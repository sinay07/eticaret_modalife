$(document).ready(function() {
  $('form').submit(function(event) {
    event.preventDefault(); // Formun normal submit işlemini engelle

    // Form verilerini al
    var formData = $(this).serialize();

    // POST isteği yap
    $.post('assets/fonk/contactupdate.php', formData, function(response) {
      // Sunucudan gelen cevabı işle
      if (response.success) {
        $('#successToast .toast-body').text('Die Informationen wurden erfolgreich aktualisiert.');
        $('#successToast').toast('show');
        setTimeout(function(){
          location.reload(); // 2 saniye sonra sayfayı yenile
        }, 2000);
      } else {
        $('#errorToast .toast-body').text('Aktualisierung fehlgeschlagen: ' + response.message);
        $('#errorToast').toast('show');
      }
    }, 'json'); // JSON formatında yanıt beklediğimizi belirtiyoruz
  });
});
