$(document).ready(function() {
  $('#urun-guncelle-btn').click(function(event) {
    // Form submit işlemini engelle
    event.preventDefault();

    // FormData nesnesi oluştur ve form verilerini topla
    var form_data = new FormData($('#urun-guncelle-form')[0]);

    // Diğer resimleri de ekle
    var urun_diger_resimler = $('#urun-diger-resimler')[0].files;
    for (var i = 0; i < urun_diger_resimler.length; i++) {
      form_data.append('urun_diger_resimler[]', urun_diger_resimler[i]);
    }

    // Ajax isteği gönder
    $.ajax({
      type: 'POST',
      url: 'assets/fonk/urun_guncelle.php', // Güncelleme işlemini yapacak PHP dosyası
      data: form_data,
      contentType: false,
      processData: false,
      success: function(response) {
        response = JSON.parse(response);
        if (response.status === 'success') {
          $('#successToast .toast-body').text(response.message);
          $('#successToast').toast('show');

          // 3 saniye sonra sayfayı yeniden yükle
          setTimeout(function() {
            location.reload();
          }, 2000);
        } else {
          $('#errorToast .toast-body').text(response.message);
          $('#errorToast').toast('show');
        }
      },
      error: function(xhr, status, error) {
        var errorMessage = xhr.status + ': ' + xhr.statusText;
        $('#errorToast .toast-body').text('Hata: ' + errorMessage);
        $('#errorToast').toast('show');
      }
    });
  });
});
