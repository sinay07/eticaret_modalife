<?php

session_start();

// Veritabanı bağlantısını dahil edin
include 'assets/fonk/mysql.php';

// Stripe kütüphanesini dahil edin
require_once 'vendor/autoload.php';

// PHPMailer kütüphanesini dahil edin
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

// Stripe API anahtarınızı ayarlayın
\Stripe\Stripe::setApiKey('sk_test_51PccqAEsZtB4ERkGKqejcBTlfKrZyngTtrOo5S0DJXEr3oViA7OrQx3l74khkNXevHN7sqEdarUGU1t8guqwSTUF00vjYoXeEb');

// Ödeme yöntemini doğrulayın
$paymentMethodId = $_POST['payment_method_id'];
$fullname = $_POST['cardholder-name'];

// Toplam tutarı hesaplayın
$session_email = $_SESSION['EPOSTA'];
$sql = "SELECT * FROM sepet WHERE EPOSTA = :session_email";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':session_email', $session_email);
$stmt->execute();

$grandTotal = 0;
$orderItems = [];

while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $orderItems[] = [
        'URUN_KODU' => $row['URUN_KODU'],
        'URUN_ADI' => $row['URUN_ADI'],
        'ADET' => $row['ADET'],
        'BIRIM_FIYAT' => $row['FIYAT'],
        'RESIM' => $row['RESIM'] // Ürün resmini ekleyin
    ];
    $grandTotal += $row['TOPLAM'];
}

// Kargo ücretini hesaplayın
$shippingCost = $grandTotal >= 100 ? 0 : 10;
$totalAmount = $grandTotal + $shippingCost;

// Benzersiz sipariş ID'si oluştur
$uniqueOrderId = uniqid();

