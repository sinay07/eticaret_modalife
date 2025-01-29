<?php
include 'mysql.php';

if(isset($_GET['anaKategori'])) {
    $anaKategori = $_GET['anaKategori'];

    $stmt = $conn->prepare("SELECT ALT_KATEGORI_ADI FROM alt_kategori WHERE ANA_KATEGORI_ADI = :anaKategori");
    $stmt->bindParam(':anaKategori', $anaKategori, PDO::PARAM_STR);
    $stmt->execute();
    $altKategoriler = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $altKategorilerList = [];
    foreach ($altKategoriler as $altKategori) {
        $altKategorilerList[] = $altKategori['ALT_KATEGORI_ADI'];
    }

    echo json_encode($altKategorilerList);
}
?>
