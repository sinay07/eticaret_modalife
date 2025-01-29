-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3306
-- Üretim Zamanı: 27 Oca 2025, 10:47:13
-- Sunucu sürümü: 10.4.10-MariaDB
-- PHP Sürümü: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `eticaret`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `aboneler`
--

DROP TABLE IF EXISTS `aboneler`;
CREATE TABLE IF NOT EXISTS `aboneler` (
  `KAYIT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `EPOSTA` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`KAYIT_ID`),
  UNIQUE KEY `EPOSTA` (`EPOSTA`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `adresler`
--

DROP TABLE IF EXISTS `adresler`;
CREATE TABLE IF NOT EXISTS `adresler` (
  `KAYIT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `ADSOYAD` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `EPOSTA` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `TELEFON` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `FIRMA` varchar(50) COLLATE utf8_turkish_ci DEFAULT NULL,
  `ADRES` varchar(500) COLLATE utf8_turkish_ci NOT NULL,
  `ULKE` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `SEHIR` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `EYALET` varchar(50) COLLATE utf8_turkish_ci DEFAULT NULL,
  `POSTA_KODU` varchar(50) COLLATE utf8_turkish_ci DEFAULT NULL,
  PRIMARY KEY (`KAYIT_ID`),
  UNIQUE KEY `EPOSTA` (`EPOSTA`),
  UNIQUE KEY `TELEFON` (`TELEFON`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `adresler`
--

INSERT INTO `adresler` (`KAYIT_ID`, `ADSOYAD`, `EPOSTA`, `TELEFON`, `FIRMA`, `ADRES`, `ULKE`, `SEHIR`, `EYALET`, `POSTA_KODU`) VALUES
(2, 'İpek Yemenicioğlu', 'iyemenicioglu@gmail.com', '5415310366', 'SDASD', 'Emek Mahallesi', 'Türkiye', 'Kocaeli', 'Çayırova', '41400');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `adreslerno`
--

DROP TABLE IF EXISTS `adreslerno`;
CREATE TABLE IF NOT EXISTS `adreslerno` (
  `KAYIT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `ADSOYAD` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `EPOSTA` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `TELEFON` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `FIRMA` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `ADRES` varchar(250) COLLATE utf8_turkish_ci NOT NULL,
  `ULKE` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `SEHIR` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `EYALET` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `POSTA_KODU` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `SIPARIS_NO` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`KAYIT_ID`),
  UNIQUE KEY `SIPARIS_NO` (`SIPARIS_NO`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `alt_kategori`
--

DROP TABLE IF EXISTS `alt_kategori`;
CREATE TABLE IF NOT EXISTS `alt_kategori` (
  `KAYIT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `ANA_KATEGORI_ADI` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `ALT_KATEGORI_ADI` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `GIZLE` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`KAYIT_ID`),
  UNIQUE KEY `ALT_KATEGORI_ADI` (`ALT_KATEGORI_ADI`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `alt_kategori`
--

INSERT INTO `alt_kategori` (`KAYIT_ID`, `ANA_KATEGORI_ADI`, `ALT_KATEGORI_ADI`, `GIZLE`) VALUES
(1, 'Moda', 'Kadın Modası', 0),
(2, 'Moda', 'Erkek Modası', 0),
(3, 'Moda', 'Çocuk Modası', 0),
(4, 'Moda', 'Sezonluk Trendler', 0),
(5, 'Moda', 'Aksesuar & Tamamlayıcılar', 0),
(6, 'Güzellik & Bakım', 'Cilt Bakımı', 0),
(7, 'Güzellik & Bakım', 'Doğal Ürünler', 0),
(8, 'Yaşam & Dekorasyon', 'Ev Dekorasyonu', 0),
(9, 'Yaşam & Dekorasyon', 'Minimalist Ürünler', 0),
(10, 'Sağlık', 'Egzersiz Ürünleri', 0),
(11, 'Sağlık', 'Spor Giyim', 0),
(12, 'Sağlık', 'Diyet Ürünleri', 0),
(13, 'Deneme', 'Deneme Alt Kategori', 0);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ana_kategori`
--

DROP TABLE IF EXISTS `ana_kategori`;
CREATE TABLE IF NOT EXISTS `ana_kategori` (
  `KAYIT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `ANA_KATEGORI_ADI` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`KAYIT_ID`),
  UNIQUE KEY `KATEGORI_ADI` (`ANA_KATEGORI_ADI`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `ana_kategori`
--

INSERT INTO `ana_kategori` (`KAYIT_ID`, `ANA_KATEGORI_ADI`) VALUES
(1, 'Moda'),
(2, 'Güzellik & Bakım'),
(3, 'Yaşam & Dekorasyon'),
(4, 'Sağlık'),
(6, 'Deneme');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `bedenler`
--

DROP TABLE IF EXISTS `bedenler`;
CREATE TABLE IF NOT EXISTS `bedenler` (
  `KAYIT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `BEDEN_ADI` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`KAYIT_ID`),
  UNIQUE KEY `BEDEN_ADI` (`BEDEN_ADI`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `bedenler`
--

INSERT INTO `bedenler` (`KAYIT_ID`, `BEDEN_ADI`) VALUES
(1, 'S'),
(2, 'M'),
(3, 'L'),
(4, 'XL'),
(5, 'XXL');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `duyuru_banner`
--

DROP TABLE IF EXISTS `duyuru_banner`;
CREATE TABLE IF NOT EXISTS `duyuru_banner` (
  `KAYIT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `RESIM` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `BASLIK` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `LINK` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`KAYIT_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `firma_bilgileri`
--

DROP TABLE IF EXISTS `firma_bilgileri`;
CREATE TABLE IF NOT EXISTS `firma_bilgileri` (
  `KAYIT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `FIRMA_ADI` varchar(100) COLLATE utf8_turkish_ci DEFAULT NULL,
  `FIRMA_TELEFON1` varchar(50) COLLATE utf8_turkish_ci DEFAULT NULL,
  `FIRMA_TELEFON2` varchar(50) COLLATE utf8_turkish_ci DEFAULT NULL,
  `FIRMA_EPOSTA1` varchar(50) COLLATE utf8_turkish_ci DEFAULT NULL,
  `FIRMA_EPOSTA2` varchar(50) COLLATE utf8_turkish_ci DEFAULT NULL,
  `SLOGAN` varchar(100) COLLATE utf8_turkish_ci DEFAULT NULL,
  `FACEBOOK` varchar(100) COLLATE utf8_turkish_ci DEFAULT NULL,
  `INSTAGRAM` varchar(100) COLLATE utf8_turkish_ci DEFAULT NULL,
  `X` varchar(100) COLLATE utf8_turkish_ci DEFAULT NULL,
  `LOGO` varchar(50) COLLATE utf8_turkish_ci DEFAULT NULL,
  `ADRES` varchar(500) COLLATE utf8_turkish_ci DEFAULT NULL,
  PRIMARY KEY (`KAYIT_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `firma_bilgileri`
--

INSERT INTO `firma_bilgileri` (`KAYIT_ID`, `FIRMA_ADI`, `FIRMA_TELEFON1`, `FIRMA_TELEFON2`, `FIRMA_EPOSTA1`, `FIRMA_EPOSTA2`, `SLOGAN`, `FACEBOOK`, `INSTAGRAM`, `X`, `LOGO`, `ADRES`) VALUES
(1, 'Moda Life 2', '5415310366', '987654321', 'info@modalife.com', 'destek@modalife.com', 'SELAM', '', '', '', 's', 'Yıldız Mahallesi 29 Sokak No:45 Ankara');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `indirimli_urunler`
--

DROP TABLE IF EXISTS `indirimli_urunler`;
CREATE TABLE IF NOT EXISTS `indirimli_urunler` (
  `KAYIT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `URUN_KODU` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `URUN_ADI` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `URUN_RESMI` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `URUN_FIYATI` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `INDIRIMLI_FIYAT` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `BITIS_TARIH` date DEFAULT NULL,
  `BASLANGIC_TARIH` date DEFAULT NULL,
  PRIMARY KEY (`KAYIT_ID`),
  UNIQUE KEY `URUN_KODU` (`URUN_KODU`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kaydedilenler`
--

DROP TABLE IF EXISTS `kaydedilenler`;
CREATE TABLE IF NOT EXISTS `kaydedilenler` (
  `KAYIT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `EPOSTA` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `URUN_KODU` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `URUN_RESIM` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `URUN_ADI` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `URUN_LINK` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `FIYAT` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`KAYIT_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kullanicilar`
--

DROP TABLE IF EXISTS `kullanicilar`;
CREATE TABLE IF NOT EXISTS `kullanicilar` (
  `KAYIT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `ADSOYAD` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `EPOSTA` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `SIFRE` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`KAYIT_ID`),
  UNIQUE KEY `EPOSTA` (`EPOSTA`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `kumas`
--

DROP TABLE IF EXISTS `kumas`;
CREATE TABLE IF NOT EXISTS `kumas` (
  `KAYIT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `KUMAS_CINSI` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`KAYIT_ID`),
  UNIQUE KEY `KUMAS_CINSI` (`KUMAS_CINSI`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `kumas`
--

INSERT INTO `kumas` (`KAYIT_ID`, `KUMAS_CINSI`) VALUES
(1, 'Pamuk'),
(2, 'Koton');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `mesajlar`
--

DROP TABLE IF EXISTS `mesajlar`;
CREATE TABLE IF NOT EXISTS `mesajlar` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `GONDEREN_EPOSTA` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `ALICI_EPOSTA` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `URUN_ID` int(11) NOT NULL,
  `MESAJ` text COLLATE utf8_turkish_ci NOT NULL,
  `ZAMAN` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `odeme`
--

DROP TABLE IF EXISTS `odeme`;
CREATE TABLE IF NOT EXISTS `odeme` (
  `KAYIT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `SIPARIS_NO` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `EPOSTA` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `ODEME_TARIH` datetime NOT NULL DEFAULT current_timestamp(),
  `ODEME_YONTEM` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `TOPLAM_TUTAR` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`KAYIT_ID`),
  UNIQUE KEY `SIPARIS_NO` (`SIPARIS_NO`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `odeme`
--

INSERT INTO `odeme` (`KAYIT_ID`, `SIPARIS_NO`, `EPOSTA`, `ODEME_TARIH`, `ODEME_YONTEM`, `TOPLAM_TUTAR`) VALUES
(2, '679717b8a00dc', 'iyemenicioglu@gmail.com', '2025-01-27 05:20:58', 'Stripe', '1197');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `sepet`
--

DROP TABLE IF EXISTS `sepet`;
CREATE TABLE IF NOT EXISTS `sepet` (
  `KAYIT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `EPOSTA` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `URUN_KODU` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `RESIM` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `URUN_ADI` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `URUN_LINK` varchar(500) COLLATE utf8_turkish_ci NOT NULL,
  `FIYAT` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `ADET` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `TOPLAM` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`KAYIT_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `siparisler`
--

DROP TABLE IF EXISTS `siparisler`;
CREATE TABLE IF NOT EXISTS `siparisler` (
  `KAYIT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `SIPARIS_NO` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `EPOSTA` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `SIPARIS_TARIH` datetime NOT NULL,
  `URUN_KODU` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `URUN_ADI` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `ADET` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `BIRIM_FIYAT` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `DURUM` varchar(50) COLLATE utf8_turkish_ci NOT NULL DEFAULT 'BEKLİYOR',
  `URUN_RESIM` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`KAYIT_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `siparisler`
--

INSERT INTO `siparisler` (`KAYIT_ID`, `SIPARIS_NO`, `EPOSTA`, `SIPARIS_TARIH`, `URUN_KODU`, `URUN_ADI`, `ADET`, `BIRIM_FIYAT`, `DURUM`, `URUN_RESIM`) VALUES
(2, '679717b8a00dc', 'iyemenicioglu@gmail.com', '2025-01-27 05:20:58', 'A123456', 'Önü Büzgülü Askılı Elbise', '3', '399', 'TESLİM EDİLDİ', 'cover_6793d3edc581b.webp');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `slider`
--

DROP TABLE IF EXISTS `slider`;
CREATE TABLE IF NOT EXISTS `slider` (
  `KAYIT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `RESIM` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `BASLIK` varchar(100) COLLATE utf8_turkish_ci DEFAULT NULL,
  `BUTON_METNI` varchar(100) COLLATE utf8_turkish_ci DEFAULT NULL,
  `BUTON_LINK` varchar(100) COLLATE utf8_turkish_ci DEFAULT NULL,
  PRIMARY KEY (`KAYIT_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `slider`
--

INSERT INTO `slider` (`KAYIT_ID`, `RESIM`, `BASLIK`, `BUTON_METNI`, `BUTON_LINK`) VALUES
(3, '6793d9dde2ca1.webp', 'Tarzını Yansıt, Dünyayı Fark Ettir!', 'İNCELE', 'http://localhost/x/category.php?category=Moda&subcategory=Kadın+Modası'),
(4, '6793da46c087a.webp', 'Modayı Takip Et!', 'İNCELE', 'http://localhost/x/category.php?category=Moda');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `urunler`
--

DROP TABLE IF EXISTS `urunler`;
CREATE TABLE IF NOT EXISTS `urunler` (
  `KAYIT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `URUN_KODU` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `RESIM` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `URUN_ADI` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `ACIKLAMA` varchar(500) COLLATE utf8_turkish_ci NOT NULL,
  `FIYAT` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `STOK` int(11) NOT NULL,
  `ETIKETLER` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `KUMAS_CINSI` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `CINSIYET` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `URUN_LINK` varchar(500) COLLATE utf8_turkish_ci NOT NULL,
  `PUAN` float NOT NULL,
  `ANA_KATEGORI` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `ALT_KATEGORI` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`KAYIT_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `urunler`
--

INSERT INTO `urunler` (`KAYIT_ID`, `URUN_KODU`, `RESIM`, `URUN_ADI`, `ACIKLAMA`, `FIYAT`, `STOK`, `ETIKETLER`, `KUMAS_CINSI`, `CINSIYET`, `URUN_LINK`, `PUAN`, `ANA_KATEGORI`, `ALT_KATEGORI`) VALUES
(1, 'A123456', 'cover_6793d3edc581b.webp', 'Önü Büzgülü Askılı Elbise', '%70 Polyester % 20 Pamuk %10 Elastan', '399', 50, 'büzgülü, askılı, elbise', 'Pamuk', 'Kadın', 'Önü Büzgülü Askılı Elbise/A123456', 1.9, 'Moda', 'Kadın Modası'),
(2, 'A123457', 'cover_6793d4d10f416.webp', 'Yakası Güpürlü V Yaka Elbise', '%100 Polyester', '299', 50, 'büzgülü, askılı, elbise', 'Pamuk', 'Kadın', 'Yakası Güpürlü V Yaka Elbise/A123457', 1.9, 'Moda', 'Kadın Modası'),
(3, '2342F', 'cover_6797198a95660.webp', 'Deneme Ürün', 'Deneme açıklama', '500', 50, 'denem ürün, deneme ürün 2', 'Pamuk', 'Kadın', 'Deneme Ürün/2342F', 1.9, 'Deneme', 'Deneme Alt Kategori');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `varyant_beden`
--

DROP TABLE IF EXISTS `varyant_beden`;
CREATE TABLE IF NOT EXISTS `varyant_beden` (
  `KAYIT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `URUN_KODU` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `BEDEN` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`KAYIT_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `varyant_beden`
--

INSERT INTO `varyant_beden` (`KAYIT_ID`, `URUN_KODU`, `BEDEN`) VALUES
(1, 'A123456', 'S'),
(2, 'A123456', 'M'),
(3, 'A123456', 'L'),
(4, 'A123457', 'S'),
(5, 'A123457', 'M'),
(6, '2342F', 'S'),
(7, '2342F', 'M'),
(8, '2342F', 'L'),
(9, '2342F', 'XL');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `varyant_renk`
--

DROP TABLE IF EXISTS `varyant_renk`;
CREATE TABLE IF NOT EXISTS `varyant_renk` (
  `KAYIT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `URUN_KODU` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `RENK_ADI` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `RENK_KODU` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`KAYIT_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `varyant_resim`
--

DROP TABLE IF EXISTS `varyant_resim`;
CREATE TABLE IF NOT EXISTS `varyant_resim` (
  `KAYIT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `URUN_KODU` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `RESIM` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`KAYIT_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `varyant_resim`
--

INSERT INTO `varyant_resim` (`KAYIT_ID`, `URUN_KODU`, `RESIM`) VALUES
(1, 'A123456', 'product_6793d3ee178a5_0.webp');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `yonetici`
--

DROP TABLE IF EXISTS `yonetici`;
CREATE TABLE IF NOT EXISTS `yonetici` (
  `KAYIT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `KULLANICI_ADI` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `SIFRE` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`KAYIT_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `yonetici`
--

INSERT INTO `yonetici` (`KAYIT_ID`, `KULLANICI_ADI`, `SIFRE`) VALUES
(1, 'admin', '$2y$10$H1o4yD588SzmWB5XCuPU7O88hr95Wp.OvulDFmULpwfONfCy8Ou1G');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `yorumlar`
--

DROP TABLE IF EXISTS `yorumlar`;
CREATE TABLE IF NOT EXISTS `yorumlar` (
  `KAYIT_ID` int(11) NOT NULL AUTO_INCREMENT,
  `URUN_ID` int(11) NOT NULL,
  `EPOSTA` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `ADSOYAD` varchar(255) COLLATE utf8_turkish_ci NOT NULL,
  `YORUM` text COLLATE utf8_turkish_ci NOT NULL,
  `ZAMAN` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`KAYIT_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
