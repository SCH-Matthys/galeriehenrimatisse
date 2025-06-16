document.addEventListener("DOMContentLoaded", () => {
    const menuBurger = document.getElementById("menuBurger");
    const nav = document.querySelector("nav");

    menuBurger.addEventListener("click", () => {
        nav.classList.toggle("nav-hidden");
        nav.classList.toggle("nav-visible");
        menuBurger.classList.toggle("active");
        console.log("click");
    });

    
    function updateNavVisibility() {
        if (window.innerWidth < 1100) {
            nav.classList.add("nav-hidden");
        } else {
            nav.classList.remove("nav-hidden");
        }
    }

    updateNavVisibility();

    window.addEventListener("resize", updateNavVisibility);
});