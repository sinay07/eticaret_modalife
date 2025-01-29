$(document).ready(function() {
    // Ürün ekle kısmı
    $('#addProductButton').click(function(event) {
        event.preventDefault();

        var formData = new FormData();

        // Form elemanlarını FormData'ya ekle
        formData.append('urunKodu', $('#urunKodu').val());
        formData.append('urunAdi', $('#urunAdi').val());
        formData.append('urunAciklama', $('#urunAciklama').val());
        formData.append('urunFiyat', $('#urunFiyat').val());
        formData.append('urunStok', $('#urunStok').val());
        formData.append('urunEtiket', $('#urunEtiket').val());
        formData.append('cinsiyetSec', $('#cinsiyetSec').val());
        formData.append('kumasCinsi', $('#kumasCinsi').val());
        formData.append('anaKategori', $('#anaKategori').val());
        formData.append('altKategori', $('#altKategori').val());
        
        // 'beden' elemanını ekle
        var selectedBeden = $('#beden').val();
        if (selectedBeden) {
            selectedBeden.forEach(function(value) {
                formData.append('beden[]', value);
            });
        }

        // Dosya yüklemesi için kapak resmi ve diğer resimler öğelerini FormData'ya ekle
        formData.append('kapakResim', $('#kapakResim')[0].files[0]);
        var digerResimler = $('#digerResimler')[0].files;
        for (var i = 0; i < digerResimler.length; i++) {
            formData.append('digerResimler[]', digerResimler[i]);
        }

        // Form verilerini konsola yazdır
        console.log([...formData.entries()]);

        $.ajax({
            url: 'assets/fonk/addproduct.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                var data = JSON.parse(response);
                if (data.success) {
                    $('#successToast .toast-body').text('Ürün başarıyla eklendi.');
                    $('#successToast').toast('show');
                } else {
                    $('#errorToast .toast-body').text('Ürün ekleme başarısız: ' + data.message);
                    $('#errorToast').toast('show');
                }
            },
            error: function(xhr, status, error) {
                var errorMessage = xhr.status + ': ' + xhr.statusText;
                $('#errorToast .toast-body').text('Hata: ' + errorMessage);
                $('#errorToast').toast('show');
            }
        });
    });
});
