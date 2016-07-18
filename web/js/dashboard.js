$(document).ready(function(){

	//una vez ya guardado el color, se revisa el storage
	var color = localStorage.getItem('color');
	if (color !== null) {
		$('#body').removeClass();
		$('#body').addClass('hold-transition skin-'+color+' sidebar-mini');
	}

	$('#dash-blue').on('click', function(){
		$('#body').removeClass();
		$('#body').addClass('hold-transition skin-blue sidebar-mini');
		$('input:checkbox').prop('checked',false);
		$(this).prop('checked', true);
		localStorage.setItem('color', 'blue');
	});
	$('#dash-orange').on('click', function() {
		$('#body').removeClass();
		$('#body').addClass('hold-transition skin-yellow sidebar-mini');
		$('input:checkbox').prop('checked',false);
		$(this).prop('checked', true);
		localStorage.setItem('color', 'yellow');
	});
	$('#dash-green').on('click', function() {
		$('#body').removeClass();
		$('#body').addClass('hold-transition skin-green sidebar-mini');
		$('input:checkbox').prop('checked',false);
		$(this).prop('checked', true);
		localStorage.setItem('color', 'green');
	});
	$('#dash-purple').on('click', function() {
		$('#body').removeClass();
		$('#body').addClass('hold-transition skin-purple sidebar-mini');
		$('input:checkbox').prop('checked',false);
		$(this).prop('checked', true);
		localStorage.setItem('color', 'purple');
	});
	$('#dash-red').on('click', function() {
		$('#body').removeClass();
		$('#body').addClass('hold-transition skin-red sidebar-mini');
		$('input:checkbox').prop('checked',false);
		$(this).prop('checked', true);
		localStorage.setItem('color', 'red');
	});
	$('#dash-black').on('click', function() {
		$('#body').removeClass();
		$('#body').addClass('hold-transition skin-black sidebar-mini');
		$('input:checkbox').prop('checked',false);
		$(this).prop('checked', true);
		localStorage.setItem('color', 'black');
	});

})