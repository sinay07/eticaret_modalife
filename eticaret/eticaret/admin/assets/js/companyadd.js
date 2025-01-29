$(document).ready(function(){
  $(".product-image").hover(function(){
    $(this).css("width", "100px"); // Resmin boyutunu büyüt
  }, function(){
    $(this).css("width", "50px"); // Resmin boyutunu küçült (fare resmin üzerinden çekildiğinde)
  });

  $('#modalCenter').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Modal'ı tetikleyen butonu seç
    var urunTipi = button.data('uruntipi'); // Butonun data-uruntipi değerini al
    var urunAdi = button.data('urunadi'); // Butonun data-urunadi değerini al
    var urunKodu = button.data('urunkodu'); // Butonun data-urunkodu değerini al
    var mevcutFiyat = button.data('fiyat'); // Butonun data-fiyat değerini al
    var urunResim = button.closest('tr').find('.product-image').attr('src'); // Seçilen ürünün resim yolunu al
    // Sadece "product" klasörüne olan yolu almak için işlem yap
    urunResim = urunResim.split('product/')[1];
    var modal = $(this);
    modal.find('.modal-title').text(urunTipi === "sureli" ? "Süreli Kampanya" : "Süresiz Kampanya");
    modal.find('#urunAdi').val(urunAdi);
    modal.find('#urunKodu').val(urunKodu);
    modal.find('#mevcutFiyat').val(mevcutFiyat);
    modal.find('#urunResim').val(urunResim); // Resim alanının src özelliğini güncelle
    
    if (urunTipi === "sureli") {
      modal.find('#tarihFields').show();
    } else {
      modal.find('#tarihFields').hide();
    }
  });

  // Save changes butonuna tıklama olayını ekle
  $('#saveChangesBtn').on('click', function () {
    // Gerekli değerleri al
    var urunKodu = $('#urunKodu').val();
    var urunAdi = $('#urunAdi').val();
    var urunResim = $('#urunResim').val();
    var mevcutFiyat = $('#mevcutFiyat').val();
    var indirimliFiyat = $('#indirimliFiyat').val();
    var baslangicTarihi = $('#baslangicTarihi').val();
    var bitisTarihi = $('#bitisTarihi').val();

    // Ajax isteği oluştur
    $.ajax({
      type: "POST",
      url: "assets/fonk/companyadd.php", // Verileri işleyecek PHP dosyasının yolu
      data: {
        urunKodu: urunKodu,
        urunAdi: urunAdi,
        urunResim: urunResim,
        mevcutFiyat: mevcutFiyat,
        indirimliFiyat: indirimliFiyat,
        baslangicTarihi: baslangicTarihi,
        bitisTarihi: bitisTarihi
      },
      dataType: 'json', // Veri tipini JSON olarak belirt
      success: function(response) {
        if (response.success) {
          $('#successToast .toast-body').text('Kampanya başarıyla oluşturuldu.');
          $('#successToast').toast('show');
          setTimeout(function(){
            location.reload(); // 2 saniye sonra sayfayı yenile
          }, 2000);
        } else {
          $('#errorToast .toast-body').text('Ürün ekleme başarısız: ' + response.message);
          $('#errorToast').toast('show');
          setTimeout(function(){
            location.reload(); // 2 saniye sonra sayfayı yenile
          }, 2000);
        }
        // Modalı kapat
        $('#modalCenter').modal('hide');
      },
      error: function(xhr, status, error) {
        // Hata oluşursa burada işleme al
        $('#errorToast .toast-body').text('Ürün ekleme başarısız: ' + response.message);
        $('#errorToast').toast('show');
        setTimeout(function(){
          location.reload(); // 2 saniye sonra sayfayı yenile
        }, 2000);
      }
    });
  });
});
