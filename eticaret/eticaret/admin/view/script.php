  <!-- build:js assets/vendor/js/core.js -->
  <script src="assets/vendor/libs/jquery/jquery.js"></script>
  <script src="assets/vendor/libs/popper/popper.js"></script>
  <script src="assets/vendor/js/bootstrap.js"></script>
  <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="assets/vendor/js/menu.js"></script>
  <script src="assets/js/main.js"></script>
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <script>
    document.getElementById('updateButton').addEventListener('click', function() {
      var primaryToast = new bootstrap.Toast(document.getElementById('primaryToast'));
      primaryToast.show();
  });
</script>

<script>
    $(document).ready(function(){
      $(".product-image").hover(function(){
        $(this).css("width", "100px"); // Resmin boyutunu büyüt
    }, function(){
        $(this).css("width", "50px"); // Resmin boyutunu küçült (fare resmin üzerinden çekildiğinde)
    });
  });
</script>