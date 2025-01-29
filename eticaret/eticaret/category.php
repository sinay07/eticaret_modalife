<?php 
// Ana ve alt kategori adlarını al
$category = isset($_GET['category']) ? urldecode($_GET['category']) : "";
$subcategory = isset($_GET['subcategory']) ? urldecode($_GET['subcategory']) : "";

// Sayfa başlığını belirle
$SayfaBaslik = !empty($subcategory) ? $subcategory : $category;
?>
<!doctype html>
<html class="no-js" lang="en">
<?php include 'view/head.php'; ?>
<body>
<div class="main-wrapper">
<?php include 'view/header.php'; ?>
<?php include 'view/sayfabaslik.php'; ?>
<?php include 'view/kategoriicerik.php'; ?>
<?php include 'view/footer.php'; ?>
</div>
<?php include 'view/script.php'; ?>
</body>
</html>