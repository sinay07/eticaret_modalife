<?php  
include 'assets/fonk/mysql.php';

// Kullanıcının oturum e-posta adresini al
$session_email = isset($_SESSION['EPOSTA']) ? $_SESSION['EPOSTA'] : '';

// Veritabanından kullanıcının oturum e-posta adresine göre verileri çek
$sql = "SELECT * FROM mesajlar WHERE ALICI_EPOSTA = :session_email ORDER BY ZAMAN DESC"; // Mesajları zaman sırasına göre alıyoruz
$stmt = $conn->prepare($sql);
$stmt->bindParam(':session_email', $session_email);
$stmt->execute();

// Sorgu sonucundaki satır sayısını kontrol et
if ($stmt->rowCount() > 0) {
    // Mesajları gruplamak için bir dizi oluşturuyoruz
    $messages = [];
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $mesaj_id = $row["URUN_ID"];
        $gonderen_email = $row["GONDEREN_EPOSTA"];
        $tarih = $row["ZAMAN"];
        $mesaj = $row["MESAJ"];
        $alici_email = $row["ALICI_EPOSTA"];

        // Gönderenin adı ve soyadını almak için kullanıcılar tablosuna sorgu yapalım
        $user_sql = "SELECT ADSOYAD FROM kullanicilar WHERE EPOSTA = :gonderen_email";
        $user_stmt = $conn->prepare($user_sql);
        $user_stmt->bindParam(':gonderen_email', $gonderen_email);
        $user_stmt->execute();

        $gonderen_adsoyad = '';
        if ($user_stmt->rowCount() > 0) {
            $user_row = $user_stmt->fetch(PDO::FETCH_ASSOC);
            $gonderen_adsoyad = $user_row['ADSOYAD'];
        }

        // Mesajları birleştirmek için gönderen e-posta ile grupluyoruz
        if (!isset($messages[$gonderen_email])) {
            $messages[$gonderen_email] = [
                'gonderen_adsoyad' => $gonderen_adsoyad,
                'gonderen_email' => $gonderen_email,
                'mesajlar' => []
            ];
        }
        // Her bir mesajı bu grupta tutuyoruz
        $messages[$gonderen_email]['mesajlar'][] = [
            'mesaj_id' => $mesaj_id,
            'mesaj' => $mesaj,
            'tarih' => $tarih
        ];
    }
    ?>
    <div class="page-section section section-padding">
        <div class="container">
            <h2 class="text-center mb-4">Mesajlarınız</h2>
            <div class="row">
                <div class="col-12">
                    <div class="messages-table table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Ürün</th>
                                    <th>Gönderen</th>
                                    <th>Tarih</th>
                                    <th>Mesaj</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($messages as $gonderen_email => $message_group) {
                                    $gonderen_adsoyad = $message_group['gonderen_adsoyad'];
                                    $mesajlar = $message_group['mesajlar'];
                                    $first_message = $mesajlar[0]; // İlk mesajı gösterelim
                                    $urun_id = $first_message['mesaj_id']; // Ürün ID'si de ilk mesaja ait

                                    // Ürün resmini alalım
                                    $product_sql = "SELECT RESIM FROM urunler WHERE KAYIT_ID = :urun_id";
                                    $product_stmt = $conn->prepare($product_sql);
                                    $product_stmt->bindParam(':urun_id', $urun_id);
                                    $product_stmt->execute();
                                    $urun_resim = '';
                                    if ($product_stmt->rowCount() > 0) {
                                        $product_row = $product_stmt->fetch(PDO::FETCH_ASSOC);
                                        $urun_resim = $product_row['RESIM'];
                                    }
                                    ?>
                                    <tr class="message-row" data-gonderen-email="<?php echo htmlspecialchars($gonderen_email); ?>" data-alici-email="<?php echo htmlspecialchars($session_email); ?>">
                                        <td>
                                            <?php if ($urun_resim): ?>
                                                <img src="assets/images/upload/product/<?php echo htmlspecialchars($urun_resim); ?>" alt="Ürün Resmi" width="50" height="50">
                                            <?php else: ?>
                                                <span>Resim Bulunamadı</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($gonderen_adsoyad); ?></td>
                                        <td><?php echo htmlspecialchars($first_message['tarih']); ?></td>
                                        <td>
                                            <?php
                                            // İlk mesajı gösterelim ve geçmiş mesajları Modalda göstereceğiz
                                            echo substr(htmlspecialchars($first_message['mesaj']), 0, 100) . '...';
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Uyarı Modal -->
<div id="warningModal" class="modal" style="display: none;">
  <div class="modal-content">
    <span class="close warningModal">&times;</span> <!-- X ikonunu kaldırdık -->
    <h2 class="modal-title">Mesaj ve Cevap</h2>

    <!-- Mesajın içeriği burada görüntülenecek -->
    <div id="conversation"></div>

    <!-- Cevap yazma alanı -->
    <div class="response-form">
      <label for="responseText">Cevap:</label>
      <textarea id="responseText" rows="4" class="form-control"></textarea>
      <button id="sendResponseButton" class="modal-button">Cevapla</button>
  </div>

  <button class="modal-button warningModal">Kapat</button> <!-- Kapanma butonu -->
</div>
</div>

<?php
} else {
    echo "<div class='text-center'><h3>Henüz Mesajınız Yok</h3><p>Henüz bir mesajınız bulunmamaktadır.</p></div>";
}

