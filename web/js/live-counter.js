// record start time

    var startTime;

    function display() {
        // later record end time
        var endTime = new Date();

        // time difference in ms
        var timeDiff = endTime - startTime;
        
        // strip the miliseconds
        timeDiff /= 1000;

        // get seconds
        var seconds = Math.round(timeDiff % 60);

        // remove seconds from the date
        timeDiff = Math.floor(timeDiff / 60);

        // get minutes
        var minutes = Math.round(timeDiff % 60);

        // remove minutes from the date
        timeDiff = Math.floor(timeDiff / 60);

        // get hours
        var hours = Math.round(timeDiff % 24);

        // remove hours from the date
        timeDiff = Math.floor(timeDiff / 24);

        // the rest of timeDiff is number of days
        var days = timeDiff;

        $(".time").text(days + " días, " + hours + ":" + minutes + ":" + seconds);
        setTimeout(display, 1000);
    }

    $(document).on('click', '.contador' , function() {
        console.log('clicked');
      
        startTime = new Date();
        setTimeout(display, 1000);

    });

