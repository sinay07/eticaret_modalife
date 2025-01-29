<?php
session_start();

// Veritabanı bağlantısını dahil et
include 'assets/fonk/mysql.php';

// Stripe kütüphanesini dahil et
require_once 'vendor/autoload.php';

// PHPMailer kütüphanesini dahil et
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

// Stripe API anahtarınızı ayarla
\Stripe\Stripe::setApiKey('sk_test_51PccqAEsZtB4ERkGKqejcBTlfKrZyngTtrOo5S0DJXEr3oViA7OrQx3l74khkNXevHN7sqEdarUGU1t8guqwSTUF00vjYoXeEb');

// Ödeme yöntemini doğrula
$paymentMethodId = $_POST['payment_method_id'];
$fullname = $_POST['first-name'] . " " . $_POST['last-name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$city = $_POST['city'];
$state = $_POST['state'];
$country = $_POST['country'];
$zip = $_POST['zip'];

// Toplam tutarı hesapla
$grandTotal = 0;
$orderItems = [];

if (isset($_SESSION['sepet']) && !empty($_SESSION['sepet'])) {
    foreach ($_SESSION['sepet'] as $item) {
        $orderItems[] = [
            'URUN_KODU' => $item['URUN_KODU'],
            'URUN_ADI' => $item['URUN_ADI'],
            'ADET' => $item['ADET'],
            'BIRIM_FIYAT' => $item['FIYAT'],
            'RESIM' => $item['RESIM'] // Ürün resmini ekle
        ];
        $grandTotal += $item['FIYAT'] * $item['ADET'];
    }
} else {
    header("Location: payment-error.php");
}

// Kargo ücretini hesapla
$shippingCost = $grandTotal >= 100 ? 0 : 10;
$totalAmount = $grandTotal + $shippingCost;

// Benzersiz sipariş ID'si oluştur
$uniqueOrderId = uniqid();

try {
    // Stripe Müşterisi oluştur
    $customer = \Stripe\Customer::create([
        'name' => $fullname,
        'email' => $email,
        'phone' => $phone,
        'payment_method' => $paymentMethodId,
    ]);

    // Stripe ödeme işlemini oluştur
    $paymentIntent = \Stripe\PaymentIntent::create([
        'customer' => $customer->id,
        'payment_method' => $paymentMethodId,
        'amount' => $totalAmount * 100, // Ödeme miktarını cent cinsinden gir
        'currency' => 'chf', // Ödeme para birimini buraya yaz
        'description' => 'Sipariş Özeti',
        'confirmation_method' => 'automatic',
        'confirm' => true,
        'receipt_email' => $email,
        'return_url' => 'http://localhost/mirans/checkout-success.php',
    ]);

    // Ödeme başarılıysa
    if ($paymentIntent->status === 'succeeded') {
        // Ödeme bilgilerini veritabanına kaydet
        $stmt = $conn->prepare("INSERT INTO odeme (SIPARIS_NO, EPOSTA, ODEME_TARIH, ODEME_YONTEM, TOPLAM_TUTAR) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$uniqueOrderId, $email, date('Y-m-d H:i:s'), 'Stripe', $grandTotal]);

        // Sipariş detaylarını siparisler tablosuna kaydet
        foreach ($orderItems as $item) {
            $stmt = $conn->prepare("INSERT INTO siparisler (SIPARIS_NO, EPOSTA, SIPARIS_TARIH, URUN_KODU, URUN_ADI, ADET, BIRIM_FIYAT, URUN_RESIM) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$uniqueOrderId, $email, date('Y-m-d H:i:s'), $item['URUN_KODU'], $item['URUN_ADI'], $item['ADET'], $item['BIRIM_FIYAT'], $item['RESIM']]);
        }

        // Adres bilgilerini adresler tablosuna kaydet
        $stmt = $conn->prepare("INSERT INTO adreslerno (ADSOYAD, EPOSTA, TELEFON, FIRMA, ADRES, ULKE, SEHIR, EYALET, POSTA_KODU, SIPARIS_NO) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$fullname, $email, $phone, '', $address, $country, $city, $state, $zip, $uniqueOrderId]);

        // Sepeti boşalt
        unset($_SESSION['sepet']);

        // PDF'i oluştur
        $pdf_content = createPDF($uniqueOrderId, $email, $fullname, $phone, $address, $city, $state, $country, $zip);

        // PDF dosyasını geçici bir dosyaya kaydet
        $pdf_file = tempnam(sys_get_temp_dir(), 'pdf');
        file_put_contents($pdf_file, $pdf_content);

        // PHPMailer ile e-posta gönder
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'mail@gmail.com';
            $mail->Password = 'şifre';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('mail@gmail.com', 'Sipariş Özeti');
            $mail->addAddress($email, $fullname);

            $mail->addAttachment($pdf_file, 'bestellung_' . $uniqueOrderId . '.pdf');

            $mail->isHTML(true);
            $mail->Subject = 'Siparişiniz';
            $mail->Body    = 'Siparişiniz için teşekkür ederiz. Faturanız ekte yer almaktadır.';

            $mail->send();
            echo 'E-posta gönderildi';
        } catch (Exception $e) {
            echo "E-posta gönderilemedi. Hata: {$mail->ErrorInfo}";
        }

        // Geçici PDF dosyasını sil
        unlink($pdf_file);

        header("Location: payment-success.php");
        
    } else {
        // Ödeme başarısız mesajı
        header("Location: payment-error.php");
    }
} catch (Exception $e) {
    echo 'Ödeme işlemi sırasında bir hata oluştu: ' . $e->getMessage();
    header("Location: payment-error.php");
}

// PDF oluşturma fonksiyonu
function createPDF($siparis_no, $eposta, $ad_soyad, $telefon, $adres, $sehir, $eyalet, $ulke, $posta_kodu) {
    global $conn;
    
    // Sipariş ürünlerini al
    $query = "SELECT * FROM siparisler WHERE SIPARIS_NO = :siparis_no";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':siparis_no', $siparis_no);
    $stmt->execute();
    
    // TCPDF nesnesini oluştur
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->AddPage();
    
    // Arka plan rengi ve başlık
    $pdf->SetFillColor(240, 240, 240);
    $pdf->SetFont('dejavusans', 'B', 20);
    $pdf->SetTextColor(52, 58, 64);
    $pdf->Cell(0, 20, 'Sipariş Özeti', 0, 1, 'C', true);

    $pdf->SetFont('dejavusans', '', 12);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(0, 10, 'Tarih: ' . date('d/m/Y'), 0, 1, 'R');
    $pdf->Cell(0, 10, 'Sipariş Numarası: ' . $siparis_no, 0, 1, 'R');
    
    // Barkodu ekle
    $pdf->write1DBarcode($siparis_no, 'C128', 150, 50, 50, 10, '', array('border' => false));

    // Sipariş bilgilerini yaz
    $pdf->Ln();
    $pdf->Cell(0, 10, 'Sipariş Numarası: ' . $siparis_no, 0, 1);
    $pdf->Cell(0, 10, 'E-posta: ' . $eposta, 0, 1);
    $pdf->Cell(0, 10, 'Ad ve Soyad: ' . $ad_soyad, 0, 1);
    $pdf->Cell(0, 10, 'Adres: ' . $adres, 0, 1);
    $pdf->Cell(0, 10, 'Telefon: ' . $telefon, 0, 1);
    $pdf->Cell(0, 10, 'Ülke: ' . $ulke, 0, 1);
    $pdf->Cell(0, 10, 'Şehir: ' . $sehir, 0, 1);
    $pdf->Cell(0, 10, 'Eyalet: ' . $eyalet, 0, 1);
    $pdf->Cell(0, 10, 'Posta Kodu: ' . $posta_kodu, 0, 1);

    // Ürünlerin tablosunu oluştur
    $pdf->Ln();
    $pdf->SetFont('dejavusans', '', 12);
    $pdf->SetFillColor(240, 240, 240);
    $pdf->Cell(30, 10, 'Ürün Kodu', 1, 0, 'C', true);
    $pdf->Cell(80, 10, 'Ürün Adı', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Ürün Resmi', 1, 0, 'C', true);
    $pdf->Cell(20, 10, 'Miktar', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Birim Fiyat', 1, 1, 'C', true);
    
    $toplam = 0;
    $kargo_ucreti = 0;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $resim_url = $row['URUN_RESIM'] ? 'assets/images/upload/product/' . $row['URUN_RESIM'] : '';
        $urun_adi = $row['URUN_ADI'];
        $max_width = 80;  // Ürün adı hücre genişliği

        // Ürün adını kısaltmak için kontrol
        $pdf->SetFont('dejavusans', '', 12);
        $text_width = $pdf->GetStringWidth($urun_adi);
        
        if ($text_width > $max_width) {
            $truncate_length = floor(($max_width * strlen($urun_adi)) / $text_width);
            $urun_adi = substr($urun_adi, 0, $truncate_length - 3) . '...';
        }
        
        $pdf->Cell(30, 10, $row['URUN_KODU'], 1);
        $pdf->Cell(80, 10, $urun_adi, 1);

        // HTML içeriği ile resim ekle
        $link = $resim_url ? '<a href="' . $resim_url . '">Tıklayın</a>' : 'Resim Yok';
        $pdf->writeHTMLCell(30, 10, '', '', $link, 1, 0, true, 'C', true);

        $pdf->Cell(20, 10, $row['ADET'], 1);
        $pdf->Cell(30, 10, number_format($row['BIRIM_FIYAT'], 2) . ' CHF', 1);
        $pdf->Ln();

        // Toplam tutarı hesapla
        $toplam += $row['BIRIM_FIYAT'] * $row['ADET'];
    }
    
    // Kargo ücreti hesapla
    $kargo_ucreti = $toplam >= 100 ? 0 : 10;
    $toplam_odeme = $toplam + $kargo_ucreti;
    
    // Toplamı ve kargo bilgilerini ekle
    $pdf->Ln();
    $pdf->SetFont('dejavusans', '', 12);
    $pdf->Cell(150, 10, 'Toplam Tutar:', 0, 0, 'R');
    $pdf->Cell(40, 10, number_format($toplam, 2) . ' CHF', 0, 1, 'R');
    $pdf->Cell(150, 10, 'Kargo Ücreti:', 0, 0, 'R');
    $pdf->Cell(40, 10, ($kargo_ucreti > 0 ? '10.00 CHF' : 'Ücretsiz'), 0, 1, 'R');
    $pdf->Cell(150, 10, 'Toplam Ödeme:', 0, 0, 'R');
    $pdf->Cell(40, 10, number_format($toplam_odeme, 2) . ' CHF', 0, 1, 'R');
    
    // PDF'yi kullanıcıya sun
    $pdf_output = $pdf->Output('siparis_onay_' . $siparis_no . '.pdf', 'S');
    return $pdf_output;
}
?>
