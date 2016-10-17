  $(document).ready(function() {
    console.log('ready');
    var key = localStorage.getItem('SessionId');
    var secret = localStorage.getItem('SessionKey');

    
    if (key !== null && secret !== null) {
      console.log('sending ajax');
      var data = {'SessionId': key, 'SessionKey': secret};
        $.ajax({
            url: window.location.origin+'/api/login',
            data: data,
            method: 'POST',
            success: function(data) {
              console.log(data);
              if (data.valid === true){
                notie.alert(1, 'Usuario recordado: iniciando sesión automáticamente');
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