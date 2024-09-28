<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>







<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="orders">

   <h1 class="heading">Placed Orders.</h1>

   <div class="box-container">

   <?php
      if($user_id == ''){
         echo '<p class="empty">please login to see your orders</p>';
      }else{
         // Fetch orders for the logged-in user
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
         $select_orders->execute([$user_id]);
         
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){

               // Fetch product details based on the order
               $order_id = $fetch_orders['id'];
               $select_products = $conn->prepare("SELECT * FROM `order_products` WHERE order_id = ?");
               $select_products->execute([$order_id]);

   ?>
   
   <div class="box">
      <p>Placed on : <span><?= $fetch_orders['placed_on']; ?></span></p>
      <p>Name : <span><?= $fetch_orders['name']; ?></span></p>
      <p>Email : <span><?= $fetch_orders['email']; ?></span></p>
      <p>Phone Number : <span><?= $fetch_orders['number']; ?></span></p>
      <p>Address : <span><?= $fetch_orders['address']; ?></span></p>
      <p>Payment Method : <span><?= $fetch_orders['method']; ?></span></p>
      <p>Your orders:</p>

      <!-- Display products in the order -->
      <div class="product-list">
         <?php
         while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
            $product_image = $fetch_products['image'];
            $product_name = $fetch_products['name'];
            $product_price = $fetch_products['price'];
            $product_quantity = $fetch_products['quantity'];
         ?>
         <div class="product-item">
            <img src="uploaded_img/<?= $product_image; ?>" alt="<?= $product_name; ?>" class="product-img">
            <p>Product: <span><?= $product_name; ?></span></p>
            <p>Price: <span>Nrs.<?= $product_price; ?>/-</span></p>
            <p>Quantity: <span><?= $product_quantity; ?></span></p>
         </div>
         <?php } ?>
      </div>

      <p>Total price : <span>Nrs.<?= $fetch_orders['total_price']; ?>/-</span></p>
      <p>Payment status: 
         <span style="color:<?php echo ($fetch_orders['payment_status'] == 'pending') ? 'red' : 'green'; ?>">
             <?= $fetch_orders['payment_status']; ?>
         </span>
      </p>

      <?php if ($fetch_orders['payment_status'] != 'pending'): ?>
         <a href="submit.php" value="<?= $fetch_orders['id']; ?>" class="option-btn">Order Received</a>
      <?php endif; ?>
   </div>
   
   <?php
      }
      }else{
         echo '<p class="empty">no orders placed yet!</p>';
      }
      }
   ?>

   </div>

</section>

<style>
   .product-list {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
   }

   .product-item {
      border: 1px solid #ccc;
      padding: 10px;
      width: 200px;
      text-align: center;
      background-color: #f9f9f9;
      border-radius: 5px;
   }

   .product-img {
      max-width: 100%;
      height: auto;
      margin-bottom: 10px;
   }
</style>











<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>