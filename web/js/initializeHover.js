$( document ).ready(function() {
    var elementoActual = $(".quitarHoras")
    elementoActual.hover(
    function () {
         $(this).text("Quitar aprobación");
    },
     function () {
         $(this).text("Aprobado");
    });
});