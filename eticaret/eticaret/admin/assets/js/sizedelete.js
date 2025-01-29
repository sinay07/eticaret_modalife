$(document).ready(function(){
    // Beden silme işlemi
    $("#deleteMainCategoryButton").click(function(){
        var mainCategory = $("#mainCategorySelect").val();

        if(mainCategory) {
            $.post("assets/fonk/sizedelete.php", { 
                mainCategory: mainCategory 
            }, 
            function(data, status){
                if(status == "success") {
                    $('#successToast').toast('show');
                    setTimeout(function(){
                        location.reload(); // 2 saniye sonra sayfa yenilensin
                    }, 2000); // 2 saniye beklet
                } else {
                    $('#errorToast').toast('show');
                }
            });
        } else {
            $('#errorToast .toast-body').text('Bitte eine Größe auswählen.');
            $('#errorToast').toast('show');
        }
    });
});
