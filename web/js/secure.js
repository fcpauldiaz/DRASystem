  $(document).ready(function() {

    var key = localStorage.getItem('SessionId');
    var secret = localStorage.getItem('SessionKey');

    
    if (key !== null && secret !== null) {

      var data = {'SessionId': key, 'SessionKey': secret};
        $.ajax({
            url: window.location.origin+'/api/login',
            data: data,
            method: 'POST',
            success: function(data) {

              if (data.valid === true){
                notie.alert({ type: 4, text: 'Usuario recordado: iniciando sesión automáticamente'});
                setTimeout(function(){ }, 3000);
                window.location = window.location.origin;
              }
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
    }
  });