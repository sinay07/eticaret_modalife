$(document).ready(function(){
  $(".dropdown-item.iptal-et").click(function(){
        var urunKodu = $(this).data("urunkodu"); // data-urunkodu özniteliğinden ürün numarasını al

        // AJAX isteği gönder
        $.ajax({
          url: 'assets/fonk/productdelete.php',
          type: 'POST',
          dataType: 'json',
          data: {urunKodu: urunKodu},
          success: function(response){
                // İşlem başarılı ise
            if(response.success){
              location.reload();
            }
          }
        });
      });
});