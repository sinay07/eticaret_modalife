$(document).ready(function(){
    // Ana kategori silme işlemi
    $("#deleteMainCategoryButton").click(function(){
        var mainCategory = $("#mainCategorySelect").val();
        
        $.post("assets/fonk/delete_main_category.php", { mainCategory: mainCategory }, function(data, status){
            if(status == "success") {
                $('#successToast').toast('show');
                setTimeout(function(){
                    location.reload(); // 2 saniye sonra sayfa yenilensin
                }, 2000); // 2 saniye beklet
            } else {
                $('#errorToast').toast('show');
            }
        });
    });
});
