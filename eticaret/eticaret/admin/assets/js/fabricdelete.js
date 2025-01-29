$(document).ready(function() {
    $("#deleteMainCategoryButton").click(function() {
        var mainCategory = $("#mainCategorySelect").val();

        $.post("assets/fonk/fabricdelete.php", { 
            mainCategory: mainCategory 
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
