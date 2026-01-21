document.addEventListener("DOMContentLoaded", () => {

    const niveles = document.querySelectorAll(".nivel");
    const puntosCont = document.getElementById("puntos");
    const carrusel = document.querySelector(".lista-niveles");

    let actual = 0;
    let inicioX = 0;

    // ----- PUNTOS -----
    niveles.forEach((_, i) => {
        const p = document.createElement("span");
        p.className = "punto" + (i === 0 ? " active" : "");
        p.onclick = () => cambiar(i);
        puntosCont.appendChild(p);
    });

    const puntos = document.querySelectorAll(".punto");

    // ----- CAMBIAR NIVEL -----
    const cambiar = (i) => {
        if (i === actual) return;
        niveles[actual].classList.remove("activo");
        puntos[actual].classList.remove("active");
        actual = i;
        niveles[actual].classList.add("activo");
        puntos[actual].classList.add("active");
    };

    // ----- DRAG / SWIPE -----
    const start = e => inicioX = e.touches ? e.touches[0].clientX : e.clientX;
    const end = e => {
        const finX = e.changedTouches ? e.changedTouches[0].clientX : e.clientX;
        const dif = finX - inicioX;
        if (dif > 50 && actual > 0) cambiar(actual - 1);
        if (dif < -50 && actual < niveles.length - 1) cambiar(actual + 1);
    };

    carrusel.addEventListener("mousedown", start);
    carrusel.addEventListener("mouseup", end);
    carrusel.addEventListener("touchstart", start);
    carrusel.addEventListener("touchend", end);

    // ----- FLECHAS DEL TECLADO -----
    document.addEventListener("keydown", (e) => {
        if (e.key === "ArrowLeft" && actual > 0) {
            cambiar(actual - 1);
        }
        if (e.key === "ArrowRight" && actual < niveles.length - 1) {
            cambiar(actual + 1);
        }
    });

});
