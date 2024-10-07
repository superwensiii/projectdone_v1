<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM users WHERE email = ?");
   $select_user->execute([$email,]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $message[] = 'email already exists!';
   }else{
      if($pass != $cpass){
         $message[] = 'confirm password not matched!';
      }else{
         $insert_user = $conn->prepare("INSERT INTO `users`(name, email, password) VALUES(?,?,?)");
         $insert_user->execute([$name, $email, $cpass]);
         $message[] = 'registered successfully, login now please!';
      }
   }

}

?>


 
<!-- HTML code for displaying the message -->
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

<!-- CSS styles -->
<style>
.notification-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    backdrop-filter: blur(5px);
}

.notification-message {
    background: linear-gradient(135deg, #6a11cb, #2575fc);
    padding: 40px;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.4);
    color: #fff;
    max-width: 90%;
    width: 500px;
    animation: fadeIn 0.5s ease-in-out;
}

.notification-message p {
    margin: 0 0 25px;
    font-size: 4em;
    font-weight: 600;
    line-height: 1.4;
}

.notification-message button {
    padding: 15px 30px;
    border: none;
    background: #ff416c;
    color: #fff;
    cursor: pointer;
    border-radius: 10px;
    font-size: 1.2em;
    transition: background 0.3s ease, transform 0.3s ease;
}

.notification-message button:hover {
    background: #ff4b2b;
    transform: translateY(-3px);
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

</style>

<!-- JavaScript to close the notification -->
<script>
function closeNotification() {
    document.querySelector('.notification-overlay').style.display = 'none';
}
</script>

<?php

include 'components/connect.php';



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

   $check_cart = $conn->prepare("SELECT * FROM cart WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if($check_cart->rowCount() > 0){

      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

      $delete_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
      $delete_cart->execute([$user_id]);

      $message[] = 'order placed successfully!';
   }else{
      $message[] = 'your cart is empty';
   }

}

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

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<style>

   /* Form Wrapper */
section.checkout-orders {
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    font-family: 'Arial', sans-serif;
}

/* Form Heading */
section.checkout-orders h3 {
    text-align: center;
    font-size: 28px;
    font-weight: 600;
    color: #333;
    margin-bottom: 20px;
}

/* Form Flex Wrapper */
section.checkout-orders .flex {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
}

/* Input Container */
section.checkout-orders .inputBox {
    flex: 1 1 calc(50% - 20px);
    margin-bottom: 15px;
}

/* Input Label */
section.checkout-orders .inputBox span {
    display: block;
    font-weight: 500;
    color: #555;
    margin-bottom: 5px;
}

/* Input Field */
section.checkout-orders .inputBox .box {
    width: 100%;
    padding: 10px;
    border-radius: 4px;
    border: 1px solid #ddd;
    background: #f9f9f9;
    font-size: 16px;
    outline: none;
    transition: border-color 0.3s ease;
}

/* Input Field Focus */
section.checkout-orders .inputBox .box:focus {
    border-color: #2575fc;
}

/* Submit Button */
section.checkout-orders .btn {
    display: inline-block;
    padding: 12px;
    border: none;
    border-radius: 4px;
    background: #2575fc;
    color: #fff;
    font-size: 18px;
    cursor: pointer;
    transition: background 0.3s ease;
    margin-top: 10px;
}

/* Submit Button Hover */
section.checkout-orders .btn:hover {
    background: #1b5bbf;
}

/* OTP Fields */
section.checkout-orders .inputBox input[type="submit"] {
    margin-top: 10px;
    padding: 8px 20px;
    background: #ff416c;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background 0.3s ease;
}

section.checkout-orders .inputBox input[type="submit"]:hover {
    background: #ff1e4b;
}

/* Login Button */
section.checkout-orders .option-btn {
    display: inline-block;
    text-align: center;
    width: 100%;
    padding: 10px;
    border-radius: 4px;
    background: #2575fc;
    color: #fff;
    font-size: 16px;
    text-decoration: none;
    margin-top: 20px;
    transition: background 0.3s ease;
}

/* Login Button Hover */
section.checkout-orders .option-btn:hover {
    background: #1b5bbf;
}

/* "Already have an account?" Text */
section.checkout-orders .inputBox p {
    margin-top: 10px;
    color: #333;
    font-size: 16px;
}

/* Responsive Design */
@media (max-width: 768px) {
    section.checkout-orders .flex {
        flex-direction: column;
    }

    section.checkout-orders .inputBox {
        flex: 1 1 100%;
    }
}
</style>



<section class="checkout-orders">
   <div class="register-container">
      <!-- Input GIF Image -->
      <div class="register-image">
         <img src="images/register.gif" alt="Register Image">
      </div>

      <!-- Registration Form -->
      <form action="" method="POST">
         <h3>Register</h3>

         <div class="flex">
            <div class="inputBox">
               <span>Customer Name:</span>
               <input type="text" name="name" placeholder="Enter your name" class="box" maxlength="20" required>
            </div>
            <div class="inputBox">
               <span>Your Number:</span>
               <input type="number" name="number" placeholder="Enter your number" class="box" min="0" max="99999999999" onkeypress="if(this.value.length == 10) return false;" required>
               
            </div>
            
            <div class="inputBox">
               <span>Your Email:</span>
               <input type="email" name="email" placeholder="Enter your email" class="box" maxlength="50" required>
            </div>
            <div class="inputBox">
               <span>Block and Lot:</span>
               <input type="text" name="block_lot" placeholder="Enter block and lot" class="box" maxlength="50" required>
            </div>
            <div class="inputBox">
               <span>Address:</span>
               <input type="text" name="flat" placeholder="Enter address" class="box" maxlength="50" required>
            </div>
            <div class="inputBox">
               <span>City:</span>
               <input type="text" name="city" placeholder="Enter city" class="box" maxlength="50" required>
            </div>
            <div class="inputBox">
               <span>Country:</span>
               <input type="text" name="country" placeholder="Nepal" class="box" maxlength="50" required>
            </div>
            <div class="inputBox">
               <span>ZIP CODE:</span>
               <input type="number" name="pin_code" placeholder="e.g. 1400" min="0" max="999999" onkeypress="if(this.value.length == 6) return false;" class="box" required>
            </div>
            <div class="inputBox">
               <span>Enter Password:</span>
               <input type="password" name="pass" placeholder="Enter your password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')" required>
            </div>
            <div class="inputBox">
               <span>Confirm Password:</span>
               <input type="password" name="cpass" placeholder="Confirm your password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')" required>
            </div>
         </div>

         <input type="submit" value="Register Now" class="btn" name="submit">
         <div class="inputBox">
            <p>Already have an account?</p>
         </div>
         <a href="user_login.php" class="option-btn">Login Now.</a>
      </form>
   </div>
</section>









<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>