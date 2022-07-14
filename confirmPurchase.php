<?php session_start(); ?>
<?php require_once('connections/dbconnection.php'); ?>
<?php require_once('components/header.php'); ?>

<?php

$p_id = " ";
$c_id = " ";
$t_price = " ";
$o_qty = " ";

if (!isset($_SESSION['cus_id'])) {
    header('Location: landing_page.php');
} else {

    if (isset($_GET['item_id'])) {
        echo "ID ".$_GET['item_id']." passed! <br>";
    } else {
        echo "ID pass failed!";
    }
    if (isset($_GET['total']) && $_GET['qty']) {
        echo "total price and order qty is passed!";
    } else {
        //header('Location: home.php');
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Purchase</title>
    <link rel="stylesheet" href="css/confirmPurchase.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

    <?Php

    $_GET['id'] = $_SESSION['cus_id'];
    $cus_id = $_GET['id'];
    $t_price = $_GET['total'];
    $o_qty = $_GET['qty'];

    $query = "SELECT product_id, product_brand, product_name, price FROM products WHERE product_id = '{$_GET['item_id']}' LIMIT 1";

    $result = mysqli_query($connection, $query);

    if ($result) {
        while ($record = mysqli_fetch_array($result)) {
            $_GET['prd_id'] = $record['product_id'];
            $pro_id = $_GET['prd_id'];
    ?>

            <div class="confirmCard">
                <h2 class="h2">Confirm Purchase</h2>
                <p class="p"><?php echo $record['product_id'] ?></p>
                <p class="p1"><?php echo $record['product_brand'] ?></p>
                <p class="p2"><?php echo $record['product_name'] ?></p>
                <p class="p3"><?php echo "$" . $record['price'] ?></p>
                <p class="p4"><?php echo $o_qty ?> Units Placed</p>
                <p class="p4">Total Price: $<?php echo $t_price ?></p>
            </div>

    <?php

        }
    }

    ?>

    <form action="confirmPurchase.php" method="POST">
        <input type="hidden" name="cus_id" value="<?php echo $cus_id; ?>">
        <input type="hidden" name="pro_id" value="<?php echo $pro_id; ?>">
        <input type="hidden" name="t_price" value="<?php echo $t_price; ?>">
        <input type="hidden" name="o_qty" value="<?php echo $o_qty; ?>">
        <input type="submit" name="confirm" value="Confirm Purchase">
    </form>

    <?php

    if (isset($_POST['confirm'])) {
        $cid = mysqli_real_escape_string($connection, $_POST['cus_id']);
        $pid = mysqli_real_escape_string($connection, $_POST['pro_id']);
        $tp = mysqli_real_escape_string($connection, $_POST['t_price']);
        $oqty = mysqli_real_escape_string($connection, $_POST['o_qty']);
        //echo $cid." , ".$pid." , ".$tp." , ".$oqty." , "."passed!";

        $insert_query = "INSERT INTO orders(customer_id, product_id, order_qty, order_price)
                        VALUES ('{$cid}', '{$pid}', '{$oqty}', '{$tp}')";

        $query_checker = mysqli_query($connection, $insert_query);
        
        if($query_checker){
            header("location: myOrders.php");
        }

    }

    ?>


</body>

</html>

<?php mysqli_close($connection); ?>