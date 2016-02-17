$(document).ready(function () {
    //Initialize tooltips
    $('.nav-tabs > li a[title]').tooltip();
    
    //Wizard
    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {

        var $target = $(e.target);
    
        if ($target.parent().hasClass('disabled')) {
            return false;
        }
    });

    $(".next-step").click(function (e) {

        var $active = $('.wizard .nav-tabs li.active');
        $active.next().removeClass('disabled');
        nextTab($active);


    });
     $(".next-step-tab1").click(function (e) {

        var nombre = $('#fos_user_registration_form_nombre').val();
        var apellido = $('#fos_user_registration_form_apellidos').val();
        var usuario = $('#fos_user_registration_form_username').val();
        var correo = $('#fos_user_registration_form_email').val();
        var contraseña = $('#fos_user_registration_form_plainPassword_first').val();
        var contraseña2 = $('#fos_user_registration_form_plainPassword_second').val();

        var valido = true;
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  

        if (nombre == "" || nombre == null){
            valido = false;
          $(document).trigger("add-alerts", [
            {
              'message': "Ha dejado el nombre vacío.",
              'priority': 'warning'
            }
          ]);
        }
         if (apellido == "" || apellido == null) {
             valido = false;
            $(document).trigger("add-alerts", [
            {
              'message': "Ha dejado el apellido vacío.",
              'priority': 'warning'
            }
          ]);

        }
          if (usuario == "" || usuario == null) {
             valido = false;
            $(document).trigger("add-alerts", [
            {
              'message': "Ha dejado el usuario vacío.",
              'priority': 'warning'
            }
          ]);

        }
          if (correo == "" || correo == null) {
             valido = false;
            $(document).trigger("add-alerts", [
            {
              'message': "Ha dejado el correo vacío.",
              'priority': 'warning'
            }
          ]);

        }
          if (contraseña == "" || contraseña == null) {
             valido = false;
            $(document).trigger("add-alerts", [
            {
              'message': "Ha dejado el contraseña vacía.",
              'priority': 'warning'
            }
          ]);

        }
          if (contraseña2 == "" || contraseña2 == null) {
             valido = false;
            $(document).trigger("add-alerts", [
            {
              'message': "Ha dejado la contraseña vacía.",
              'priority': 'warning'
            }
          ]);

        }
        var correo_valido = regex.test(correo);
        if (!correo_valido){
            valido=false;
            $(document).trigger("add-alerts", [
            {
              'message': "El formato del correo es inválido.",
              'priority': 'warning'
            }
            ]);
        }
       
        if (valido == true){
            var $active = $('.wizard .nav-tabs li.active');
            $active.next().removeClass('disabled');
            nextTab($active);
        }
           

    });
        
    $(".next-step-tab2").click(function (e) {

        var fecha = $('#fos_user_registration_form_fechaIngreso').val();
        var dpi = $('#fos_user_registration_form_dpi').val();
        var nit = $('#fos_user_registration_form_nit').val();

        var valido = true;
        
        
        if (fecha == "" || fecha == null){
            valido = false;
          $(document).trigger("add-alerts", [
            {
              'message': "Ha dejado la fecha de ingreso vacía.",
              'priority': 'warning'
            }
          ]);
        }
         if (dpi == "" || dpi == null) {
             valido = false;
            $(document).trigger("add-alerts", [
            {
              'message': "Ha dejado el DPI vacío.",
              'priority': 'warning'
            }
          ]);

        }
          if (nit == "" || nit == null) {
             valido = false;
            $(document).trigger("add-alerts", [
            {
              'message': "Ha dejado el NIT vacío.",
              'priority': 'warning'
            }
          ]);

        }


        if (valido == true){
            var $active = $('.wizard .nav-tabs li.active');
            $active.next().removeClass('disabled');
            nextTab($active);
        }
           

    });
    

    $(".prev-step").click(function (e) {

        var $active = $('.wizard .nav-tabs li.active');
        prevTab($active);

    });
});

function nextTab(elem) {
    $(elem).next().find('a[data-toggle="tab"]').click();
}
function prevTab(elem) {
    $(elem).prev().find('a[data-toggle="tab"]').click();
}