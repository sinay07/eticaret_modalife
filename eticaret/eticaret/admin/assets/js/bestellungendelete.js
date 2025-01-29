$(document).ready(function(){
  $(".dropdown-item.iptal-et").click(function(){
        var siparisNo = $(this).data("siparisno"); // data-siparisno özniteliğinden sipariş numarasını al

        // AJAX isteği gönder
        $.ajax({
          url: 'assets/fonk/bestellungendelete.php',
          type: 'POST',
          dataType: 'json',
          data: {siparisNo: siparisNo},
          success: function(response){
                // İşlem başarılı ise
            if(response.success){
              location.reload();
            }
          }
        });
      });
});