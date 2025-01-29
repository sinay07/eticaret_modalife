<?php  
include 'assets/fonk/mysql.php';
$gizle = false;
// Oturum e-posta adresini al
$session_email = $_SESSION['EPOSTA'];

// SQL sorgusu
$sql = "SELECT * FROM adresler WHERE EPOSTA = :session_email";

// Sorguyu hazırla ve çalıştır
$stmt = $conn->prepare($sql);
$stmt->bindParam(':session_email', $session_email);
$stmt->execute();

// Satır sayısını kontrol et
$adresVarMi = $stmt->rowCount() > 0;

if ($adresVarMi) {
    // Veri bulunduğunda döngüyü başlat
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Verileri al
		$adsoyad = $row["ADSOYAD"];
		$telefon = $row["TELEFON"];
		$firma = $row["FIRMA"];
		$adres = $row["ADRES"];
		$ulke = $row["ULKE"];
		$sehir = $row["SEHIR"];
		$eyalet = $row["EYALET"];
		$posta_kodu = $row["POSTA_KODU"];
	}
} 

// PDO bağlantısını kapat
$stmt = null;
$conn = null;
?>

<!-- Sayfa Bölümü Başlangıç -->
<div class="page-section section section-padding">
	<div class="container">

		<!-- Ödeme Formu -->
		<form id="payment-form" action="checkout-process.php" method="post" class="checkout-form">
			<div class="row row-50 mbn-40">

				<div class="col-lg-7">

					<!-- Fatura Adresi -->
					<?php  
					if(isset($_SESSION['EPOSTA'])) {
                        // E-posta oturumu tanımlıysa, yani kullanıcı giriş yapmışsa, alanları gizle
						$display = "style='display: none;'";
						$mevcutadres = "style='mevcutadres: none;'";
					} else {
                        // E-posta oturumu tanımlı değilse, yani kullanıcı giriş yapmamışsa, alanları göster
						$display = "";
						$mevcutadres = "";
					}
					?>

					<div class="col-12 mb-40">
						<h4 class="checkout-title">Kayıtlı Adres</h4>
						<div class="checkout-payment-method">
							<div class="single-method">
								<input type="radio" checked id="" name="existing-address" value="check">
								<label for="existing-address">Adres</label>
								<p data-method="check" style="display: block;">
									<?php 
									if(!empty($adsoyad) && !empty($telefon) && !empty($adres) && !empty($ulke) && !empty($sehir)) {
										echo "$adsoyad<br>$telefon<br>$firma<br>$adres<br>$ulke<br>$sehir<br>$eyalet<br>$posta_kodu<br>";
									}
									else{
										$gizle = true;
									} 
									?>
									<a href='my-account.php' class='btn btn-dark btn-round d-inline-block'><i class='fa fa-edit'></i>
										<?php
										echo !empty($adsoyad) && !empty($telefon) && !empty($adres) && !empty($ulke) && !empty($sehir) ? 'Adres Düzenle' : 'Adres Ekle';
										?>
									</a>
								</p>
							</div>
						</div>
					</div>

					<?php 
					if (!$gizle) { 
						?>
						<div class="col-12 mb-4">
							<div class="checkout-payment-method" style="background-color: #ffffff; border: 1px solid #e5e5e5; border-radius: 10px; box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1); padding: 30px;">
								<h4 class="checkout-title" style="font-size: 1.75rem; font-weight: bold; color: #333; margin-bottom: 20px; border-bottom: 2px solid #ccc; padding-bottom: 10px;">Ödeme Bilgileri</h4>

								<div class="form-group">
									<label for="cardholder-name" style="display: block; font-size: 1rem; color: #555; margin-bottom: 10px;">Ad ve Soyad</label>
									<input type="text" id="cardholder-name" name="cardholder-name" class="form-control" placeholder="Ad ve Soyad" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem; transition: border-color 0.3s ease;">
								</div>

								<!-- Kart Numarası -->
								<div class="form-group">
									<label for="card-number" style="display: block; font-size: 1rem; color: #555; margin-bottom: 10px;">Kart Numarası</label>
									<div id="card-element" style="background-color: #f7f7f7; border: 1px solid #ddd; border-radius: 6px; padding: 20px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); color: #333; font-size: 1rem; font-family: 'Arial', sans-serif; width: 100%; transition: box-shadow 0.3s ease;">
										<!-- Stripe Ödeme Elemanı burada olacak -->
									</div>
								</div>

								<!-- Buton -->
								<div style="text-align: right; margin-top: 20px;">
									<button type="submit" class="btn btn-dark btn-round d-inline-block" id="payment-button">Ödeme Yap</button>
								</div>
							</div>
						</div>
						<?php 
					}
					?>

				</div>

				<div class="col-lg-5">
					<div class="row">

						<?php  
						include 'assets/fonk/mysql.php';
						$session_email = $_SESSION['EPOSTA'];
						$sql = "SELECT URUN_ADI, ADET, TOPLAM FROM sepet WHERE EPOSTA = :session_email";
						$stmt = $conn->prepare($sql);
						$stmt->bindParam(':session_email', $session_email);
						$stmt->execute();

						$grandTotal = 0;
        $shippingCost = 0; // Kargo ücreti

        if ($stmt->rowCount() > 0) {
        	?>
        	<div class="col-12 mb-40">
        		<h4 class="checkout-title">Sepet Toplamı</h4>
        		<div class="checkout-cart-total">
        			<h4>Ürün <span>Toplam</span></h4>
        			<ul>
        				<?php
        				while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        					$urun_adi = $row["URUN_ADI"];
        					$adet = $row["ADET"];
        					$toplam = $row["TOPLAM"];
        					$grandTotal += $toplam;
        					?>
        					<li><?php echo $urun_adi; ?> X <?php echo $adet; ?> <span><?php echo $toplam; ?> ₺</span></li>
        					<?php
        				}
        				?>
        			</ul>
        			<h4>Ara Toplam <span><?php echo $grandTotal; ?> ₺</span></h4>

        			<?php
                    // Kargo ücretini hesapla
        			if ($grandTotal < 100) {
        				$shippingCost = 10;
        			}

                    // Toplamı hesapla
        			$totalWithShipping = $grandTotal + $shippingCost;
        			?>
        			
        			<h4>Kargo Ücreti <span><?php echo $shippingCost; ?> ₺</span></h4>
        			<h4>Genel Toplam <span><?php echo $totalWithShipping; ?> ₺</span></h4>
        		</div>
        	</div>
        	<?php
            $_SESSION['TOTAL'] = $totalWithShipping; // Toplamı oturumda sakla
        } else {
        	echo "0 Sonuç";
        }
        $stmt = null;
        $conn = null;
        ?>

    </div>
