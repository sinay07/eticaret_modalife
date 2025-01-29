$(document).ready(function() {
    $("#updateMainCategoryButton").click(function() {
        var mainCategory = $("#mainCategorySelect").val();
        var newMainCategoryName = $("#mainCategoryNewName").val();

        $.post("assets/fonk/fabricupdate.php", { 
            mainCategory: mainCategory, 
            newMainCategoryName: newMainCategoryName 
        }, function(data, status) {
            if(status == "success") {
                $('#successToast').toast('show');
                setTimeout(function() {
                    location.reload();
                }, 2000);
            } else {
                $('#errorToast').toast('show');
            }
        });
    });
});
