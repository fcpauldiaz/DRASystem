$('.fecha-inicial')
    .on('changeMonth', function(ev){
    date = ev.date;
    var firstDay = new Date(date.getFullYear(), date.getMonth() +1 , 1);
    var day = firstDay.getDate();
    var month = firstDay.getMonth()+1 ;
    var year = firstDay.getFullYear();
    if (day < 10) {
        day = "0" + day;
    }
    if (month < 10) {
        month = "0" + month;
    }
    console.log(firstDay);
    var stringDate = day + "/" + month + "/" + year;
    $(this).val(stringDate);
   
});
$('.fecha-final')
    .on('changeMonth', function(ev){
    date = ev.date;
    var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
    var day = lastDay.getDate();
    var month = lastDay.getMonth()+1;
    var year = lastDay.getFullYear();
   
     if (day < 10) {
        day = "0" + day;
    }
    if (month < 10) {
        month = "0" + month;
    }
    var stringDate = day + "/" + month + "/" + year;
  
    $(this).val(stringDate)
   
});