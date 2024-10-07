<?php
include 'components/connect.php';
include 'voucher_helper.php';  // Include the voucher helper


session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
   header('location:user_login.php');
}

$message = []; // Ensure $message is initialized as an array

$discount = 0; // Initialize discount

if(isset($_POST['order'])) {
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
   $method = isset($_POST['method']) ? $_POST['method'] : '';
   $address = 'flat no. '. $_POST['flat'] .', '. $_POST['street'] .', '. $_POST['city'] .', '. $_POST['state'] .', '. $_POST['country'] .' - '. $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];
   $grand_total = $total_price + 100; // Including shipping fee

   $coupon_code = isset($_POST['coupon_code']) ? $_POST['coupon_code'] : '';
   if(!empty($coupon_code)){
      $discount = validate_voucher($conn, $coupon_code, $user_id);
      $grand_total -= $discount;  // Reduce grand total by discount amount
   }

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if($check_cart->rowCount() > 0) {
      // Insert the order into the orders table
      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $grand_total]);

      // Delete the cart after the order is placed
      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);

      // Insert purchase history for tracking
      $insert_purchase = $conn->prepare("INSERT INTO `customer_purchase_history` (customer_id, total_price, purchase_date) VALUES (?, ?, NOW())");
      $insert_purchase->execute([$user_id, $grand_total]);

      // Check for voucher eligibility and generate a voucher
      $voucher_code = check_and_award_voucher($conn, $user_id);

      // Display success message with voucher code
      $message[] = 'Order placed successfully!';
      if ($voucher_code) {
         $message[] = "Congratulations! You've earned a voucher for your loyalty: $voucher_code (₱100 off your next order)";
      }
   } else {
      $message[] = 'Your cart is empty';
   }
}

function validate_voucher($conn, $coupon_code, $user_id) {
   // Replace this with your actual logic for validating the coupon
   $check_voucher = $conn->prepare("SELECT * FROM `vouchers` WHERE code = ? AND user_id = ?");
   $check_voucher->execute([$coupon_code, $user_id]);

   if($check_voucher->rowCount() > 0) {
       $fetch_voucher = $check_voucher->fetch(PDO::FETCH_ASSOC);
       return $fetch_voucher['discount_amount'];
   }

   return 0; // No discount if invalid
}
?>

<?php if (!empty($message)): ?>
    <div class="notification-overlay">
        <div class="notification-message">
            <?php foreach ($message as $msg): ?>
                <p><?php echo htmlspecialchars($msg); ?></p>
            <?php endforeach; ?>
            <button onclick="closeNotification()">OK</button>
        </div>
    </div>
<?php endif; ?>

<!-- Place Order Button -->




<style>

</style>

<script>
function closeNotification() {
    document.querySelector('.notification-overlay').style.display = 'none';
}
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="http://www.paypal.com/sdk/js?client-id=AfWWgIuFSgyu8PBCPZaSblbJ4tuRBURmBDp3lGvNAqcyJmX5zn84vfiPbbEgTviDvsI7kkHQqMSaxYcY"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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

            if($select_cart->rowCount() > 0) {
                while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                    $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].') - ';
                    $total_products = implode($cart_items);
                    $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
            ?>
                <div class="order-item">
                    <img src="images/jpg<?= $fetch_cart['image']; ?>" alt="<?= $fetch_cart['name']; ?>" style="width: 100px; height: auto;">
                    <p> <?= $fetch_cart['name']; ?> <span>(<?= '₱'.$fetch_cart['price'].' x '. $fetch_cart['quantity']; ?>)</span> </p>
                </div>
            <?php
                }
                $grand_total += $shipping_fee; // Add shipping fee
            } else {
                echo '<p class="empty">Your cart is empty!</p>';
            }

             // Calculate total with shipping fee and discount
         $grand_total += $shipping_fee;
         $grand_total -= $discount;
            ?>
            <input type="hidden" name="total_products" value="<?= $total_products; ?>">
            <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
            <div class="grand-total">Grand Total (including shipping): <span>₱<?= $grand_total; ?></span></div>
        </div>

        <h3>Coupon Code</h3>
      <div class="inputBox">
         <span>Enter Coupon Code:</span>
         <input type="text" name="coupon_code" placeholder="Enter coupon code" class="box" maxlength="20">
      </div>

        <h3>Place Your Orders</h3>
        <?php
        // Display saved customer info or full form if not found
        // Existing code omitted for brevity

        // Check if customer data already exists
      $select_customer = $conn->prepare("SELECT * FROM customers WHERE user_id = ?");
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
      <style>
         .flex {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: space-between;
}

.inputBox {
    flex: 1 1 45%;
    display: flex;
    flex-direction: column;
    margin-bottom: 15px;
}

.inputBox span {
    font-size: 16px;
    color: #333;
    margin-bottom: 5px;
    font-weight: bold;
}

.inputBox .box {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
}

.inputBox .box:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}

