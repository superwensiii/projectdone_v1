<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['send'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $msg = $_POST['msg'];
   $msg = filter_var($msg, FILTER_SANITIZE_STRING);

   $select_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $select_message->execute([$name, $email, $number, $msg]);

   if($select_message->rowCount() > 0){
      $message[] = 'already sent message!';
   }else{

      $insert_message = $conn->prepare("INSERT INTO `messages`(user_id, name, email, number, message) VALUES(?,?,?,?,?)");
      $insert_message->execute([$user_id, $name, $email, $number, $msg]);

      $message[] = 'sent message successfully!';

   }

}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contact</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="contact">

   <div class="contact-container">
      <!-- Contact Form -->
      <form action="" method="post" class="contact-form">
         <h3>Get in touch.</h3>
         <input type="text" name="name" placeholder="Enter your name:" required maxlength="20" class="box">
         <input type="email" name="email" placeholder="Enter your email:" required maxlength="50" class="box">
         <input type="number" name="number" min="0" max="9999999999" placeholder="Contact No.:" required onkeypress="if(this.value.length == 10) return false;" class="box">
         <textarea name="msg" class="box" placeholder="Enter your thoughts:" cols="30" rows="10"></textarea>
         <input type="submit" value="send message" name="send" class="btn">
      </form>

      <!-- Google Map Embed and Company Info -->
      <div class="map-container">
         <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d61781.67860036959!2d121.03997314514878!3d14.578841261254976!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397c7dc88f7b24f%3A0x4a592b2b4b34fd89!2sPasig%2C%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1726764350860!5m2!1sen!2sph" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

         <!-- Company Information -->
         <div class="company-info">
            <h3>Company Information</h3>
            <p><strong>Main Branch:</strong> Pasig City</p>
            <p><strong>Call us:</strong> 09312321321</p>
            <p><strong>Email:</strong> <a href="mailto:greatwallph@gmail.com">greatwallph@gmail.com</a></p>
            <p><strong>Facebook:</strong> <a href="https://facebook.com/hahahahaha" target="_blank">hahahahaha</a></p>
         </div>
      </div>
   </div>

</section>

<style>
   .contact-container {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      flex-wrap: wrap;
   }

   .contact-form {
      flex: 1;
      max-width: 50%; /* Adjust as needed */
      margin-right: 20px;
   }

   .map-container {
      flex: 1;
      max-width: 400px; /* Adjust as needed */
   }

   .box {
      width: 100%;
      padding: 10px;
      margin-bottom: 10px;
   }

   .btn {
      padding: 10px 20px;
      background-color: #333;
      color: #fff;
      border: none;
      cursor: pointer;
   }

   .btn:hover {
      background-color: #555;
   }

   .company-info {
      margin-top: 20px;
      background-color: #f9f9f9;
      padding: 15px;
      border-radius: 5px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
   }

   .company-info h3 {
      margin-bottom: 10px;
      font-size: 18px;
      font-weight: bold;
   }

   .company-info p {
      margin: 5px 0;
      font-size: 14px;
   }

   .company-info a {
      color: #007bff;
      text-decoration: none;
   }

   .company-info a:hover {
      text-decoration: underline;
   }
</style>





<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>