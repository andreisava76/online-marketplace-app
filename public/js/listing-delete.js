$(document).ready(() => {
    $("#staticBackdrop").on("show.bs.modal", function (event) {
        const button = $(event.relatedTarget);
        $("#delete_form").attr(
            "action",
            "listings/" + button.data("listing_slug") + "/delete"
        );
    });
});
