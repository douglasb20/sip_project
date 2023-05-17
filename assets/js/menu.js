if ($("#sidebar").length > 0) {
    route = window.location.pathname === "" ? "/" : window.location.pathname;
    
    $(".nav-link").addClass("collapsed");
    let menu_route = $(`#sidebar [href="${route}"]`);
    // if(menu_route)
    menu_route.removeClass("collapsed");

}

$("#sidebar .nav-item a").not('[data-bs-toggle="collapse"]').click(() => StartLoading())

window.onload = () => EndLoading();

window.addEventListener('beforeunload', () => StartLoading());