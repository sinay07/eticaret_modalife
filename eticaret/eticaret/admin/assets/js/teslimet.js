$(document).ready(function(){
    $('#teslimButon').click(function(e){
        e.preventDefault(); // Bağlantının varsayılan davranışını engelle
            
        var urunKodu = $(this).data('urun-kodu'); // data-urun-kodu değerini al
            
        // POST isteği yap
        $.post('assets/fonk/teslimet.php', { urunKodu: urunKodu }, function(response){
            if (response.status === 'success') {
                $('#successToast .toast-body').text(response.message);
                $('#successToast').toast('show');
                setTimeout(function(){
                    location.reload(); // 2 saniye sonra sayfayı yenile
                }, 2000);
            } else {
                $('#errorToast .toast-body').text('Hata: ' + response.message);
                $('#errorToast').toast('show');
                setTimeout(function(){
                    location.reload(); // 2 saniye sonra sayfayı yenile
                }, 2000);
            }
        }, 'json');
    });
});
