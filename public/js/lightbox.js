document.addEventListener("DOMContentLoaded", () =>{
    const images = document.querySelectorAll(".artwork-image");
    const lightbox = document.getElementById("lightbox");  
    const lightboxImg = document.getElementById("lightbox-image");
    const btnClose = document.querySelector(".lightbox .btn-close");
    const btnPrev = document.getElementById("btn-prev");
    const btnNext = document.getElementById("btn-next");

    let currentIndex = 0;

    console.log(images);

    const showImage = (index) =>{
        const image = images[index];
        if (!image) return;
        lightboxImg.src = image.dataset.src;
        currentIndex = index;
        lightbox.classList.remove("hidden");
    };

    images.forEach((img, index) =>{
        img.addEventListener("click", () =>{
            showImage(index);
        });
    });

    btnClose.addEventListener("click", () =>{
        lightbox.classList.add("hidden");
    });

    lightbox.addEventListener("click", (e) =>{
        if(e.target === lightbox){
            lightbox.classList.add("hidden");
        };
    });

    btnPrev.addEventListener("click", () =>{
        const newIndex = (currentIndex - 1 + images.length) % images.length;
        showImage(newIndex);
    });

    btnNext.addEventListener("click", () =>{
        const newIndex = (currentIndex + 1) % images.length;
        showImage(newIndex);
    });

    document.addEventListener("keydown", (e) =>{
        if(lightbox.classList.contains("hidden")) return;
        if(e.key === "Escape"){
            lightbox.classList.add("hidden");
        };
        if(e.key === "ArrowLeft"){
            btnPrev.click();
        };
        if(e.key === "ArrowRight"){
            btnNext.click();
        };
    });

    console.log("lightbox : OK")

    // document.addEventListener("click", ()=>{
    //     console.log("click");
    // });
});