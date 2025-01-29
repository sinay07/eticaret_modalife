$(document).ready(function(){
  $(".product-image").hover(function(){
    $(this).css("width", "100px"); // Resmin boyutunu büyüt
  }, function(){
    $(this).css("width", "50px"); // Resmin boyutunu küçült (fare resmin üzerinden çekildiğinde)
  });

  // Tooltipleri etkinleştir
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  });

  // Formu jQuery ile işle
  $('#sliderForm').on('submit', function(e) {
    e.preventDefault();

    var formData = new FormData(this);
    $.ajax({
      url: 'assets/fonk/addslider.php',
      type: 'POST',
      data: formData,
      contentType: false,
      processData: false,
      success: function(response) {
        var result = JSON.parse(response);
        if (result.status === 'success') {
          $('#successToast .toast-body').text(result.message);
          $('#successToast').toast('show');
          setTimeout(function(){
            location.reload();
          }, 2000);
        } else {
          $('#errorToast .toast-body').text('Hata: ' + result.message);
          $('#errorToast').toast('show');
        }
      },
      error: function() {
        $('#errorToast .toast-body').text('Bir hata oluştu.');
        $('#errorToast').toast('show');
      }
    });
  });
});
