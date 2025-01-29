<?php  
// Sepet verisini kontrol et
$sepetVarMi = isset($_SESSION['sepet']) && !empty($_SESSION['sepet']);

$grandTotal = 0;
$shippingCost = 0; // Kargo ücreti

if ($sepetVarMi) {
    // Sepet verilerini işle
	foreach ($_SESSION['sepet'] as $urun) {
		$grandTotal += $urun['TOPLAM'];
	}

    // Kargo ücretini hesapla
	if ($grandTotal < 100) {
		$shippingCost = 10;
	}

    // Toplamı hesapla
	$totalWithShipping = $grandTotal + $shippingCost;
}
?>

<!-- Seitenabschnitt Start -->
<div class="page-section section section-padding">
	<div class="container">

		<!-- Checkout Form -->
		<form id="payment-form" action="checkout-processno.php" method="post" class="checkout-form">
			<div class="row row-50 mbn-40">

				<div class="col-lg-7">

					<!-- Rechnungsadresse -->
					<div class="col-12 mb-40">
						<h4 class="checkout-title" style="font-size: 1.75rem; font-weight: bold; color: #333; margin-bottom: 20px;">Registrierte Adresse</h4>
						<div class="checkout-payment-method" style="background-color: #ffffff; border: 1px solid #e5e5e5; border-radius: 10px; padding: 20px; box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);">
							<div class="form-group">
								<label for="first-name" style="font-size: 1rem; color: #555;">Vorname</label>
								<input type="text" id="first-name" name="first-name" class="form-control" placeholder="Vorname" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem;">
							</div>

							<div class="form-group">
								<label for="last-name" style="font-size: 1rem; color: #555;">Nachname</label>
								<input type="text" id="last-name" name="last-name" class="form-control" placeholder="Nachname" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem;">
							</div>

							<div class="form-group">
								<label for="email" style="font-size: 1rem; color: #555;">Email</label>
								<input type="email" id="email" name="email" class="form-control" placeholder="Email" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem;">
							</div>

							<div class="form-group">
								<label for="phone" style="font-size: 1rem; color: #555;">Telefon</label>
								<input type="tel" id="phone" name="phone" class="form-control" placeholder="Telefon" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem;">
							</div>

							<div class="form-group">
								<label for="company" style="font-size: 1rem; color: #555;">Firma</label>
								<input type="text" id="company" name="company" class="form-control" placeholder="Firma" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem;">
							</div>

							<div class="form-group">
								<label for="address" style="font-size: 1rem; color: #555;">Adresse</label>
								<input type="text" id="address" name="address" class="form-control" placeholder="Adresse" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem;">
							</div>

							<div class="form-group">
								<label for="country" style="font-size: 1rem; color: #555;">Land</label>
								<input type="text" id="country" name="country" class="form-control" placeholder="Land" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem;">
							</div>

							<div class="form-group">
								<label for="city" style="font-size: 1rem; color: #555;">Stadt</label>
								<input type="text" id="city" name="city" class="form-control" placeholder="Stadt" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem;">
							</div>

							<div class="form-group">
								<label for="state" style="font-size: 1rem; color: #555;">Bundesland</label>
								<input type="text" id="state" name="state" class="form-control" placeholder="Bundesland" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem;">
							</div>

							<div class="form-group">
								<label for="postal-code" style="font-size: 1rem; color: #555;">Postleitzahl</label>
								<input type="text" id="zip" name="zip" class="form-control" placeholder="Postleitzahl" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem;">
							</div>
						</div>
					</div>


					<div class="col-12 mb-4">
						<div class="checkout-payment-method" style="background-color: #ffffff; border: 1px solid #e5e5e5; border-radius: 10px; box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1); padding: 30px;">
							<h4 class="checkout-title" style="font-size: 1.75rem; font-weight: bold; color: #333; margin-bottom: 20px; border-bottom: 2px solid #ccc; padding-bottom: 10px;">Zahlungsinformationen</h4>

							<div class="form-group">
								<label for="cardholder-name" style="display: block; font-size: 1rem; color: #555; margin-bottom: 10px;">Vor- und Nachname</label>
								<input type="text" id="cardholder-name" name="cardholder-name" class="form-control" placeholder="Vor- und Nachname" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem; transition: border-color 0.3s ease;">
							</div>

							<!-- Kartennummer -->
							<div class="form-group">
								<label for="card-number" style="display: block; font-size: 1rem; color: #555; margin-bottom: 10px;">Kartennummer</label>
								<div id="card-element" style="background-color: #f7f7f7; border: 1px solid #ddd; border-radius: 6px; padding: 20px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); color: #333; font-size: 1rem; font-family: 'Arial', sans-serif; width: 100%; transition: box-shadow 0.3s ease;">
									<!-- Stripe Zahlungselement wird hier eingefügt -->
								</div>
							</div>

							<!-- Buton -->
							<div style="text-align: right; margin-top: 20px;">
							    <button type="submit" class="btn btn-dark btn-round d-inline-block" id="payment-button">Ödeme Yap</button>
							</div>

						</div>
					</div>

				</div>

				<div class="col-lg-5">
					<div class="row">
						<?php if ($sepetVarMi): ?>
							<div class="col-12 mb-40">
								<h4 class="checkout-title">Sepet Toplamı</h4>
								<div class="checkout-cart-total">
									<h4>Ürünler <span>Toplam</span></h4>
									<ul>
										<?php
                        // Sepet verilerini tekrar işleme
										foreach ($_SESSION['sepet'] as $urun) {
											$urun_adi = htmlspecialchars($urun["URUN_ADI"]);
											$adet = (int)$urun["ADET"];
											$toplam = number_format($urun["TOPLAM"], 2, '.', ',');
											?>
											<li><?php echo $urun_adi; ?> X <?php echo $adet; ?> <span><?php echo $toplam; ?>-CHF</span></li>
											<?php
										}
										?>
									</ul>
									<h4>Ara Toplam <span><?php echo number_format($grandTotal, 2, '.', ','); ?>-CHF</span></h4>
									<h4>Kargo Ücreti <span><?php echo number_format($shippingCost, 2, '.', ','); ?>-CHF</span></h4>
									<h4>Toplam <span><?php echo number_format($totalWithShipping, 2, '.', ','); ?>-CHF</span></h4>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div>



			</div>
		</form>

	</div>
</div><!-- Seitenabschnitt Ende -->

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
</script>
