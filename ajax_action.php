<!DOCTYPE html>
<html>
<head>
    <!-- customized css -->
    <link rel="stylesheet" href="./bms.css">
</head>
<body>
<?php
session_start();
if(!empty($_POST["action"])) {
    
    switch($_POST["action"]) {
	case "add":
            $code = $_POST['data'];
            
            //data contain room id, property name, booking date etc.
            $data_arr = explode(",", $code);
             
            $room = '';
            $booking_date = '';
            $tax = 0;
            $rate = 0;
            $currency = '';
            $tax_rate = 1;
            $tax_amount = '0.00';
            $total_amount = '0.00';
            
            //prepare output 
            foreach($data_arr as $val) {
                if (strpos($val, 'room_name') !== false) {
                    $room = substr(strrchr( $val, ':'), 1);
                }
                if (strpos($val, 'booking_date') !== false) {
                    $booking_date = substr(strrchr( $val, ':'), 1);
                }
                
                if (strpos($val, 'rate') !== false) {
                    $rate =  substr(strrchr( $val, ':'), 1);    
                }
                
                if (strpos($val, 'currency') !== false) {
                    $currency =  substr(strrchr( $val, ':'), 1);    
                }
                
                if (strpos($val, 'tax_inclusive') !== false) {
                   $tax_inclusive =  substr(strrchr( $val, ':'), 1); 
                   
                    if ((bool)$tax_inclusive) {
                       //charge tax
                        if (strpos($val, 'tax') !== false) {
                            $tax_rate =  substr(strrchr( $val, ':'), 1);  
                            $tax_amount =  number_format((float)( $rate *  15 / 100), 2, '.', '') ;
                            $total_amount =  ($rate + $tax_amount);
                         } 
                    } else {
                      //no tax
                     $total_amount =  $rate;
                  }
                }
            }
            
            //store key as room + booking_date in lowercase without space and special characters
            $key = preg_replace("/[^a-zA-Z0-9]+/", "", strtolower($room  .  $booking_date));
           
            $data = ['room'=>$room,'selected_date'=>$booking_date, 'price'=>$total_amount, 'gst'=>$tax_amount, 'currency'=>$currency];
            $itemArray = array($key=>$data);
            
            if(!empty($_SESSION["cart_item"])) {
                if(!in_array($key, $_SESSION["cart_item"])) {
                    $_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
                } 
            } else {
                $_SESSION["cart_item"] = $itemArray;
            }
            break;
	case "remove":
            
            if(!empty($_SESSION["cart_item"])) {
                foreach($_SESSION["cart_item"] as $k => $v) {
                    if($_POST['key'] == $k)
                        unset($_SESSION["cart_item"][$k]);
                    if(empty($_SESSION["cart_item"]))
                        unset($_SESSION["cart_item"]);
                }
            }
            break;
	case "empty":
            unset($_SESSION["cart_item"]);
	break;		
    }
}
?>
<?php
if(isset($_SESSION["cart_item"])){
    $item_total = 0;
?>
<div class="cart" >
    <h2 class="text-center" style="padding: 10px">Booking Summary</h2> 
    <table  class="table table-striped" cellpadding="10" cellspacing="1">
        <thead>
            <th nowrap="nowrap">Room</th>
            <th>Booking Date</th>
            <th align="center">Price</th>
            <th align="center">GST</th>
            <th>Action</th>
        </thead>	
        <tbody>
    <?php		
        foreach ($_SESSION["cart_item"] as $key => $item){
        
    ?>
        <tr id="<?php echo $key?>">
            <td nowrap="nowrap"><?php echo $item["room"]; ?></td>
            <td nowrap="nowrap"><?php echo $item["selected_date"]; ?></td>
            <td>
                <?php echo '$' .$item["price"]; ?>
            </td>
            <td>
                <?php echo '$' . $item["gst"]; ?>
            </td>
            <td nowrap="nowrap">
                <a onClick="cartAction('remove','<?php echo $key?>', '')" 
                   class="btnRemoveAction cart-action">Remove</a>&nbsp;
                <a onClick="cartAction('empty', '', '')" 
                   class="btnEmptyAction cart-action">Empty</a>   
            </td>
        </tr>
    <?php
        $item_total += $item["price"];
        }
    ?>
        <tr>
            <td colspan="5" align=right><strong>Total:</strong> <?php echo $item['currency']. " $".$item_total; ?></td>
        </tr>
        <tr class="button-group">
            <td colspan="5">
                <input type="button" class="btn btn-primary" value="Enquiry" 
                       onclick="$('.enquiry_now_wrapper').toggle()" />
            </td>
        </tr>
        <tr class="button-group">
            <td colspan="5">
                <div class="row enquiry_now_wrapper" style="display:none">
                    <form method="post" action="./export_json.php">
                        <div class="col-sm-12">
                            <label>Firstname</label> 
                            <input type="text" name="firstname" placeholder="first name" 
                                   class="form-control" required="required" />
                        </div>
                        <div class="col-sm-12">
                            <label>Lastname</label> 
                            <input type="text" name="lastname" placeholder="lastname" 
                                   class="form-control"  required="required" />
                        </div>
                        <div class="col-sm-12">
                            <label>email</label>
                            <input type="email" name="email" placeholder="email" 
                                   class="form-control"  required="required" />
                        </div>
                        <div class="col-sm-12"><br />
                            <input type="submit" class="btn btn-primary" />
                        </div>
                    </form>
                </div>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<?php
}
?>
<script>

function cartAction(action, room, date) {
    var queryString = "";
    if(action != "") {
        switch(action) {
            case "remove":
                queryString = 'action='+action+'&key='+ room;
            break;
            case "empty":
                queryString = 'action='+action;
            break;
        }	 
    }
    if (action == 'remove' || action == 'empty') {
        if (confirm('Are you sure to do this?')) {
             jQuery.ajax({
                url: "./ajax_action.php",
                data:queryString,
                type: "POST",
                success:function(data){
                    $("#cart-item").html(data);
                    if(action != "") {
                        switch(action) {
                           
                            case "remove":
                                var key = (queryString.split('key' + '=')[1] || '').split('&')[0];
                                $('#' + key).remove();
                                //need to fix total amount issue by reload the page
                                break;
                            case "empty":
                                $(".cart").html('');
                                break;   
                        }	 
                    }
                },
                error:function (){}
            });
        }
    }
}
</script>
</body>
</html>