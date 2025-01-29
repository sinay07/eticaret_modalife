<?php
// Veritabanı bağlantısını sağlayan dosyayı include edin
include 'mysql.php';

// POST isteğinden gelen arama metnini alın
if(isset($_POST['search_text'])) {
    $searchText = $_POST['search_text'];
    
    // Veritabanında arama yapmak için sorguyu hazırlayın
    $query = "SELECT * FROM urunler WHERE URUN_ADI LIKE :search_text";
    $stmt = $conn->prepare($query);
    $searchText = '%' . $searchText . '%';
    $stmt->bindParam(':search_text', $searchText);
    $stmt->execute();
    
    // Sonuçları alın ve ekrana yazdırın
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(count($results) > 0) {
        echo '<p>Sonuçlar</p>';
        foreach($results as $result) {
            // Her bir ürün için ürün adını ve resmini görüntüle
            echo '<a href="product.php?id=' . $result['KAYIT_ID'] . '" class="row">';
            echo '<div class="col-xs-3 col-sm-3">';
            echo '<img src="assets/images/upload/product/' . $result['RESIM'] . '" alt="' . $result['URUN_ADI'] . '" class="img-thumbnail">';
            echo '</div>';
            echo '<div class="col-xs-6 col-sm-9">';
            echo '<p class="text-wrap">' . $result['URUN_ADI'] . '</p>';
            echo '</div>';
            echo '</a>';
        }
    } else {
        echo '<p>Üzgünüz, aradığınız ürün bulunamadı.</p>';
    }
}
?>
