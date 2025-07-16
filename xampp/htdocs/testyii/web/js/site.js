$(document).ready(function() {
    $("#toggleSidebar").click(function() {
        $(".sidebar").toggleClass("hidden active");
        $("#toggleSidebar").toggleClass("hidden");
        $(".content").toggleClass("expanded");
    });
    $(".dropdown-toggle").click(function(e) {
        e.preventDefault();
        $(this).next(".dropdown-menu").slideToggle(200);
    });
    setTimeout(function() {
        $(".alert").fadeOut(500, function() {
            $(this).remove();
        });
    }, 5000);
    $(".alert .close").click(function() {
        $(this).parent().fadeOut(300, function() {
            $(this).remove();
        });
    });
});