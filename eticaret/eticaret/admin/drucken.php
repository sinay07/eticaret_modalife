<?php
require_once '../vendor/autoload.php';
include 'assets/fonk/mysql.php';

if (isset($_GET['q'])) {
    $siparis_no = $_GET['q'];

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
    $query = "SELECT s.URUN_KODU, s.URUN_ADI, s.ADET, s.BIRIM_FIYAT, u.RESIM
    FROM siparisler s 
    INNER JOIN urunler u ON s.URUN_KODU = u.URUN_KODU
    WHERE s.SIPARIS_NO = :siparis_no";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':siparis_no', $siparis_no);
    $stmt->execute();
    
    $urunler = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // TCPDF nesnesini oluştur
    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
    $pdf->AddPage();
    
    $pdf->SetFillColor(240, 240, 240); // Arka plan rengi
    $pdf->SetFont('dejavusans', 'B', 20);
    $pdf->SetTextColor(52, 58, 64);
    $pdf->Cell(0, 20, 'Modalife', 0, 1, 'C', true);
    
    $pdf->SetFont('dejavusans', '', 12);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(0, 10, 'Tarih: ' . date('d/m/Y'), 0, 1, 'R');
    $pdf->Cell(0, 10, 'Sipariş Numarası: ' . $siparis_no, 0, 1, 'R');

    // Barkodu ekle
    $pdf->write1DBarcode($siparis_no, 'C128', 149, 50, 50, 10, '', array('border' => false));

    // Sipariş bilgilerini yaz
    $pdf->Ln();
    $pdf->Cell(0, 10, 'Sipariş Numarası: ' . $siparis_no, 0, 1);
    $pdf->Cell(0, 10, 'E-Posta: ' . $eposta, 0, 1);
    $pdf->Cell(0, 10, 'Sipariş Tarihi: ' . $siparis_tarih, 0, 1);
    $pdf->Cell(0, 10, 'Ad ve Soyad: ' . $ad_soyad, 0, 1);
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
    $pdf->Cell(30, 10, 'Birim Fiyat', 1, 1, 'C', true);
    
    foreach ($urunler as $urun) {
        $resim_url = $urun['RESIM'] ? 'assets/images/upload/product/' . $urun['RESIM'] : '';
        $urun_adi = $urun['URUN_ADI'];
        $max_width = 80;  // Ürün adı hücre genişliği

        // Ürün adını kısaltmak için kontrol
        $pdf->SetFont('dejavusans', '', 12);
        $text_width = $pdf->GetStringWidth($urun_adi);
        
        if ($text_width > $max_width) {
            $truncate_length = floor(($max_width * strlen($urun_adi)) / $text_width);
            $urun_adi = substr($urun_adi, 0, $truncate_length - 3) . '...';
        }
        
        $pdf->Cell(30, 10, $urun['URUN_KODU'], 1);
        $pdf->Cell(80, 10, $urun_adi, 1);

        // HTML içeriği ile link ekleyin
        $link = $resim_url ? '<a href="' . $resim_url . '">Tıklayın</a>' : 'Resim Yok';
        $pdf->writeHTMLCell(30, 10, '', '', $link, 1, 0, true, 'C', true);

        $pdf->Cell(20, 10, $urun['ADET'], 1);
        $pdf->Cell(30, 10, number_format($urun['BIRIM_FIYAT'], 2) . ' ₺', 1);
        $pdf->Ln();
    }
    
    // Toplam hesapla ve kargo bilgisi ekle
    $toplam = $toplam_tutar;
    $kargo_ucreti = $toplam >= 100 ? 0 : 10;
    $toplam_odeme = $toplam + $kargo_ucreti;
    
    $pdf->Ln();
    $pdf->SetFont('dejavusans', '', 12);
    $pdf->Cell(150, 10, 'Toplam Tutar:', 0, 0, 'R');
    $pdf->Cell(40, 10, number_format($toplam, 2) . ' ₺', 0, 1, 'R');
    $pdf->Cell(150, 10, 'Kargo Ücreti:', 0, 0, 'R');
    $pdf->Cell(40, 10, ($kargo_ucreti > 0 ? '10.00 ₺' : 'Ücretsiz'), 0, 1, 'R');
    $pdf->Cell(150, 10, 'Toplam Ödeme:', 0, 0, 'R');
    $pdf->Cell(40, 10, number_format($toplam_odeme, 2) . ' ₺', 0, 1, 'R');
    
    // PDF'yi kullanıcıya sunun
    $pdf->Output('siparis_onayi_' . $siparis_no . '.pdf', 'D');
} else {
    die("Sipariş numarası belirtilmedi.");
}
?>
