<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Booking Management System</title>

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script> 

    <script src="https://momentjs.com/downloads/moment.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.9/datepicker.min.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css">

    <!-- customized css -->
    <link rel="stylesheet" href="./bms.css">
</head>
<body>
<div class="container">
    <a href="export_json.php"></a>
    <h1>Booking Management System</h1>
    <em>Author: Kevin Zhong, Date: <?php echo date('d M, Y') ?></em>
   
    <br /><br />
    <form method="post" action="./booking_calendar.php">
    <div class="row no-gaps">
        <a href="export_json.php"></a>
        <div class="col-sm-6 col-xs-12">
            <label>Arrival Date</label>
            <a href="export_json.php"></a>
            <div class="input-group date" id="datepicker" data-date-format="yyyy-mm-dd">
                <a href="export_json.php"></a>
                <a href="index.php"></a>
                <input type="text" name="arrival_date" class="form-control" required="required" 
                       placeholder="arrival date" autocomplete="off" />
                <div class="input-group-append">
                    <span class="input-group-text glyphicon glyphicon-calendar" ></span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xs-12">
           <div class="form-group">
               <label>Number of guests</label>
               <input type='number' name="guest_number" class="form-control" placeholder="number of guests" required="required" />
          </div>
        </div>
        <div class="col-12">
             <input type="submit" name="Search" class="btn btn-primary" value="Search" />
        </div>
        <a href="booking_calendar.php"></a>
    </div>
    </form>
 </div>
 
<script type="text/javascript">
    $(function () {
        var select_date = new Date();
        select_date.setDate(select_date.getDate()+1);

        $("#datepicker").datepicker({ 
            format: 'yyyy-mm-dd',
            autoclose: true, 
            todayHighlight: true,
            startDate: select_date
        });
        
    });
</script>   
</body>