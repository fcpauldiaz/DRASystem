$(document).ready(function(){
	
	function disableCheckBox(param, parent) {
		var checkboxSelected = $('#userbundle_tipopuesto_permisos_'+param);
		var checked = parent[0].checked;
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
		disableCheckBox('8',$(this));
	});
      
    //se tiene que manejar un chedkbox para cada permiso
	//permiso para crear actividades
	$('#userbundle_tipopuesto_permisos_11').on('ifClicked', function(event){
		disableCheckBox('3',$(this));
	});

	//se tiene que manejar un chedkbox para cada permiso
	//permiso para crear horas invertidas
	$('#userbundle_tipopuesto_permisos_12').on('ifClicked', function(event){
		disableCheckBox('2',$(this));
    }); 
    //se tiene que manejar un chedkbox para cada permiso
	//permiso para crear presupuestos
	$('#userbundle_tipopuesto_permisos_13').on('ifClicked', function(event){
		disableCheckBox('4',$(this));
     }); 
    //se tiene que manejar un chedkbox para cada permiso
	//permiso para crear tipos de puesto
	$('#userbundle_tipopuesto_permisos_15').on('ifClicked', function(event){
		disableCheckBox('6',$(this))
      }); 
     //se tiene que manejar un chedkbox para cada permiso
	//permiso para crear departamentos
	$('#userbundle_tipopuesto_permisos_16').on('ifClicked', function(event){
		disableCheckBox('7',$(this));
      }); 
    //se tiene que manejar un chedkbox para cada permiso
	//permiso para editar horas invertidas
	$('#userbundle_tipopuesto_permisos_17').on('ifClicked', function(event){
		disableCheckBox('12',$(this));
		disableCheckBox('2',$(this));
	});
	//permiso para editar actividades
	$('#userbundle_tipopuesto_permisos_18').on('ifClicked', function(event){
		disableCheckBox('11',$(this));
		disableCheckBox('3',$(this));
	});
	
	//permiso para editar presupuestos
	$('#userbundle_tipopuesto_permisos_19').on('ifClicked', function(event){
		disableCheckBox('13',$(this));
		disableCheckBox('4',$(this));
    }); 
	
    //permiso para editar clientes
    $('#userbundle_tipopuesto_permisos_20').on('ifClicked', function(event){
		disableCheckBox('10',$(this));
		disableCheckBox('8',$(this));
    });
    //permiso para editar usuarios
    $('#userbundle_tipopuesto_permisos_21').on('ifClicked', function(event){
		disableCheckBox('14',$(this));
    });
     //permiso para editar tipo puestos
    $('#userbundle_tipopuesto_permisos_22').on('ifClicked', function(event){
		disableCheckBox('15',$(this));
		disableCheckBox('6',$(this));
    });  
     //permiso para editar tipo departamentos
    $('#userbundle_tipopuesto_permisos_23').on('ifClicked', function(event){
		disableCheckBox('16',$(this));
		disableCheckBox('7',$(this));
    }); 
     //permiso para eliminar horas invertidas
    $('#userbundle_tipopuesto_permisos_24').on('ifClicked', function(event){
		disableCheckBox('2',$(this));
		disableCheckBox('12',$(this));
		disableCheckBox('17',$(this));
    });   
     //permiso para eliminar presupuestos
    $('#userbundle_tipopuesto_permisos_25').on('ifClicked', function(event){
		disableCheckBox('4',$(this));
		disableCheckBox('13',$(this));
		disableCheckBox('19',$(this));
    });
     //permiso para eliminar actividades
    $('#userbundle_tipopuesto_permisos_26').on('ifClicked', function(event){
		disableCheckBox('3',$(this));
		disableCheckBox('11',$(this));
		disableCheckBox('18',$(this));
    });
            
     //permiso para eliminar clientes
    $('#userbundle_tipopuesto_permisos_27').on('ifClicked', function(event){
		disableCheckBox('8',$(this));
		disableCheckBox('10',$(this));
		disableCheckBox('20',$(this));
    }); 
      //permiso para eliminar tipo puestos
    $('#userbundle_tipopuesto_permisos_28').on('ifClicked', function(event){
		disableCheckBox('6',$(this));
		disableCheckBox('15',$(this));
		disableCheckBox('22',$(this));
    });
       //permiso para eliminar departamentos
    $('#userbundle_tipopuesto_permisos_29').on('ifClicked', function(event){
		disableCheckBox('7',$(this));
		disableCheckBox('16',$(this));
		disableCheckBox('23',$(this));
    });
	$('#userbundle_tipopuesto_permisos_30').on('ifClicked', function(event){
		disableCheckBox('1',$(this));
		disableCheckBox('2',$(this));
		disableCheckBox('3',$(this));
		disableCheckBox('4',$(this));
		disableCheckBox('5',$(this));
		disableCheckBox('6',$(this));
		disableCheckBox('7',$(this));
		disableCheckBox('8',$(this));
		disableCheckBox('9',$(this));
		disableCheckBox('10',$(this));
		disableCheckBox('11',$(this));
		disableCheckBox('12',$(this));
		disableCheckBox('13',$(this));
		disableCheckBox('14',$(this));
		disableCheckBox('15',$(this));
		disableCheckBox('16',$(this));
		disableCheckBox('17',$(this));
		disableCheckBox('18',$(this));
		disableCheckBox('19',$(this));
		disableCheckBox('20',$(this));
		disableCheckBox('21',$(this));
		disableCheckBox('22',$(this));
		disableCheckBox('23',$(this));
		disableCheckBox('24',$(this));
		disableCheckBox('25',$(this));
		disableCheckBox('26',$(this));
		disableCheckBox('27',$(this));
		disableCheckBox('28',$(this));
		disableCheckBox('29',$(this));
	});

})