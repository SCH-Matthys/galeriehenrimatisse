document.addEventListener("DOMContentLoaded", () => {
    const menuBurger = document.getElementById("menuBurger");
    const nav = document.querySelector("nav");

    menuBurger.addEventListener("click", () => {
        nav.classList.toggle("nav-hidden");
        nav.classList.toggle("nav-visible");
        menuBurger.classList.toggle("active");
        console.log("click");
    });
});