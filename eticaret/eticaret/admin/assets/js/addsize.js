$(document).ready(function() {
  $('#addSizeButton').click(function() {
    var bedenAdi = $('#bedenAdi').val();

    if (bedenAdi !== '') {
      $.ajax({
        url: 'assets/fonk/addsize.php',
        method: 'POST',
        data: {
          beden_adi: bedenAdi
        },
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            $('#successToast').toast('show');
            setTimeout(function() {
              location.reload();
            }, 2000);
          } else {
            $('#errorToast').toast('show');
          }
        },
        error: function(xhr, status, error) {
          var errorMessage = xhr.status + ': ' + xhr.statusText;
          $('#errorToast .toast-body').text(errorMessage);
          $('#errorToast').toast('show');
        }
      });
    } else {
      $('#errorToast .toast-body').text('Größenname darf nicht leer sein.');
      $('#errorToast').toast('show');
    }
  });
});
