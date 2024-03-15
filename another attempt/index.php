<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>
  
    <!-- calendar integration -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>

    <link rel="stylesheet" href="style.css"/>

    <?php
        include('dbcon.php');
        $query = $conn->query("SELECT * FROM events ORDER BY id");
    ?>

<script>
    $(document).ready(function() {
        var calendar = $('#calendar').fullCalendar({
            editable:true,
            header:{
            left:'prev,next today',
            center:'title',
            right:'month,agendaWeek,agendaDay'},
            events: [<?php while ($row = $query ->fetch_object()) { ?>{ id : '<?php echo $row->id; ?>', title : '<?php echo $row->title; ?>', start : '<?php echo $row->start_event; ?>', end : '<?php echo $row->end_event; ?>', }, <?php } ?>],
            selectable:true,
            selectHelper:true,

            select: function(start, end, allDay) {
                var title = prompt("Enter Event Title");
                if(title) {
                    var start = $.fullCalendar.formatDate(start, "Y-MM-DD HH:mm:ss");
                    var end = $.fullCalendar.formatDate(end, "Y-MM-DD HH:mm:ss");
                    $.ajax({
                        url:"insert.php",
                        type:"POST",
                        data:{title:title, start:start, end:end},
                        success:function(data) {
                            calendar.fullCalendar('refetchEvents');
                            alert("Added Successfully");
                            window.location.replace("index.php");
                        }
                    })
                }
            },
        
            editable:true,
            eventResize: function(event) {
                var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                var title = event.title;
                var id = event.id;
                $.ajax({
                    url:"update.php",
                    type:"POST",
                    data:{title:title, start:start, end:end, id:id},
                    success:function(){
                        calendar.fullCalendar('refetchEvents');
                        alert('Event Update');
                        window.location.replace("index.php");
                    }
                })
            },
        
            eventDrop: function(event) {
                var start = $.fullCalendar.formatDate(event.start, "Y-MM-DD HH:mm:ss");
                var end = $.fullCalendar.formatDate(event.end, "Y-MM-DD HH:mm:ss");
                var title = event.title;
                var id = event.id;
                $.ajax({
                    url:"update.php",
                    type:"POST",
                    data:{title:title, start:start, end:end, id:id},
                    success:function() {
                        calendar.fullCalendar('refetchEvents');
                        alert("Event Updated");
                        window.location.replace("index.php");
                    }
                });
            },
        
            eventClick: function(event) {
                if(confirm("Are you sure you want to remove it?")) {
                    var id = event.id;
                    $.ajax({
                        url:"delete.php",
                        type:"POST",
                        data:{id:id},
                        success:function() {
                            calendar.fullCalendar('refetchEvents');
                            alert("Event Removed");
                            window.location.replace("index.php");
                        }
                    })
                }
            },
        });
    });
</script>
</head>

<body>
    <br>
    <div class="container">
        <div class="row">
            <div class="col-3">
                <aside>
                    <!-- Task and Reminders side section -->
                    <div class="container">
                        <div class="row">
                            <h2>Tasks</h2>
                        </div>
                        <div class="row">
                            <h2>Reminder</h2>
                        </div>
                    </div>
                </aside>          
            </div>
            <div class="col-9">
                <!-- Calendar Integration -->
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</body>
</html>