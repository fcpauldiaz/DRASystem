function ajaxAprobacionHoras()
{
    $('body').on('click', '.aprobarHoras', function (e) {
        var elementoActual = $(this);
        e.preventDefault();
       
        $.ajax({
            url: $(this).attr('href'),
            data: $(this).serialize(),
            success: function(data) {
           
            
                elementoActual.text('Aprobado');
                elementoActual.attr('class', 'btn btn-danger btn-sm quitarHoras');
                elementoActual.hover(
                function () {
                     $(this).text("Quitar aprobación");
                },
                 function () {
                     $(this).text("Aprobado");
                });
                
               

            }
        })
        .done(function (data) {
            if (typeof data.message !== 'undefined') {
                alert(data.message);
            }

            
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            if (typeof jqXHR.responseJSON !== 'undefined') {
                if (jqXHR.responseJSON.hasOwnProperty('form')) {
                    $('#form_body').html(jqXHR.responseJSON.form);
                }

                $('.form_error').html(jqXHR.responseJSON.message);

            } else {

            }
           
           
            
             

        });
    });
}
function ajaxQuitarAprobacionHoras()
{
    $('body').on('click', '.quitarHoras', function (e) {
        var elementoActual = $(this);
        e.preventDefault();
       
        $.ajax({
            url: $(this).attr('href'),
            data: $(this).serialize(),
            success: function(data) {
            
               
                elementoActual.text('Aprobar Horas');
                elementoActual.attr('class', 'btn btn-info btn-sm aprobarHoras');
                elementoActual.unbind();
            
               

            }
        })
        .done(function (data) {
            if (typeof data.message !== 'undefined') {
                alert(data.message);
            }

            
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            if (typeof jqXHR.responseJSON !== 'undefined') {
                if (jqXHR.responseJSON.hasOwnProperty('form')) {
                    $('#form_body').html(jqXHR.responseJSON.form);
                }

                $('.form_error').html(jqXHR.responseJSON.message);

            } else {

            }
          
           
        });
    });
}
function ajaxEnvioAvisoHoras()
{
    $('body').on('click', '.enviarCorreo', function (e) {
        var elementoActual = $(this);
        e.preventDefault();
       
        $.ajax({
            url: $(this).attr('href'),
            data: $(this).serialize(),
            success: function(data) {
           
            
            elementoActual.text(data);
            elementoActual.attr('class', 'btn btn-primary btn-lg');
                
                
               

            }
        })
        .done(function (data) {
            if (typeof data.message !== 'undefined') {
                alert(data.message);
            }

            
        })
        .fail(function (jqXHR, textStatus, errorThrown) {
            if (typeof jqXHR.responseJSON !== 'undefined') {
                if (jqXHR.responseJSON.hasOwnProperty('form')) {
                    $('#form_body').html(jqXHR.responseJSON.form);
                }

                $('.form_error').html(jqXHR.responseJSON.message);

            } else {

            }
           
           
            
             

        });
    });
}