$(document).ready(function() {
  // Ana kategori seçildiğinde alt kategorileri getir
  $('#anaKategori').change(function() {
    var anaKategori = $(this).val();
    var subCategorySelect = $('#altKategori');

    // Önce mevcut alt kategorileri temizle
    subCategorySelect.empty().append('<option selected disabled>Alt Kategori Seç</option>');

    // AJAX ile alt kategorileri getir
    $.ajax({
      type: 'GET',
      url: 'assets/fonk/get_subcategories.php',
      data: { anaKategori: anaKategori },
      dataType: 'json',
      success: function(data) {
        // Her bir alt kategori için yeni bir seçenek oluştur
        $.each(data, function(index, subCategory) {
          subCategorySelect.append($('<option></option>').text(subCategory).val(subCategory));
        });
      },
      error: function(xhr, status, error) {
        console.error('Alt kategorileri alma işlemi başarısız oldu.');
      }
    });
  });
});
