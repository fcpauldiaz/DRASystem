function ajaxTipoPuesto()
{
    $('body').on('submit', '.ajaxFormTipoPuesto', function (e) {

        e.preventDefault();
       

        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function(data) {

            
            dataOptions = data[0];
            dataIds     = data[1];
            
            // Get the raw DOM object for the select box
            select = document.getElementById('userbundle_puesto_tipoPuesto');
            // Clear the old options
            select.options.length = 0;   
            showCreated = "";
            // Load the new options
            // Or whatever source information you're working with
            for (var index = 0; index < dataOptions.length; index++) {
                option = dataOptions[index];
                opt1 = document.createElement("option");
                opt1.text = option['key'];
                opt1.value = dataIds[index]['value'];
                showCreated = option['key'];
                
                select.options.add(opt1);
                if (index == dataOptions.length-1){
                   $(select).val(dataIds[index]['value']).trigger("change"); 
                }
            }
           
            $("#userbundle_tipopuesto_nombrePuesto").val('');
            $("#userbundle_tipopuesto_descripcion").val('');

            $('#modalTipoPuesto').modal('hide');
            
           

            $(document).trigger("add-alerts", {
              message: "Se ha guardado correctamente: "+showCreated,
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
            $('#modalTipoPuesto').modal('hide');
           
            
             $(document).trigger("add-alerts", {
              message: (jqXHR.responseJSON.error).substring(16),
              priority: "error"
            });

        });
    });
}
function ajaxDepartamento()
{
    $('body').on('submit', '.ajaxFormDepartamento', function (e) {

        e.preventDefault();
       

        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function(data) {

           
            dataOptions = data[0];
            dataIds     = data[1];
            
            // Get the raw DOM object for the select box
            select = document.getElementById('userbundle_puesto_departamento');
            // Clear the old options
            select.options.length = 0;   

            // Load the new options
            showCreated = "";
            // Load the new options
            // Or whatever source information you're working with
            for (var index = 0; index < dataOptions.length; index++) {
                option = dataOptions[index];
                opt1 = document.createElement("option");
                opt1.text = option['key'];
                opt1.value = dataIds[index]['value'];
                showCreated = option['key'];
                
                select.options.add(opt1);
                if (index == dataOptions.length-1){
                    $(select).val(dataIds[index]['value']).trigger("change"); 
                  
                }
            }
            
            $("#userbundle_departamento_nombreDepartamento").val('');
            $("#userbundle_departamento_descripcion").val('');
            $('#modalDepartamento').modal('hide');
            
           

            $(document).trigger("add-alerts", {
              message: "Se ha guardado correctamente: "+showCreated,
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
            $('#modalDepartamento').modal('hide');
           
            
             $(document).trigger("add-alerts", {
              message: (jqXHR.responseJSON.error).substring(16),
              priority: "error"
            });

        });
    });
}
