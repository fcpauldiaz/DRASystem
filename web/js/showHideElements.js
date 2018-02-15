//sirve para llamar a la función cuando el DOM esté listo
$(document).ready(controlFormularioMontetario);

//sirve como listener cada vez que cambia el elemento
$('.hide-element-decision').on('change', function() {
   controlFormularioMontetario();

});
//sirve para mostrar o ocultar los elementos de un formulario
//dependiendo de la clase
//se utiliza el hide-element-decision para saber si se oculta o no
//y toodos los elementos que tengan hide-element como clase se modidificará la visibilidad.
function controlFormularioMontetario(){
  console.log($('.hide-element-decision').val());
    if ($(".hide-element-decision").val() == 0) {
      $(".hide-element").hide();
    }
    if ($('.hide-element-decision').val() == 1) {
      $(".hide-element").show();
    }
    if ($('.hide-element-decision').val() == 'Usuarios') {
      $(".hide-element").show();
    }
    if ($('.hide-element-decision').val() == 'Área') {
      $(".hide-element").hide();
    }
    if ($('.hide-element-decision').val() == 'Cliente') {
      $(".hide-element").hide();
    }
}