</div>


</div>
</form>

</div>
</div><!-- Sayfa Bölümü Bitişi -->

<!-- Stripe JavaScript SDK -->
<script src="https://js.stripe.com/v3/"></script>
<script>
    // Stripe Ödeme Elemanı oluştur
	var stripe = Stripe('pk_test_51PccqAEsZtB4ERkGrLV2KFxAyj4DJgbpafvtaYnPz7KcYzE8diI7pO4SZ10mwxiiTQjqn863z9t981wu9nnm8YPh00OeEz3sxN');

	var elements = stripe.elements();
	var cardElement = elements.create('card');

    // Ödeme Elemanını sayfaya monte et
	cardElement.mount('#card-element');

	var form = document.getElementById('payment-form');

	form.addEventListener('submit', function(event) {
		event.preventDefault();

		var cardholderName = document.getElementById('cardholder-name').value;

		stripe.createPaymentMethod({
			type: 'card',
			card: cardElement,
			billing_details: {
				name: cardholderName
			}
		}).then(function(result) {
			if (result.error) {
				console.error(result.error.message);
			} else {
				var paymentMethodInput = document.createElement('input');
				paymentMethodInput.setAttribute('type', 'hidden');
				paymentMethodInput.setAttribute('name', 'payment_method_id');
				paymentMethodInput.setAttribute('value', result.paymentMethod.id);
				form.appendChild(paymentMethodInput);
				form.submit();
			}
		});
	});

    // Adres verisi yoksa ödeme butonunu gizle
	<?php if (!$adresVarMi): ?>
		document.getElementById('payment-button').style.display = 'none';
	<?php endif; ?>
</script>
