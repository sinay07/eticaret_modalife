$(document).ready(function(){
    // Alt kategori silme işlemi
    $("#deleteSubCategoryButton").click(function(){
        var subCategory = $("#subCategorySelect").val();

        $.post("assets/fonk/delete_sub_category.php", { 
            subCategory: subCategory 
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
    });
});
