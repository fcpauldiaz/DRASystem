function ajaxPuesto()
{
    $('body').on('submit', '.ajaxFormPuesto', function (e) {

        e.preventDefault();
       

        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function(data) {

            key = data[0];
            value     = data[1];
            
            // Get the raw DOM object for the select box
            select = document.getElementById('fos_user_registration_form_puestos');
            // Clear the old options
            select.options.length = 0;   

            // Load the new options
            // Or whatever source information you're working with
            /*for (var index = 0; index < dataOptions.length; index++) {
                option = dataOptions[index];
                opt1 = document.createElement("option");
                opt1.text = option['key'];
                opt1.value = dataIds[index]['value'];
               
                
                select.options.add(opt1);
            }*/
            opt1 = document.createElement("option");
            opt1.text = key;
            opt1.value = value;
            
            select.options.add(opt1);
            select.selectedIndex = key;
            console.log(select);
            
           

            $('#modalPuesto').modal('hide');
            
           

            $(document).trigger("add-alerts", {
              message: "Se ha guardado correctamente",
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
