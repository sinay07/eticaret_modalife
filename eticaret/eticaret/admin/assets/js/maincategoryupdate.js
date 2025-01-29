$(document).ready(function(){
    // Ana kategori güncelleme işlemi
  $("#updateMainCategoryButton").click(function(){
    var mainCategory = $("#mainCategorySelect").val();
    var newMainCategoryName = $("#mainCategoryNewName").val();

    $.post("assets/fonk/update_main_category.php", { 
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
  });
});
