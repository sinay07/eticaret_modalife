$(document).ready(function() {
  // Giriş yapma kısmı
  $('#formAuthentication').on('submit', function(event) {
    event.preventDefault();
    var username = $('#email').val();
    var password = $('#password').val();

    $.ajax({
      url: 'assets/fonk/login.php',
      type: 'POST',
      dataType: 'json',
      data: {
        username: username,
        password: password
      },
      success: function(response) {
        if (response.success) {
          window.location.href = 'kontrol.php';
        } else {
          $('#loginError').show();
        }
      }
    });
  });
});