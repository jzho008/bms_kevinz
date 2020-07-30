<?php
 session_start();
if ($_POST) {
    $json_obj = json_encode(array_merge($_POST, $_SESSION['cart_item']));
    //clear session 
    unset($_SESSION);
}
?>
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

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row">
             <h1>Booking Json Output</h1>
        </div>
        
        <div class="row">
            <?php echo  $json_obj; ?>
        </div>
        <br />
         <div class="row">
             <h4 class="text-center"><a href="./index.php">Back to home</a></h4> 
        </div>
    </div>
</body>
</html>


