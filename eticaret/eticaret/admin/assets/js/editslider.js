$(document).ready(function(){
  // Düzenle butonuna tıklama olayını dinle
  $('.dropdown-item[data-bs-target="#modalCenter"]').on('click', function(e) {
    e.preventDefault();

    // İlgili satırın verilerini al
    var sliderId = $(this).data('slider-id');
    var sliderYazi = $(this).data('slider-yazi');
    var butonYazi = $(this).data('buton-yazi');
    var butonUrl = $(this).data('buton-link');
    var sliderResim = $(this).data('slider-resim');

    // Modal içindeki input alanlarını güncelle
    $('#sliderId').val(sliderId);
    $('#sliderYazi').val(sliderYazi);
    $('#butonYazi').val(butonYazi);
    $('#butonUrl').val(butonUrl);
    $('#sliderResim').val(sliderResim);

    // Modalı aç
    $('#modalCenter').modal('show');
  });

  // Save changes butonuna tıklama olayını dinle
  $('#saveChangesBtn').on('click', function() {
    // Form verilerini ayrı ayrı al
    var sliderId = $('#sliderId').val();
    var sliderYazi = $('#sliderYazi').val();
    var butonYazi = $('#butonYazi').val();
    var butonUrl = $('#butonUrl').val();

    // FormData nesnesi oluştur
    var formData = new FormData();
    formData.append('slider_id', sliderId);
    formData.append('slider_yazi', sliderYazi);
    formData.append('buton_yazi', butonYazi);
    formData.append('buton_url', butonUrl);

    // Eğer kullanıcı resim seçtiyse
    var fileInput = $('#sliderResim')[0];
    if (fileInput.files.length > 0) {
      formData.append('slider_resmi', fileInput.files[0]);
    }

    // Ajax isteği oluştur
    $.ajax({
      type: 'POST',
      url: 'assets/fonk/updateslider.php',
      data: formData,
      contentType: false,
      processData: false,
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          $('#successToast .toast-body').text('Veriler başarıyla güncellendi.');
          $('#successToast').toast('show');
          setTimeout(function(){
            location.reload(); // 2 saniye sonra sayfayı yenile
          }, 2000);
        } else {
          $('#errorToast .toast-body').text('Veri güncelleme başarısız: ' + response.message);
          $('#errorToast').toast('show');
          setTimeout(function(){
            location.reload(); // 2 saniye sonra sayfayı yenile
          }, 2000);
        }
        // Modalı kapat
        $('#modalCenter').modal('hide');
      },
      error: function(xhr, status, error) {
        console.error(xhr.responseText);
        $('#errorToast .toast-body').text('Veri güncelleme başarısız: ' + error);
        $('#errorToast').toast('show');
        setTimeout(function(){
          location.reload(); // 2 saniye sonra sayfayı yenile
        }, 2000);
        // Modalı kapat
        $('#modalCenter').modal('hide');
      }
    });
  });
});
