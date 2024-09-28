<?php
include 'components/connect.php';
include 'voucher_helper.php';  // Include the voucher helper


session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
};


if (!empty($message)) {
    foreach ($message as $msg) {
        echo "<p>$msg</p>";
    }
}




if(isset($_POST['order'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $address = 'flat no. '. $_POST['flat'] .', '. $_POST['street'] .', '. $_POST['city'] .', '. $_POST['state'] .', '. $_POST['country'] .' - '. $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if($check_cart->rowCount() > 0){

      // Insert the order into the orders table
      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

      // Delete the cart after the order is placed
      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);

      // Insert purchase history for tracking
      $insert_purchase = $conn->prepare("INSERT INTO `customer_purchase_history` (customer_id, total_price, purchase_date) VALUES (?, ?, NOW())");
      $insert_purchase->execute([$user_id, $total_price]);

      // Check for voucher eligibility after the order
      check_and_award_voucher($conn, $user_id);

      $message[] = 'Order placed successfully!';
   }else{
      $message[] = 'Your cart is empty';
   }

}

?>


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>checkout</title>
  
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <script src="http://www.paypal.com/sdk/js?client-id=AfWWgIuFSgyu8PBCPZaSblbJ4tuRBURmBDp3lGvNAqcyJmX5zn84vfiPbbEgTviDvsI7kkHQqMSaxYcY"></script>

</head>
<body>


   
<?php include 'components/user_header.php'; ?>

<section class="checkout-orders">

   <form action="" method="POST">

   <h3>Your Orders</h3>

      <div class="display-orders">
      <?php
         $grand_total = 0;
         $shipping_fee = 100; // Example shipping fee, adjust as needed
         $cart_items[] = '';
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);

         if($select_cart->rowCount() > 0){
            while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
               $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].') - ';
               $total_products = implode($cart_items);
               $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
      ?>
         <div class="order-item">
            <img src="images/<?= $fetch_cart['image']; ?>" alt="<?= $fetch_cart['name']; ?>" style="width: 100px; height: auto;">
            <p> <?= $fetch_cart['name']; ?> <span>(<?= '$'.$fetch_cart['price'].'/- x '. $fetch_cart['quantity']; ?>)</span> </p>
         </div>
      <?php

      
            }
         } else {
            echo '<p class="empty">Your cart is empty!</p>';
         }
      ?>

       

         <input type="hidden" name="total_products" value="<?= $total_products; ?>">
         <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
         <div class="grand-total">Grand Total: <span>P<?= $grand_total; ?>/-</span></div>
      </div>

      <h3>Place Your Orders</h3>

      <?php
      // Check if customer data already exists
      $select_customer = $conn->prepare("SELECT * FROM `customers` WHERE user_id = ?");
      $select_customer->execute([$user_id]);

      if($select_customer->rowCount() > 0){
         // Fetch saved customer info
         $fetch_customer = $select_customer->fetch(PDO::FETCH_ASSOC);
        
         $name = $fetch_customer['name'];
         $number = $fetch_customer['number'];
         $email = $fetch_customer['email'];
         $flat = $fetch_customer['flat'];
         $street = $fetch_customer['street'];
         $city = $fetch_customer['city'];
         $state = $fetch_customer['state'];
         $country = $fetch_customer['country'];
         $pin_code = $fetch_customer['pin_code'];

         // Debugging log
         echo "<p>Customer found with user_id: $user_id</p>"; // Log the customer

         // Display customer information at the top
         echo "<div class='customer-info'>
                  <h4>Customer Information:</h4>
                  <p>Name: $name</p>
                  <p>Number: $number</p>
                  <p>Email: $email</p>
                  <p>Address: $flat, $street, $city, $state, $country - $pin_code</p>
               </div>";
      ?>
         <div class="inputBox">
            <span>Payment Method:</span>
            <select name="method" class="box" required>
               <option value="cash on delivery">Cash On Delivery</option>
               <option value="credit card">Credit Card</option>
            </select>
         </div>
      <?php
      } else {
         // If no customer data exists, display the full form
         echo "<p>No customer data found for user_id: $user_id</p>"; // Debugging info
      ?>

      <div class="flex">
         <div class="inputBox">
            <span>Customer Name:</span>
            <input type="text" name="name" placeholder="Enter your name" class="box" maxlength="20" required>
         </div>
         <div class="inputBox">
            <span>Your Number:</span>
            <input type="number" name="number" placeholder="Enter your number" class="box" min="0" max="9999999999" required>
         </div>
         
         <div class="inputBox">
            <span>Your Email:</span>
            <input type="email" name="email" placeholder="Enter your email" class="box" maxlength="50" required>
         </div>
         <div id="paypal-button-container"></div>

<script>

paypal.Buttons().render('#paypal-button-container');
</script>
         
        
         <div class="inputBox">
            <span>Address Line 01:</span>
            <input type="text" name="flat" placeholder="e.g. Flat number" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Address Line 02:</span>
            <input type="text" name="street" placeholder="Street name" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>City:</span>
            <input type="text" name="city" placeholder="Kathmandu" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Province:</span>
            <input type="text" name="state" placeholder="Bagmati" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Country:</span>
            <input type="text" name="country" placeholder="Nepal" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>ZIP CODE:</span>
            <input type="number" name="pin_code" placeholder="e.g. 56400" min="0" max="999999" class="box" required>
         </div>
      </div>

      <?php } ?>

      <input type="submit" name="order" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>" value="Place Order">
     
  
   </form>

</section>

<?php
// Your order processing logic can go here
?>






<?php include 'components/footer.php'; ?>


<script src="js/script.js"></script>

</body>
</html>