try {
    // Müşteri bilgilerini alın
    $email = $_SESSION['EPOSTA'];
    $phone = '+905412345678'; //telefon numarası, uygun bir şekilde güncellemelisiniz
    
    // Stripe Müşterisi oluşturun
    $customer = \Stripe\Customer::create([
        'name' => $fullname,
        'email' => $email,
        'phone' => $phone,
        'payment_method' => $paymentMethodId,
    ]);

// Stripe ödeme işlemini oluşturun
$paymentIntent = \Stripe\PaymentIntent::create([
    'customer' => $customer->id,
    'payment_method' => $paymentMethodId,
    'amount' => $totalAmount * 100, // Ödeme miktarını cent cinsinden girin
    'currency' => 'chf', // Ödeme para birimini buraya yazın
    'description' => 'Satın Alma',
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

        // Sepeti boşalt
        $stmt = $conn->prepare("DELETE FROM sepet WHERE EPOSTA = ?");
        $stmt->execute([$email]);

        // Ödeme başarılı mesajı
        //echo "Ödeme başarılı! Ödeme ID: " . $paymentIntent->id;
        $_SESSION['odemeID'] = $paymentIntent->id;
        $_SESSION['siparisID'] = $uniqueOrderId;
        unset($_SESSION['TOTAL']);
        
        // PDF'i oluştur
        $pdf_content = createPDF($uniqueOrderId, $email);

        // PDF dosyasını geçici bir dosyaya kaydet
        $pdf_file = tempnam(sys_get_temp_dir(), 'pdf');
        file_put_contents($pdf_file, $pdf_content);

        // PHPMailer ile e-posta gönder
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'mailadresi@gmail.com';
            $mail->Password = 'şifre';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('mail@gmail.com', 'Satın Alma');
            $mail->addAddress($email, $fullname);

            $mail->addAttachment($pdf_file, 'bestellung_' . $uniqueOrderId . '.pdf');

            $mail->isHTML(true);
            $mail->Subject = 'Eticaret';
            $mail->Body    = 'Siparişiniz için teşekkür ederiz. Faturanız ekli dosyada yer almaktadır.';

            $mail->send();
            echo 'E-Mail gönderildi';
        } catch (Exception $e) {
            echo "E-posta gönderilemedi. Mailer Hatası: {$mail->ErrorInfo}";
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
function createPDF($siparis_no, $eposta) {
    global $conn;
    
    // Sipariş ve müşteri bilgilerini al
    $query = "SELECT s.*, a.ADSOYAD, a.ADRES, a.TELEFON, a.ULKE, a.SEHIR, a.EYALET, a.POSTA_KODU, o.TOPLAM_TUTAR
    FROM siparisler s 
    INNER JOIN adresler a ON s.EPOSTA = a.EPOSTA 
    INNER JOIN odeme o ON s.SIPARIS_NO = o.SIPARIS_NO
    WHERE s.SIPARIS_NO = :siparis_no";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':siparis_no', $siparis_no);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $siparis_no = $row['SIPARIS_NO'];
        $eposta = $row['EPOSTA'];
        $siparis_tarih = $row['SIPARIS_TARIH'];
        $ad_soyad = $row['ADSOYAD'];
        $adres = $row['ADRES'];
        $telefon = $row['TELEFON'];
        $ulke = $row['ULKE'];
        $sehir = $row['SEHIR'];
        $eyalet = $row['EYALET'];
        $postakodu = $row['POSTA_KODU'];
        $toplam_tutar = $row['TOPLAM_TUTAR'];
    } else {
        die("Sipariş bulunamadı.");
    }

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
    $pdf->Cell(0, 20, 'Mirans Baby World', 0, 1, 'C', true);

    $pdf->SetFont('dejavusans', '', 12);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(0, 10, 'Tarih: ' . date('d/m/Y'), 0, 1, 'R');
    $pdf->Cell(0, 10, 'Sipariş Numarası: ' . $siparis_no, 0, 1, 'R');
    
    // Barkod ekle
    $pdf->write1DBarcode($siparis_no, 'C128', 150, 50, 50, 10, '', array('border' => false));

    // Sipariş bilgilerini yaz
    $pdf->Ln();
    $pdf->Cell(0, 10, 'Sipariş Numarası: ' . $siparis_no, 0, 1);
    $pdf->Cell(0, 10, 'E-Posta: ' . $eposta, 0, 1);
    $pdf->Cell(0, 10, 'Sipariş Tarihi: ' . $siparis_tarih, 0, 1);
    $pdf->Cell(0, 10, 'Ad Soyad: ' . $ad_soyad, 0, 1);
    $pdf->Cell(0, 10, 'Adres: ' . $adres, 0, 1);
    $pdf->Cell(0, 10, 'Telefon: ' . $telefon, 0, 1);
    $pdf->Cell(0, 10, 'Ülke: ' . $ulke, 0, 1);
    $pdf->Cell(0, 10, 'Şehir: ' . $sehir, 0, 1);
    $pdf->Cell(0, 10, 'Eyalet: ' . $eyalet, 0, 1);
    $pdf->Cell(0, 10, 'Posta Kodu: ' . $postakodu, 0, 1);
    
    // Ürünlerin tablosunu oluştur
    $pdf->Ln();
    $pdf->SetFont('dejavusans', '', 12);
    $pdf->SetFillColor(240, 240, 240);
    $pdf->Cell(30, 10, 'Ürün Kodu', 1, 0, 'C', true);
    $pdf->Cell(80, 10, 'Ürün Adı', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Ürün Resmi', 1, 0, 'C', true);
    $pdf->Cell(20, 10, 'Adet', 1, 0, 'C', true);
    $pdf->Cell(30, 10, 'Birim Fiyatı', 1, 1, 'C', true);
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $resim_url = $row['URUN_RESIM'] ? 'assets/images/upload/product/' . $row['URUN_RESIM'] : '';
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

        // HTML içeriği ile resim ekleyin
        $link = $resim_url ? '<a href="' . $resim_url . '">Tıklayın</a>' : 'Resim Yok';
        $pdf->writeHTMLCell(30, 10, '', '', $link, 1, 0, true, 'C', true);

        $pdf->Cell(20, 10, $row['ADET'], 1);
        $pdf->Cell(30, 10, number_format($row['BIRIM_FIYAT'], 2) . ' CHF', 1);
        $pdf->Ln();
    }
    
    // Toplam hesapla ve kargo bilgisi ekle
    $toplam = $toplam_tutar;
    $kargo_ucreti = $toplam >= 100 ? 0 : 10;
    $toplam_odeme = $toplam + $kargo_ucreti;
    
    $pdf->Ln();
    $pdf->SetFont('dejavusans', '', 12);
    $pdf->Cell(150, 10, 'Toplam Tutar:', 0, 0, 'R');
    $pdf->Cell(40, 10, number_format($toplam, 2) . ' CHF', 0, 1, 'R');
    $pdf->Cell(150, 10, 'Kargo Ücreti:', 0, 0, 'R');
    $pdf->Cell(40, 10, ($kargo_ucreti > 0 ? '10.00 CHF' : 'Ücretsiz'), 0, 1, 'R');
    $pdf->Cell(150, 10, 'Toplam Ödeme:', 0, 0, 'R');
    $pdf->Cell(40, 10, number_format($toplam_odeme, 2) . ' CHF', 0, 1, 'R');
    
    // PDF'yi kullanıcıya sunun
    $pdf_output = $pdf->Output('sipariş_onay_' . $siparis_no . '.pdf', 'S');
    return $pdf_output;
}
?>
