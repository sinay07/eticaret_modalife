$(document).ready(function(){
    // Beden güncelleme işlemi
    $("#updateMainCategoryButton").click(function(){
        var mainCategory = $("#mainCategorySelect").val();
        var newMainCategoryName = $("#mainCategoryNewName").val();

        if(mainCategory && newMainCategoryName) {
            $.post("assets/fonk/sizeupdate.php", { 
                mainCategory: mainCategory, 
                newMainCategoryName: newMainCategoryName 
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
            $('#errorToast .toast-body').text('Bitte alle Felder ausfüllen.');
            $('#errorToast').toast('show');
        }
    });
});
