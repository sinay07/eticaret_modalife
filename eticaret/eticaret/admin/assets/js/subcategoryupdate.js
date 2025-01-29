$(document).ready(function(){
    // Alt kategori güncelleme işlemi
    $("#updateSubCategoryButton").click(function(){
        var subCategory = $("#subCategorySelect").val();
        var newSubCategoryName = $("#subCategoryNewName").val();

        $.post("assets/fonk/update_sub_category.php", { 
            subCategory: subCategory, 
            newSubCategoryName: newSubCategoryName 
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