#paypal-button-container {
    margin: 20px 0;
    position: center;
}

h1 {
    width: 100%;
    font-size: 24px;
    margin-bottom: 20px;
    color: #007bff;
    text-align: center;
}

.btn {
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 18px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #0056b3;
}

.btn.disabled {
    background-color: #ccc;
    cursor: not-allowed;
}
</style>



      <div class="flex">
         <div class="inputBox">
         <label for="fname"><i class="fa fa-user"></i> Full Name</label>
            <input type="text" name="name" placeholder="Enter your name" class="box" maxlength="20" required>
         </div>
         <div class="inputBox">
            <span>Your Number:</span>
            <input type="number" name="number" placeholder="Enter your number" class="box" min="0" max="9999999999" required>
         </div>
         
         <div class="inputBox">
         <label for="email"><i class="fa fa-envelope"></i> Your Email</label>
            <input type="email" name="email" placeholder="Enter your email" class="box" maxlength="50" required>
         </div>
         <h1>Payment Method</h1>
      
      <!-- Payment Method Selection -->
      <div class="inputBox">
         
      </div>
      
      <!-- PayPal Button -->
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



<button id="placeOrder">See Your Order</button>

<?php include 'components/footer.php'; ?>

<!-- Order Summary Sidebar -->
<section class="order-summary-sidebar" style="display: none;"> 
    <button id="closeSidebar" class="close-btn">X</button> <!-- Close button -->
    <h3>Order Summary</h3>
    <div class="order-summary-details">
        <h4>Customer Information</h4>
        <p><strong>Name:</strong> <?= htmlspecialchars($name); ?></p>
        <p><strong>Number:</strong> <?= htmlspecialchars($number); ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($email); ?></p>
        <p><strong>Address:</strong> <?= htmlspecialchars($address); ?></p>

        <h4>Order Summary</h4>
        <?php
        // Fetching cart items for order summary display
        $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $select_cart->execute([$user_id]);

        if ($select_cart->rowCount() > 0):
            while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="order-item">
                    <img src="images/<?= htmlspecialchars($fetch_cart['image']); ?>" alt="<?= htmlspecialchars($fetch_cart['name']); ?>" style="width: 80px; height: auto;">
                    <p><?= htmlspecialchars($fetch_cart['name']); ?> <span>(<?= '₱' . htmlspecialchars($fetch_cart['price']) . ' x ' . htmlspecialchars($fetch_cart['quantity']); ?>)</span></p>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>

        <div class="order-total">
    <p><strong><i class="fas fa-shipping-fast"></i> Shipping Fee:</strong> ₱100</p>
    <p><strong><i class="fas fa-tags"></i> Discount:</strong> ₱<?= htmlspecialchars($discount); ?></p>
    <p><strong>Grand Total (including shipping and discount):</strong> ₱<?= htmlspecialchars($grand_total); ?></p>
</div>
<button id="confirmCheckout">Confirm Checkout</button>


<style>

   /* Notification Overlay */
.notification-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    backdrop-filter: blur(5px);
}

.notification-message {
    background: #28a745; /* Green for success */
    padding: 30px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.5);
    color: #fff;
    max-width: 400px;
    animation: fadeIn 0.5s ease-in-out;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.notification-message p {
    margin: 0 0 15px;
    font-size: 1.2em;
    line-height: 1.5;
}

.notification-message button {
    padding: 10px 20px;
    border: none;
    background: #007bff;
    color: #fff;
    cursor: pointer;
    border-radius: 5px;
    font-size: 1em;
    transition: background 0.3s ease, transform 0.3s ease;
}

.notification-message button:hover {
    background: #0056b3;
    transform: translateY(-3px);
}

/* Checkout Form Styles */
.checkout-orders {
    max-width: 800px;
    margin: 50px auto;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 10px;
    background-color: #f9f9f9;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.checkout-orders h3 {
    margin-bottom: 20px;
    font-size: 1.8em;
    color: #333;
}

.display-orders {
    margin-bottom: 20px;
}

.order-item {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.order-item img {
    width: 80px;
    height: auto;
    border-radius: 5px;
    margin-right: 15px;
}

.order-item p {
    margin: 0;
    font-size: 1em;
}

.grand-total {
    font-size: 1.3em;
    font-weight: bold;
    color: #000;
    margin-top: 10px;
}

/* Input Box Styles */
.inputBox {
    margin-bottom: 15px;
}

.inputBox span {
    display: block;
    font-size: 1em;
    margin-bottom: 5px;
    color: #555;
}

.inputBox .box {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1em;
    background-color: #fff;
}

/* Buttons */
.btn {
    display: inline-block;
    padding: 12px 25px;
    background-color: #007BFF;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s, transform 0.3s;
    font-size: 1em;
    text-align: center;
    cursor: pointer;
}

.btn:hover {
    background-color: #0056b3;
    transform: translateY(-2px);
}

.disabled {
    background-color: #aaa;
    cursor: not-allowed;
}

#placeOrder {
    display: block;
    margin: 20px auto;
    padding: 12px 25px;
    background-color: #28a745;
    color: #fff;
    border: none;
    border-radius: 5px;
    font-size: 1.2em;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.3s;
}

