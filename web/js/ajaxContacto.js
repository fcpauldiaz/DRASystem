function ajaxContacto()
{
    $('body').on('submit', '.ajaxContactoForm', function (e) {

        e.preventDefault();
        e.stopImmediatePropagation();
       

        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function(data) {

            dataOptions = data[0];
            dataIds     = data[1];
            
            // Get the raw DOM object for the select box
            select = document.getElementById('appbundle_cliente_contactos');
            // Clear the old options
            select.options.length = 0;   

            // Load the new options
            // Or whatever source information you're working with
            for (var index = 0; index < dataOptions.length; index++) {
                option = dataOptions[index];
                opt1 = document.createElement("option");
                opt1.text = option['key'];
                opt1.value = dataIds[index]['value'];
               
                
                select.options.add(opt1);
            }
            
           

            $('#modalContacto').modal('hide');
            
           

            $(document).trigger("add-alerts", {
              message: "Se ha guardado correctamente el contacto",
              priority: "success"
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
            $('#myModal').modal('hide');
           
            
             $(document).trigger("add-alerts", {
              message: (jqXHR.responseJSON.error).substring(16),
              priority: "error"
            });

        });
    });
}
