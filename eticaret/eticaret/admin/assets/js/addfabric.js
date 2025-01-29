$(document).ready(function() {
  $('#addKumasButton').click(function() {
    var kumasAdi = $('#kumasAdi').val();

    if (kumasAdi !== '') {
      $.ajax({
        url: 'assets/fonk/addfabric.php',
        method: 'POST',
        data: {
          kumas_adi: kumasAdi
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
      $('#errorToast .toast-body').text('Stoffname darf nicht leer sein.');
      $('#errorToast').toast('show');
    }
  });
});
