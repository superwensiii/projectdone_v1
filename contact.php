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
   <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Merriweather:wght@300;700&family=Roboto:wght@400;500&display=swap">


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
 body {
    font-family: 'Roboto', sans-serif; /* Use a modern and professional font for the body */
    background-color: #f8f8f8;
    color: #333;
}

h3, h1, h2 {
    font-family: 'Merriweather', serif; /* Use a more formal font for headings */
    color: #333;
    text-align: center;
}

.contact-container {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 20px;
    padding: 40px;
    background-color: #f8f8f8;
}

.contact-form {
    flex: 1;
    min-width: 300px;
    max-width: 45%;
    padding: 20px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    font-size: 16px;
}

.contact-form h3 {
    font-family: 'Merriweather', serif;
    font-size: 24px;
    margin-bottom: 15px;
    color: #2c3e50;
}

.contact-form .box {
    width: 100%;
    padding: 15px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
}

.contact-form .btn {
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    cursor: pointer;
    border-radius: 5px;
    transition: background 0.3s ease;
    font-size: 16px;
}

.contact-form .btn:hover {
    background-color: #0056b3;
}

.map-container {
    flex: 1;
    min-width: 300px;
    max-width: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.map-container iframe {
    width: 100%;
    height: 300px;
    border: 0;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
}

.company-info {
    background-color: #fff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    width: 100%;
    font-size: 16px;
    line-height: 1.6;
    color: #2c3e50;
}

.company-info h3 {
    font-family: 'Merriweather', serif;
    font-size: 22px;
    font-weight: bold;
    color: #333;
    margin-bottom: 15px;
    border-bottom: 2px solid #007bff;
    padding-bottom: 5px;
}

.company-info p {
    margin: 10px 0;
    font-size: 16px;
    color: #555;
}

.company-info a {
    color: #007bff;
    text-decoration: none;
    transition: color 0.3s ease;
    font-weight: 500;
}

.company-info a:hover {
    color: #0056b3;
    text-decoration: underline;
}

/* Responsive Styles */
@media (max-width: 768px) {
    .contact-container {
        flex-direction: column;
    }

    .contact-form, .map-container {
        max-width: 100%;
        margin-bottom: 20px;
    }
}


</style>





<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>