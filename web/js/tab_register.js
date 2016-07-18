$(document).ready(function () {

    //función para sugerir nombre de usuario
    $( "#fos_user_registration_form_apellidos" ).change(function() {
     
        var nombres = $('#fos_user_registration_form_nombre').val();
        var apellido = $(this).val().split(" ")[0];
        var usuario = nombres.slice(0,1) + apellido;
        $("#fos_user_registration_form_username").attr("placeholder", usuario);
        console.log(usuario);
    });

    //función para verificar si un string es un número
      function isNumeric(num){
        return !isNaN(num);
    }

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
         if (contraseña != contraseña2) {
             valido = false;
            $(document).trigger("add-alerts", [
            {
              'message': "Las contraseñas no coinciden.",
              'priority': 'danger'
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

        var direccion = $('#fos_user_registration_form_direccion').val();
        var dpi = $('#fos_user_registration_form_dpi').val();
        var nit = $('#fos_user_registration_form_nit').val();
        var telefono = $('#fos_user_registration_form_telefono').val();
        var igss = $('fos_user_registration_form_numeroIgss').val()
        var valido = true;
        
        
        if (direccion == "" || direccion == null){
            valido = false;
          $(document).trigger("add-alerts", [
            {
              'message': "Ha dejado la dirección vacía.",
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
        if (!isNumeric(dpi)) {
             valido = false;
            $(document).trigger("add-alerts", [
            {
              'message': "El campo del DPI no es un número",
              'priority': 'danger'
            }
          ]);

        }
          if (dpi.length != 13) {
             valido = false;
            $(document).trigger("add-alerts", [
            {
              'message': "El DPI debe de ser de 13 dígitos",
              'priority': 'danger'
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
        if (!isNumeric(nit)) {
             valido = false;
            $(document).trigger("add-alerts", [
            {
              'message': "El campo del NIT no es un número",
              'priority': 'danger'
            }
          ]);

        }
        if (telefono != "" && telefono != null) {
            if (!isNumeric(telefono)){
                valido = false;
                $(document).trigger("add-alerts", [
                {
                  'message': "El campo de telefóno tiene que ser un número",
                  'priority': 'danger'
                }
                ]);
            }

        }
        if (igss != "" && igss != null) {
          if (!isNumeric(igss)) {
            valido = false;
              $(document).trigger("add-alerts", [
              {
                'message': "El campo del IGSS no es un número",
                'priority': 'danger'
              }
            ]);
          }
          if (igss.length < 9 || igss.length > 13 ) {
              valido = false;
                $(document).trigger("add-alerts", [
              {
                'message': "El número de afiliación debe ser entre 9 y 13 dígitos.",
                'priority': 'danger'
              }
            ]);
          }
        }


        if (valido === true){
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