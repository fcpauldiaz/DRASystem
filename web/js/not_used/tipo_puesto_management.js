$(document).ready(function(){

	$('#userbundle_tipopuesto_permisos_30').on('ifChecked', function(event){
	  alert(event.type + ' callback');
	});

	function checkBoxFunc(param, component) {
		console.log('Entra al evento');
		var checkboxSelected = $('#userbundle_tipopuesto_permisos_'+param);

        if((component).is(":checked")) {
            checkboxSelected.prop("checked", true);
            //se inhabilita que lo pueda cambiar manualmente
            checkboxSelected.prop("disabled", true);
        } else{
        	checkboxSelected.prop("checked", false);
        	//se vuele a habilitar el checkbox
        	checkboxSelected.prop("disabled", false);
        }
        
	};

	//se tiene que manejar un chedkbox para cada permiso
	//permiso para crear clientes
	$('#userbundle_tipopuesto_permisos_10').change(function(){	
		checkBoxFunc('8',$(this));
	});
      
    //se tiene que manejar un chedkbox para cada permiso
	//permiso para crear actividades
	$('#userbundle_tipopuesto_permisos_11').change(function(){
		checkBoxFunc('3',$(this));
	});

	//se tiene que manejar un chedkbox para cada permiso
	//permiso para crear horas invertidas
	$('#userbundle_tipopuesto_permisos_12').change(function(){
		checkBoxFunc('2',$(this));
    }); 
    //se tiene que manejar un chedkbox para cada permiso
	//permiso para crear presupuestos
	$('#userbundle_tipopuesto_permisos_13').change(function(){
		checkBoxFunc('4',$(this));
     }); 
    //se tiene que manejar un chedkbox para cada permiso
	//permiso para crear tipos de puesto
	$('#userbundle_tipopuesto_permisos_15').change(function(){
		checkBoxFunc('6',$(this))
      }); 
     //se tiene que manejar un chedkbox para cada permiso
	//permiso para crear departamentos
	$('#userbundle_tipopuesto_permisos_16').change(function(){
		checkBoxFunc('7',$(this));
      }); 
    //se tiene que manejar un chedkbox para cada permiso
	//permiso para editar horas invertidas
	$('#userbundle_tipopuesto_permisos_17').change(function(){
		checkBoxFunc('12', $(this));
		checkBoxFunc('2',$(this));
	});
	//permiso para editar actividades
	$('#userbundle_tipopuesto_permisos_18').change(function(){
		checkBoxFunc('11',$(this));
		checkBoxFunc('3',$(this));
	});
	
	//permiso para editar presupuestos
	$('#userbundle_tipopuesto_permisos_19').change(function(){
		checkBoxFunc('13', $(this));
		checkBoxFunc('4',$(this));
    }); 
	
    //permiso para editar clientes
    $('#userbundle_tipopuesto_permisos_20').change(function(){
		checkBoxFunc('10',$(this));
		checkBoxFunc('8',$(this));
    });
    //permiso para editar usuarios
    $('#userbundle_tipopuesto_permisos_21').change(function(){
		checkBoxFunc('14', $(this));
    });
     //permiso para editar tipo puestos
    $('#userbundle_tipopuesto_permisos_22').change(function(){
		checkBoxFunc('15', $(this));
		checkBoxFunc('6',$(this));
    });  
     //permiso para editar tipo departamentos
    $('#userbundle_tipopuesto_permisos_23').change(function(){
		checkBoxFunc('16', $(this));
		checkBoxFunc('7',$(this));
    }); 
     //permiso para eliminar horas invertidas
    $('#userbundle_tipopuesto_permisos_24').change(function(){
		checkBoxFunc('2', $(this));
		checkBoxFunc('12', $(this));
		checkBoxFunc('17',$(this));
    });   
     //permiso para eliminar presupuestos
    $('#userbundle_tipopuesto_permisos_25').change(function(){
		checkBoxFunc('4', $(this));
		checkBoxFunc('13', $(this));
		checkBoxFunc('19',$(this));
    });
     //permiso para eliminar actividades
    $('#userbundle_tipopuesto_permisos_26').change(function(){
		checkBoxFunc('3', $(this));
		checkBoxFunc('11', $(this));
		checkBoxFunc('18',$(this));
    });
            
     //permiso para eliminar clientes
    $('#userbundle_tipopuesto_permisos_27').change(function(){
		checkBoxFunc('8', $(this));
		checkBoxFunc('10', $(this));
		checkBoxFunc('20',$(this));
    }); 
      //permiso para eliminar tipo puestos
    $('#userbundle_tipopuesto_permisos_28').change(function(){
		checkBoxFunc('6', $(this));
		checkBoxFunc('15', $(this));
		checkBoxFunc('22',$(this));
    });
       //permiso para eliminar departamentos
    $('#userbundle_tipopuesto_permisos_29').change(function(){
		checkBoxFunc('7', $(this));
		checkBoxFunc('16', $(this));
		checkBoxFunc('23',$(this));
    });  
    //acceso a todo y panel de control
    $('#userbundle_tipopuesto_permisos_30').change(function(){
		checkBoxFunc('1', $(this));
		checkBoxFunc('2', $(this));
		checkBoxFunc('3', $(this));
		checkBoxFunc('4', $(this));
		checkBoxFunc('5', $(this));
		checkBoxFunc('6', $(this));
		checkBoxFunc('7', $(this));
		checkBoxFunc('8', $(this));
		checkBoxFunc('9', $(this));
		checkBoxFunc('10', $(this));
		checkBoxFunc('11', $(this));
		checkBoxFunc('12', $(this));
		checkBoxFunc('13', $(this));
		checkBoxFunc('14', $(this));
		checkBoxFunc('15', $(this));
		checkBoxFunc('16', $(this));
		checkBoxFunc('17', $(this));
		checkBoxFunc('18', $(this));
		checkBoxFunc('19', $(this));
		checkBoxFunc('20', $(this));
		checkBoxFunc('21', $(this));
		checkBoxFunc('22', $(this));
		checkBoxFunc('23', $(this));
		checkBoxFunc('24', $(this));
		checkBoxFunc('25', $(this));
		checkBoxFunc('26', $(this));
		checkBoxFunc('27', $(this));
		checkBoxFunc('28', $(this));
		checkBoxFunc('29', $(this));

    });                

   
  
})