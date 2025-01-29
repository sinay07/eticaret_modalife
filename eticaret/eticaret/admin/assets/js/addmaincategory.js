$(document).ready(function() {
  // Ana kategori ekle kısmı
  $('#addMainCategoryButton').click(function() {
    var anaKategoriAdi = $('#mainCategoryName').val();

    if (anaKategoriAdi !== '') {
      $.ajax({
        url: 'assets/fonk/addmaincategory.php',
        method: 'POST',
        data: {
          ana_kategori: anaKategoriAdi
        },
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            location.reload();
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
      $('#errorToast').toast('show');
    }
  });
});