<?php
$servername = "localhost"; // MySQL sunucu adımızı tanımlıyoruz.
$username = "root"; // MySQL sunucu kullanıcı adımızı tanımlıyoruz.
$password = ""; // MySQL sunucu şifremizi tanımlıyoruz.
$dbname = "eticaret"; // MySQL database adımızı tanımlıyoruz.

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // PDO istisna durumlarını ayarla
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // UTF-8 karakter kodlaması
    $conn->exec("SET NAMES utf8");
} catch(PDOException $e) {
    echo "Bağlantı hatası: " . $e->getMessage();
}
?>
