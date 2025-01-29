$(document).ready(function() {
  // Alt kategori ekle kısmı
  $('#addSubCategoryButton').click(function() {
    var selectedAnaKategori = $('#mainCategorySelect').val();
    var altKategoriAdi = $('#subCategoryName').val();

    if (selectedAnaKategori !== '' && altKategoriAdi !== '') {
      $.ajax({
        url: 'assets/fonk/addsubcategory.php',
        method: 'POST',
        data: {
          ana_kategori: selectedAnaKategori,
          alt_kategori: altKategoriAdi
        },
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            $('#successToast').toast('show');
          } else {
            $('#errorToast').toast('show');
          }
        },
        error: function(xhr, status, error) {
          $('#errorToast').toast('show');
        }
      });
    } else {
      $('#errorToast').toast('show');
    }
  });
});