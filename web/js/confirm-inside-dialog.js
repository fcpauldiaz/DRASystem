$(document).ready(function (){
    $("#inside-confirm").click(function (event) {
        console.log('entra')
        swal({   
            title: "¿Seguro que lo desea eliminar?",   
            text: "Esta acción no se puede deshacer y afectará la información relacionada",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#DD6B55",   
            confirmButtonText: "Sí, eliminar registro!",   
            cancelButtonText: "Cancelar",   
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            closeOnCancel: false,
            reverseButtons: true,
            showCloseButton: true 
            }).then(function() {   
                $('#delete-form').submit();
                swal({
                    title: "Eliminando",
                    text: "Se eliminará el registro",
                    type: "success",
                    showLoaderOnConfirm: true,
                    showCancelButton: false
                });   
            }, function(dismiss) {
                if (dismiss === 'cancel') {
                    swal("Cancelado", "Se ha cancelado la operación", "error");   
                }
            }); 
    });
});