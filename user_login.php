<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $_SESSION['user_id'] = $row['id'];
      header('location:home.php');
   }else{
      $message[] = 'incorrect username or password!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <script src="https://www.google.com/recaptcha/api.js" async defer></script>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

   
<?php include 'components/user_header.php'; ?>


<style>
   .form-container {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 20px;
      margin: 40px auto;
      max-width: 900px;
   }

   .form-container img {
      max-width: 400px;
      width: 100%;
      height: auto;
      border-radius: 10px;
      flex-shrink: 0;
   }

   .form-container form {
      max-width: 400px;
      width: 100%;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
      background-color: #fff;
      display: flex;
      flex-direction: column;
   }

   .form-header {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 10px;
      margin-bottom: 20px;
   }

   .form-header img {
      width: 50px; /* Adjust the size of the logo */
      height: 50px;
   }

   .form-header h3 {
      margin: 0;
      font-size: 24px;
   }

   .input-container {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
      position: relative;
   }

   .input-container img {
      position: absolute;
      left: 10px;
      width: 20px;
      height: 20px;
   }

   .box {
      width: 100%;
      padding: 10px 10px 10px 40px;
      margin: 10px 0;
      border-radius: 5px;
      border: 1px solid #ccc;
   }

   .btn {
      width: 100%;
      padding: 10px;
      background-color: #28a745;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
   }

   .btn:hover {
      background-color: #218838;
   }

   .option-btn {
      color: #007bff;
      text-decoration: none;
   }

   .option-btn:hover {
      text-decoration: underline;
   }

   .g-recaptcha {
      margin-bottom: 20px;
   }

   
</style>

<section class="form-container">

   <!-- Image on the left -->
    
  

   <!-- Form on the right -->
   <form action="user_login.php" method="post">
      
      <!-- Logo and Login Heading -->
      <div class="form-header">
        
      <img src="images/gone.png" alt="Logo"> 
      <!-- Logo next to the title -->
         <h1> LOG IN NOW! </h1>
      </div>

      <!-- Email field with icon -->
      <div class="input-container">
         <input type="email" name="email" required placeholder="Enter your email" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      </div>

      <!-- Password field with icon -->
      <div class="input-container">
         <input type="password" name="pass" required placeholder="Enter your password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      </div>

      <!-- Captcha -->
      <div class="g-recaptcha" data-sitekey="6Lci_U4qAAAAADpnsZ7iksRyKzezJJp2E5jsn_nf"></div>

      <!-- Login Button -->
      <input type="submit" value="Login Now" class="btn" name="submit">

      <p>Don't have an account?</p>
      <a href="user_register.php" class="option-btn">Register Now.</a>

      <?php
      
     

     
     
     require_once __DIR__ . '/vendor/autoload.php';






// Init configuration
$clientID = '745050248523-lntke8lat215dr1raid80fn35idhrjsa.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost/projectdone_v1/user_login.php';

// Create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

// Authenticate code from Google OAuth Flow
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    
    // Error handling for token fetching
    if (array_key_exists('error', $token)) {
        die("Error fetching access token: " . htmlspecialchars($token['error']));
    }

    $client->setAccessToken($token['access_token']);

    // Get profile info
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    $email = $google_account_info->email;
    $name = $google_account_info->name;
    
    

    // Now you can use this profile info to create an account on your website and log the user in.
    echo "Name: $name<br>Email: $email";
} else {
    echo "<a href='" . htmlspecialchars($client->createAuthUrl()) . "'>Google Login</a>";
}

?>

   </form>

</section>



<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>