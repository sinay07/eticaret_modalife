  $(document).ready(function() {
    $('.sil-buton').click(function() {
      var kayitId = $(this).data('kayit-id');

      $.post('assets/fonk/sil_resim.php', { kayit_id: kayitId}, function(response) {
        // Silme işlemi başarılı olduğunda sayfayı yeniden yükle
        location.reload();
      });
    });
  });