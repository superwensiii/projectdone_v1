<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Quick view</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

      

</header><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tracking System</title>
    <link rel="stylesheet" href="css/tracking.css">
</head>
<body>
    

    <!-- Order Tracking Section -->
    <div class="order-tracking">
        
        <div id="tracking-result" class="tracking-result">
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders.</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">
   <h1 class="heading">Your Orders.</h1>
</head>
</div>
<div>
</div>
<section class="orders">

<div class="box-container">
<?php
    $select_user = $conn->prepare("SELECT * FROM `orders` WHERE id = ?");
    $select_user->execute([$user_id]);
      $select_orders = $conn->prepare("SELECT * FROM `orders`");
      $select_orders->execute([]);
      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p> Placed On : <span><?= $fetch_orders['placed_on']; ?></span> </p>
      <p> Email : <span><?= $fetch_orders['email']; ?></span> </p>
      <p> Name : <span><?= $fetch_orders['name']; ?></span> </p>
      <p> Number : <span><?= $fetch_orders['number']; ?></span> </p>
      <p> Address : <span><?= $fetch_orders['address']; ?></span> </p>
      <p> Total products : <span><?= $fetch_orders['total_products']; ?></span> </p>
      <p> Total price : <span>P<?= $fetch_orders['total_price']; ?>/-</span> </p>
      <p> Payment method : <span><?= $fetch_orders['method']; ?></span> </p>
     
      <p>Payment status: 
    <span style="color:<?php echo ($fetch_orders['payment_status'] == 'pending') ? 'red' : 'green'; ?>">
        <?= $fetch_orders['payment_status']; ?>
    </span>
    <?php if ($fetch_orders['payment_status'] != 'pending'): ?>
        <a href="product_rating.php" class="option-btn">Order Received</a>
    <?php endif; ?>
</p>
         </select>
        
         

       
        
      </form>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">no orders placed yet!</p>';
      }
   ?>

</div>



</section>
        </div>
    </div>

</body>




<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
