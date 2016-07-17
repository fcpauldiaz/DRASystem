$(document).ready(function(){
	
	function disableCheckBox(param) {
		var checkboxSelected = $('#userbundle_tipopuesto_permisos_'+param);
		var checked = checkboxSelected[0].checked;
		if (checked === true) {
			checkboxSelected.iCheck('uncheck');
			checkboxSelected.iCheck('enable');
		}
		else {
			checkboxSelected.iCheck('check');
			checkboxSelected.iCheck('disable');
		}
	}
		//se tiene que manejar un chedkbox para cada permiso
	//permiso para crear clientes
	$('#userbundle_tipopuesto_permisos_10').on('ifClicked', function(event){
		disableCheckBox('8');
	});
      
    //se tiene que manejar un chedkbox para cada permiso
	//permiso para crear actividades
	$('#userbundle_tipopuesto_permisos_11').on('ifClicked', function(event){
		disableCheckBox('3');
	});

	//se tiene que manejar un chedkbox para cada permiso
	//permiso para crear horas invertidas
	$('#userbundle_tipopuesto_permisos_12').on('ifClicked', function(event){
		disableCheckBox('2');
    }); 
    //se tiene que manejar un chedkbox para cada permiso
	//permiso para crear presupuestos
	$('#userbundle_tipopuesto_permisos_13').on('ifClicked', function(event){
		disableCheckBox('4');
     }); 
    //se tiene que manejar un chedkbox para cada permiso
	//permiso para crear tipos de puesto
	$('#userbundle_tipopuesto_permisos_15').on('ifClicked', function(event){
		disableCheckBox('6')
      }); 
     //se tiene que manejar un chedkbox para cada permiso
	//permiso para crear departamentos
	$('#userbundle_tipopuesto_permisos_16').on('ifClicked', function(event){
		disableCheckBox('7');
      }); 
    //se tiene que manejar un chedkbox para cada permiso
	//permiso para editar horas invertidas
	$('#userbundle_tipopuesto_permisos_17').on('ifClicked', function(event){
		disableCheckBox('12');
		disableCheckBox('2');
	});
	//permiso para editar actividades
	$('#userbundle_tipopuesto_permisos_18').on('ifClicked', function(event){
		disableCheckBox('11');
		disableCheckBox('3');
	});
	
	//permiso para editar presupuestos
	$('#userbundle_tipopuesto_permisos_19').on('ifClicked', function(event){
		disableCheckBox('13');
		disableCheckBox('4');
    }); 
	
    //permiso para editar clientes
    $('#userbundle_tipopuesto_permisos_20').on('ifClicked', function(event){
		disableCheckBox('10');
		disableCheckBox('8');
    });
    //permiso para editar usuarios
    $('#userbundle_tipopuesto_permisos_21').on('ifClicked', function(event){
		disableCheckBox('14');
    });
     //permiso para editar tipo puestos
    $('#userbundle_tipopuesto_permisos_22').on('ifClicked', function(event){
		disableCheckBox('15');
		disableCheckBox('6');
    });  
     //permiso para editar tipo departamentos
    $('#userbundle_tipopuesto_permisos_23').on('ifClicked', function(event){
		disableCheckBox('16');
		disableCheckBox('7');
    }); 
     //permiso para eliminar horas invertidas
    $('#userbundle_tipopuesto_permisos_24').on('ifClicked', function(event){
		disableCheckBox('2');
		disableCheckBox('12');
		disableCheckBox('17');
    });   
     //permiso para eliminar presupuestos
    $('#userbundle_tipopuesto_permisos_25').on('ifClicked', function(event){
		disableCheckBox('4');
		disableCheckBox('13');
		disableCheckBox('19');
    });
     //permiso para eliminar actividades
    $('#userbundle_tipopuesto_permisos_26').on('ifClicked', function(event){
		disableCheckBox('3');
		disableCheckBox('11');
		disableCheckBox('18');
    });
            
     //permiso para eliminar clientes
    $('#userbundle_tipopuesto_permisos_27').on('ifClicked', function(event){
		disableCheckBox('8');
		disableCheckBox('10');
		disableCheckBox('20');
    }); 
      //permiso para eliminar tipo puestos
    $('#userbundle_tipopuesto_permisos_28').on('ifClicked', function(event){
		disableCheckBox('6');
		disableCheckBox('15');
		disableCheckBox('22');
    });
       //permiso para eliminar departamentos
    $('#userbundle_tipopuesto_permisos_29').on('ifClicked', function(event){
		disableCheckBox('7');
		disableCheckBox('16');
		disableCheckBox('23');
    });
	$('#userbundle_tipopuesto_permisos_30').on('ifClicked', function(event){
		disableCheckBox('1');
		disableCheckBox('2');
		disableCheckBox('3');
		disableCheckBox('4');
		disableCheckBox('5');
		disableCheckBox('6');
		disableCheckBox('7');
		disableCheckBox('8');
		disableCheckBox('9');
		disableCheckBox('10');
		disableCheckBox('11');
		disableCheckBox('12');
		disableCheckBox('13');
		disableCheckBox('14');
		disableCheckBox('15');
		disableCheckBox('16');
		disableCheckBox('17');
		disableCheckBox('18');
		disableCheckBox('19');
		disableCheckBox('20');
		disableCheckBox('21');
		disableCheckBox('22');
		disableCheckBox('23');
		disableCheckBox('24');
		disableCheckBox('25');
		disableCheckBox('26');
		disableCheckBox('27');
		disableCheckBox('28');
		disableCheckBox('29');
	});

})