// PDO bağlantısını kapat
$stmt = null;
$conn = null;
?>



<script>
document.addEventListener('DOMContentLoaded', function () {
    const messageRows = document.querySelectorAll('.message-row');
    let sendResponseButton = document.getElementById('sendResponseButton'); // Butonu burada tanımladık

    // Satırlara tıklama olayını ekleyelim
    messageRows.forEach(row => {
        row.addEventListener('click', function () {
            const gonderenEmail = this.getAttribute('data-gonderen-email');  // Gönderenin e-posta adresini burada alıyoruz
            const aliciEmail = this.getAttribute('data-alici-email');  // Alıcı e-posta adresini burada alıyoruz
            const urunId = 1;//this.getAttribute('data-urun-id');  // Ürün ID'sini burada alıyoruz

            // AJAX ile konuşma geçmişini alıyoruz
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'konusmaGecmisi.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        document.getElementById('conversation').innerHTML = response.conversation;
                    } else {
                        alert(response.message);
                    }
                }
            };

            xhr.send('gonderen_email=' + encodeURIComponent(gonderenEmail) + '&alici_email=' + encodeURIComponent(aliciEmail));

            // Modalı aç
            const warningModal = document.getElementById('warningModal');
            warningModal.style.display = 'block';

            // Cevap gönderme butonuna urun_id'yi ekleyelim
            sendResponseButton.setAttribute('data-urun-id', urunId);

            // Alıcı ve Gönderenin yerini değiştirelim
            sendResponseButton.setAttribute('data-gonderen-email', aliciEmail); // Gönderen olarak alıcıyı set ediyoruz
            sendResponseButton.setAttribute('data-alici-email', gonderenEmail); // Alıcı olarak göndereni set ediyoruz
        });
    });

    // Cevap gönderme işlemini yalnızca bir kez ekleyelim
    if (sendResponseButton) {
        sendResponseButton.addEventListener('click', function () {
            const cevap = document.getElementById('responseText').value;
            const urunId = this.getAttribute('data-urun-id');  // Burada ürün ID'sini alıyoruz
            const gonderenEmail = this.getAttribute('data-gonderen-email'); // Gönderen (asıl cevap yazan)
            const aliciEmail = this.getAttribute('data-alici-email'); // Alıcı (asıl cevabı alacak)

            if (cevap.trim() !== "") {
                // AJAX ile cevabı gönderelim
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'cevapKaydet.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        const response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            alert(response.message); // Başarı mesajı
                        } else {
                            alert(response.message); // Hata mesajı
                        }
                    }
                };

                xhr.send('gonderen_email=' + encodeURIComponent(gonderenEmail) + 
                    '&alici_email=' + encodeURIComponent(aliciEmail) +
                    '&urun_id=' + encodeURIComponent(urunId) +  // Urun_id'yi burada gönderiyoruz
                    '&cevap=' + encodeURIComponent(cevap));

                // Modalı kapat
                const warningModal = document.getElementById('warningModal');
                warningModal.style.display = 'none';
            } else {
                alert('Lütfen cevabınızı girin.');
            }
        });
    }
});

</script>
