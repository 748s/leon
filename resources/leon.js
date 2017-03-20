
$(document).ready(function() {
    $("body").on("submit", ".ajaxForm", function(e) {
        e.preventDefault();
        var form = $(this);
        $.ajax({
            type: "POST",
            url: $(form).attr('action'),
            data: $(form).serialize(),
            success: ajaxSuccess,
            dataType: 'json'
        });
    });

    function ajaxSuccess(response) {
        if(response.location) {
            window.location.href = response.location;
            if(response.location.indexOf("#") != -1) {
                window.location.reload(true);
            }
        } else if (response.replaceById) {
            $.each(response.replaceById, function(id, value) {
                $("#" + id).replaceWith(value);
            });
        }
    }

    $("body").on("click", ".switchModal", function(e) {
        e.preventDefault();
        switchModal($(this));
    });

    function switchModal(obj) {
        $(obj).closest(".modal").modal("hide");
        $($(obj).attr("data-target")).modal("show");
    }

});
