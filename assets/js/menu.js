if ($("#sidebar").length > 0) {
    route = window.location.pathname === "" ? "/" : window.location.pathname;
    
    $(".nav-link").addClass("collapsed");
    $(`#sidebar [href="${route}"]`).removeClass("collapsed");

}

$("#sidebar .nav-item a").click(() => StartLoading())

window.onload = () => EndLoading();

window.addEventListener('beforeunload', () => StartLoading());