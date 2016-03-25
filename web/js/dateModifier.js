$('#consultabundle_costo_fechaInicio')
    .on('changeMonth', function(ev){
   
    var day = ev.date.getDate();
    var month = ev.date.getMonth() + 1;
    var year = ev.date.getFullYear();
    if (day < 10) {
        day = "0" + day;
    }
    if (month < 10) {
        month = "0" + month;
    }
    
    var date = day + "/" + month + "/" + year;
    $(this).val(date)
   
});
$('#consultabundle_costo_fechaFinal')
    .on('changeMonth', function(ev){
   
    var month = ev.date.getMonth() + 1;
    var year = ev.date.getFullYear();
    var newDate =  new Date( (new Date(year, month,1))-1 );
    var newDay = newDate.getDate();
    var newMonth = newDate.getMonth()+1;
    var newYear = newDate.getFullYear();
    if (newDay < 10) {
        newDay = "0" + newDay;
    }
    if (newMonth < 10) {
        newMonth = "0" + newMonth;
    }
    
    var date = newDay + "/" + newMonth + "/" + newYear;
    $(this).val(date)
   
});