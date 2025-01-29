$(document).ready(function() {
  $('#updateButton').click(function() {
    var username = $('#username').val();
    var oldPassword = $('#oldPassword').val();
    var newPassword = $('#newPassword').val();
    var confirmPassword = $('#confirmPassword').val();

    if (newPassword !== confirmPassword) {
      $('#errorToast .toast-body').text('Yeni şifreler eşleşmiyor.');
      $('#errorToast').toast('show');
      return;
    }

    $.ajax({
      url: 'assets/fonk/updateaccount.php',
      method: 'POST',
      data: {
        username: username,
        oldPassword: oldPassword,
        newPassword: newPassword
      },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          $('#successToast').toast('show');
        } else {
          $('#errorToast .toast-body').text(response.message);
          $('#errorToast').toast('show');
        }
      },
      error: function(xhr, status, error) {
        var errorMessage = xhr.status + ': ' + xhr.statusText;
        $('#errorToast .toast-body').text(errorMessage);
        $('#errorToast').toast('show');
      }
    });
  });
});
