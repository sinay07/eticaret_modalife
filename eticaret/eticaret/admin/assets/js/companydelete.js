$(document).ready(function(){
    // Kaldır butonuna tıklandığında
    $('.remove-btn').on('click', function(){
        // İlgili satırdaki URUN_KODU değerini al
        var urunKodu = $(this).closest('tr').find('td:eq(0)').text().trim();

        // AJAX isteği göndererek ürünü sil
        $.ajax({
            type: 'POST',
            url: 'assets/fonk/companydelete.php', // Silme işleminin gerçekleştirileceği PHP dosyasının yolunu buraya yaz
            data: { urunKodu: urunKodu }, // Silinecek ürünün URUN_KODU değerini POST isteği ile gönder
            success: function(response){
                if (response.success) {
                    $('#successToast .toast-body').text(response.message);
                    $('#successToast').toast('show');
                    setTimeout(function(){
                        location.reload(); // 2 saniye sonra sayfayı yenile
                    }, 2000);
                } else {
                    $('#errorToast .toast-body').text(response.message);
                    $('#errorToast').toast('show');
                    setTimeout(function(){
                        location.reload(); // 2 saniye sonra sayfayı yenile
                    }, 2000);
                }
            },
            error: function(xhr, status, error){
                $('#errorToast .toast-body').text('Ürün silme başarısız: ' + error);
                $('#errorToast').toast('show');
                setTimeout(function(){
                    location.reload(); // 2 saniye sonra sayfayı yenile
                }, 2000);
            }
        });
    });
});
