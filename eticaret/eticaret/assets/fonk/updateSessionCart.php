<?php
session_start();

// Eğer POST'tan gelen 'cart' verisi yoksa işlem yapma
if (!isset($_POST['cart'])) {
    http_response_code(400); // Bad Request
    die();
}

// Yeni sepet verilerini POST'tan al
$new_cart = $_POST['cart'];

// Session'daki sepet verilerini güncelle
$_SESSION['cart'] = $new_cart;

// Yeni toplam fiyatı hesapla
$total_price = 0;
foreach ($new_cart as $item) {
    $total_price += $item['toplam'];
}

// Güncellenmiş toplam fiyatı JSON olarak geri döndür
echo json_encode(['totalToplam' => $total_price . " ₺"]);
?>
