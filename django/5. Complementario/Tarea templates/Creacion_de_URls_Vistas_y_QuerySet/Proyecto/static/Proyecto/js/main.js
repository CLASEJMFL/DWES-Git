document.addEventListener("DOMContentLoaded", function () {
    // 1) Marca como activo el enlace del menú que coincide con la URL actual
    var ruta = window.location.pathname;
    document.querySelectorAll(".navbar a.enlace").forEach(function (enlace) {
        if (enlace.getAttribute("href") === ruta) {
            enlace.classList.add("activo");
        }
    });

    // 2) Botón "volver arriba" que aparece al hacer scroll
    var boton = document.getElementById("subir");
    if (boton) {
        window.addEventListener("scroll", function () {
            boton.style.display = window.scrollY > 200 ? "block" : "none";
        });
        boton.addEventListener("click", function () {
            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    }
});
