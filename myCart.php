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
}else{

    $cart_query = "SELECT
    cart.cart_id,
    cart.customer_id,
    cart.product_id,
    products.product_name,
    products.product_brand,
    products.price
    FROM
    cart
    INNER JOIN products ON cart.cart_id = products.product_id
    WHERE
    cart.customer_id = '{$_SESSION['cus_id']}'";

    $result = mysqli_query($connection, $cart_query);

    if($result){

            $cartTable = "<table border=\"1\" cellpadding=\"20\" cellspacing=\"0\">";
            $cartTable .= "<tr>
                            <th>Product Brand</th>
                            <th>Product Name</th>
                            <th>Price</th>
                            </tr>";
    
            while ($cart = mysqli_fetch_array($result)) {
    
                $cartTable .= "<td>" . $cart['product_brand'] . "</td>";
                $cartTable .= "<td>" . $cart['product_name'] . "</td>";
                $cartTable .= "<td>" . "$".$cart['price'] . "</td>";
                $cartTable .= "</tr>";
            }
    
            $cartTable .= "</table>";
        }

    }

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart</title>
    <link rel="stylesheet" href="css/home.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

    <center>
    <h1>My Cart</h1>
    </center>

    <?php

            $cart_query = "SELECT
            cart.cart_id,
            cart.customer_id,
            cart.product_id,
            products.product_name,
            products.product_brand,
            products.qty,
            products.price
            FROM
            cart
            INNER JOIN products ON cart.cart_id = products.product_id
            WHERE
            cart.customer_id = '{$_SESSION['cus_id']}'";

    $result = mysqli_query($connection, $cart_query);

    if ($result) {
        echo mysqli_num_rows($result) . " Records found!";

        if (mysqli_num_rows($result) > 0) { ?>

            <div class="gridContainer">

                <?php while ($record = mysqli_fetch_array($result)) {

                    $_GET['p_id'] = $record['product_id'];

                ?>
                    <div>

                        <a class="linkedPage" href="item.php?item_id=<?= $_GET['p_id'] ?>">

                            <div class="itemCard">

                                <img class="itemImage" src="assets/gt500.jpg" alt="Car">

                                <p class="itemName"><?php echo $record['product_brand']." ".$record['product_name'] ?></p>
                                <p class="itemPrice"><strong> $<?php echo $record['price'] ?> </strong></p>
                                <p class="itemQty"><?php echo $record['qty'] ?> Items Available</p>

                            </div>

                        </a>
                        
                        <div class="buyBtnBox"> <a class="buyBtn" href="purchase.php?item_id=<?=$_GET['p_id']?>"> Buy </a> </div>
                        <a class="favBtn" href="favFunction.php?item_id=<?=$_GET['p_id']?>"> <i class="fa fa-heart" style="font-size:25px"> </i></i> </a>

                    </div>

                <?php } ?>

        </div>

    <?php }
    } else {
        echo "DB failed!";
    }

?>

</body>

</html>

<?php mysqli_close($connection); ?>