#placeOrder:hover {
    background-color: #218838;
    transform: translateY(-2px);
}

/* Order Summary Sidebar */
.order-summary-sidebar {
    position: fixed;
    top: 0;
    right: 0;
    width: 350px;
    height: 100%;
    background: #fff;
    padding: 20px;
    box-shadow: -2px 0 8px rgba(0, 0, 0, 0.1);
    overflow-y: auto;
    z-index: 1000;
    border-left: 2px solid #ddd;
}

.order-summary-sidebar h3 {
    margin-bottom: 20px;
    font-size: 1.5em;
    color: #333;
}

.order-summary-details h4 {
    margin-bottom: 10px;
    font-size: 1.2em;
    color: #666;
}

.order-summary-details p {
    margin: 5px 0;
    font-size: 1em;
    color: #444;
}

.order-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
}

.order-item img {
    width: 60px;
    height: auto;
    border-radius: 5px;
    margin-right: 10px;
}

.order-total {
    margin-top: 20px;
    font-size: 1.2em;
    font-weight: bold;
    color: #000;
}

#confirmCheckout {
    margin-top: 20px;
    padding: 12px 25px;
    border: none;
    background: #007bff;
    color: #fff;
    cursor: pointer;
    border-radius: 5px;
    font-size: 1.1em;
    transition: background-color 0.3s, transform 0.3s;
    width: 100%;
}

#confirmCheckout:hover {
    background: #0056b3;
    transform: translateY(-2px);
}

/* Close button for sidebar */
.close-btn {
    background: transparent;
    border: none;
    font-size: 1.5em;
    cursor: pointer;
    position: absolute;
    top: 10px;
    right: 15px;
    color: #333;
    transition: color 0.3s;
}

.close-btn:hover {
    color: #ff0000;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

#placeOrder {
    display: block;           /* Makes the button take up the full width available */
    margin: 20px auto;        /* Centers the button horizontally and adds margin on top and bottom */
    padding: 10px 20px;       /* Adds padding for better appearance */
    background-color: #007BFF; /* Set a background color */
    color: #fff;              /* Set the text color */
    border: none;             /* Remove the border */
    border-radius: 5px;       /* Rounded corners */
    font-size: 16px;          /* Set font size */
    cursor: pointer;          /* Set cursor to pointer */
    text-align: center;       /* Center text inside the button */
}

#placeOrder:hover {
    background-color: gray; /* Darker shade on hover for better UX */
}

/* Order Summary Sidebar */
.order-summary-sidebar {
    position: fixed;
    top: 0;
    right: 0;
    width: 400px;
    height: 70%;
    background: white;
    padding: 20px;
    box-shadow: -2px 0 8px rgba(0, 0, 0, 0.2);
    overflow-y: auto;
    z-index: 1000;
}

.order-summary-sidebar h3 {
    margin-bottom: 20px;
    font-size: 2em;
    align-text: center;
    
}

.order-summary-details h4 {
    margin-bottom: 15px;
    font-size: 1.7em;
    align-text: center;
}

.order-summary-details p {
    margin: 5px 0;
    font-size: 1.5em;
}

.order-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
}

.order-item img {
    border-radius: 8px;
    margin-right: 10px;
}

.order-total {
    margin-top: 20px;
    font-size: 1.3em;
    font-weight: bold;
}

#confirmCheckout {
    margin-top: 20px;
    padding: 15px 30px;
    border: none;
    background: #28a745;
    color: #fff;
    cursor: pointer;
    border-radius: 10px;
    font-size: 1.2em;
    transition: background 0.3s ease, transform 0.3s ease;
    width: 100%;
}

#confirmCheckout:hover {
    background: #218838;
    transform: translateY(-3px);
}

/* Close button for sidebar */
.close-btn {
    background: transparent;
    border: none;
    font-size: 1.5em;
    cursor: pointer;
    position: absolute;
    top: 10px;
    right: 15px;
    color: #333;
    transition: color 0.3s;
}

.close-btn:hover {
    color: #ff0000;
}
</style>

<script>
document.getElementById('placeOrder').addEventListener('click', function() {
    // Show the order summary sidebar when Place Order is clicked
    document.querySelector('.order-summary-sidebar').style.display = 'block';
});

// Close the order summary sidebar when the close button is clicked
document.getElementById('closeSidebar').addEventListener('click', function() {
    document.querySelector('.order-summary-sidebar').style.display = 'none';
});

// Confirm checkout process
document.getElementById('confirmCheckout').addEventListener('click', function() {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "confirm_order.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            alert("Order placed successfully!");
            window.location.href = "order_success.php";
        }
    };
    xhr.send();
});
</script>





<script src="js/script.js"></script>


</body>
</html>




