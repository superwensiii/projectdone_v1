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
      header('location:index.php');
   }else{
      $message[] = 'Incorrect username or password!';
   }

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

<style>



   /* Notification Message Styling */
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
      background: #fff;
      padding: 20px;
      border-radius: 10px;
      text-align: center;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
      color: #333;
      max-width: 400px;
      width: 90%;
   }

   .notification-message p {
      margin-bottom: 15px;
      font-size: 1.2em;
      font-weight: 500;
   }

   .notification-message button {
      padding: 10px 20px;
      border: none;
      background: #007bff;
      color: #fff;
      cursor: pointer;
      border-radius: 5px;
      transition: background 0.3s;
   }

   .notification-message button:hover {
      background: #0056b3;
   }

   /* Form Styling */
   .form-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      margin: 40px auto;
      padding: 20px;
      max-width: 400px;
      width: 100%;
      background-color: #f9f9f9;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
   }

   .form-header {
      text-align: center;
      margin-bottom: 20px;
   }

   .form-header img {
      width: 60px;
      margin-bottom: 10px;
   }

   .form-header h1 {
      font-size: 1.8em;
      color: #333;
   }
   body {
    background-color: #f4f4f4;
    font-family: Arial, sans-serif;
}

.login-container {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
    max-width: 900px;
    margin: 50px auto;
    padding: 20px;
    background-color: #ffffff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

.login-image img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
}

.login-form {
    max-width: 400px;
    padding: 20px;
}

.input-container {
    margin-bottom: 20px;
}

h1 {
    text-align: center;
    font-size: 24px;
    margin-bottom: 20px;
    color: #007bff;
}

.box {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.btn {
    display: block;
    width: 100%;
    padding: 10px;
    border: none;
    background-color: #007bff;
    color: #fff;
    font-size: 16px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #0056b3;
}

.option-btn {
    color: #007bff;
    text-decoration: none;
    font-weight: bold;
}

.option-btn:hover {
    text-decoration: underline;
}

p {
    text-align: center;
    margin-top: 15px;
}

.g-recaptcha {
    margin: 15px 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .login-container {
        flex-direction: column;
        padding: 20px;
    }

    .login-image {
        display: none; /* Hide image on smaller screens */
    }
}


   
</style>

<!-- JavaScript to close the notification -->
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
   <title>Login</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <script src="https://www.google.com/recaptcha/api.js" async defer></script>
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="login-section">
   <div class="login-container">
      <div class="login-image">
         <img src="images/haler.gif" alt="Login Image">
      </div>
      <form action="user_login.php" method="post" class="login-form">
         <div class="input-container">
            <h1>LOGIN NOW</h1>
            <input type="email" name="email" required placeholder="Enter your email" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         </div>
         <div class="input-container">
            <input type="password" name="pass" required placeholder="Enter your password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         </div>
         <div class="g-recaptcha" data-sitekey="6Lci_U4qAAAAADpnsZ7iksRyKzezJJp2E5jsn_nf"></div>
         <input type="submit" value="Login Now" class="btn" name="submit">
         <p>Don't have an account? <a href="user_register.php" class="option-btn">Register Now</a></p>
         <style>
            .google-login-btn {
    display: inline-flex;
    align-items: center;
    padding: 10px 20px;
    border: 1px solid #4285F4;
    background-color: black;
    color: #ffffff;
    text-decoration: none;
    font-size: 16px;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s, border-color 0.3s;
}

.google-login-btn img {
    width: 20px;
    height: 20px;
    margin-right: 10px;
    position: center;
}

.google-login-btn:hover {
    background-color: #357AE8;
    border-color: #357AE8;
}

</style>


<?php
// Google OAuth Login
require_once __DIR__ . '/vendor/autoload.php';
$clientID = '745050248523-lntke8lat215dr1raid80fn35idhrjsa.apps.googleusercontent.com';
$clientSecret = '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';
$redirectUri = 'http://localhost/projectdone_v1/user_login.php';

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (array_key_exists('error', $token)) {
        die("Error fetching access token: " . htmlspecialchars($token['error']));
    }
    $client->setAccessToken($token['access_token']);
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    $email = $google_account_info->email;
    $name = $google_account_info->name;
    echo "Name: $name<br>Email: $email";
} else {
   
    echo "<a href='" . htmlspecialchars($client->createAuthUrl()) . "' class='google-login-btn'><img src='images/gugle.png' alt='Google Icon'>Continue with Google</a>";
}
?>

      </form>
   </div>
</section>

</body>
</html>


      
   </form>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
