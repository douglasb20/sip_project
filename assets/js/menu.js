if ($("#sidebar").length > 0) {
    route = window.location.pathname === "" ? "/" : window.location.pathname;
    
    $(".nav-link").addClass("collapsed");
    let menu_route = $(`#sidebar [href="${route}"]`);

    if (menu_route.parents("ul").hasClass("nav-content")) {
        menu_route.parents("ul").addClass("show");
        menu_route.addClass("active");
        menu_route.parents(".nav-item").find(".nav-link").removeClass("collapsed")
    } else {
        menu_route.removeClass("collapsed");
    }

}

$("#sidebar .nav-item a").not('[data-bs-toggle="collapse"]').click(() => StartLoading())

window.onload = () => EndLoading();

window.addEventListener('beforeunload', () => StartLoading());