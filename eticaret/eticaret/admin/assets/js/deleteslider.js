$(document).ready(function(){
  // Slider silme işlemini yap
  $('.delete-slider').on('click', function(e) {
    e.preventDefault();
    var sliderId = $(this).data('slider-id');
    deleteSlider(sliderId);
  });
});

function deleteSlider(sliderId) {
  $.ajax({
    url: 'assets/fonk/deleteslider.php',
    type: 'POST',
    data: { id: sliderId },
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
}
