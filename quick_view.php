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

<section class="quick-view">

   <h1 class="heading">Quick view</h1>

   <?php
     $pid = $_GET['pid'];
     $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?"); 
     $select_products->execute([$pid]);
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      <div class="row">
         <div class="image-container">
            <div class="main-image">
               <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
            </div>
            <div class="sub-image">
               <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
               <img src="uploaded_img/<?= $fetch_product['image_02']; ?>" alt="">
               <img src="uploaded_img/<?= $fetch_product['image_03']; ?>" alt="">
            </div>
         </div>
         <div class="content">
            <div class="name"><?= $fetch_product['name']; ?></div>
            <div class="flex">
               <div class="price"><span>Nrs.</span><?= $fetch_product['price']; ?><span>/-</span></div>
               <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
            </div>
            <div class="details"><?= $fetch_product['details']; ?></div>
            <div class="flex-btn">
               <input type="submit" value="add to cart" class="btn" name="add_to_cart">
               <input class="option-btn" type="submit" name="add_to_wishlist" value="add to wishlist">
            </div>
         </div>
      </div>
   </form>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
    <title>Review & Rating System in PHP & Mysql using Ajax</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    
</head>
<body>




<style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-image: linear-gradient(to top, #e6e9f0 0%, #eef1f5 100%);
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .progress-label-left {
            float: left;
            margin-right: 0.5em;
            line-height: 1em;
        }
        .progress-label-right {
            float: right;
            margin-left: 0.3em;
            line-height: 1em;
        }
        .star-light {
            color:#e9ecef;
        }

        .row {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
    </style>

<!DOCTYPE html>
<html>
<head>
    <title>Product Rating</title>
    <!-- Include Bootstrap CSS and FontAwesome -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
</head>
<body>
    <div class="alert alert-primary" role="alert">
        <h1 class="text-center mt-2 mb-2">Product Rating</h1>
    </div>
    <div class="container">
   
        <!-- Product Card with data-product-id -->
        <div class="card" data-product-id="<?= $fetch_product['id']; ?>"> <!-- Replace '123' with your actual product ID -->
         
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3 text-center">
                        <button type="button" name="add_review" class="btn btn-primary form-control mt-3 add_review_btn">Rate/Review This Product</button>
                    </div>
                    <div class="col-sm-4 text-center">
                        <h1 class="text-warning mt-4 mb-4">
                            <b><span class="average_rating">0.0</span> / 5</b>
                        </h1>
                        <div class="mb-3">
                            <i class="fas fa-star star-light mr-1 main_star"></i>
                            <i class="fas fa-star star-light mr-1 main_star"></i>
                            <i class="fas fa-star star-light mr-1 main_star"></i>
                            <i class="fas fa-star star-light mr-1 main_star"></i>
                            <i class="fas fa-star star-light mr-1 main_star"></i>
                        </div>
                        <h3><span class="total_review">0</span> Review</h3>
                    </div>
                    <div class="col-sm-4">
                        <!-- Progress Bars for Each Star Rating -->
                        <p>
                            <div class="progress-label-left"><b>5</b> <i class="fas fa-star text-warning"></i></div>
                            <div class="progress-label-right">(<span class="total_five_star_review">0</span>)</div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="five_star_progress"></div>
                            </div>
                        </p>
                        <p>
                            <div class="progress-label-left"><b>4</b> <i class="fas fa-star text-warning"></i></div>
                            <div class="progress-label-right">(<span class="total_four_star_review">0</span>)</div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="four_star_progress"></div>
                            </div>               
                        </p>
                        <p>
                            <div class="progress-label-left"><b>3</b> <i class="fas fa-star text-warning"></i></div>
                            <div class="progress-label-right">(<span class="total_three_star_review">0</span>)</div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="three_star_progress"></div>
                            </div>               
                        </p>
                        <p>
                            <div class="progress-label-left"><b>2</b> <i class="fas fa-star text-warning"></i></div>
                            <div class="progress-label-right">(<span class="total_two_star_review">0</span>)</div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="two_star_progress"></div>
                            </div>               
                        </p>
                        <p>
                            <div class="progress-label-left"><b>1</b> <i class="fas fa-star text-warning"></i></div>
                            <div class="progress-label-right">(<span class="total_one_star_review">0</span>)</div>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="one_star_progress"></div>
                            </div>               
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <h3 class="mt-3 ml-4">Product Reviews:</h3>
        <div class="mt-3" id="review_content">
            <!-- Reviews will be loaded here -->
        </div>
    </div>

    <!-- Review Modal -->
    <div id="review_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel" aria-hidden="true">
      	<div class="modal-dialog" role="document">
        	<div class="modal-content">
    	      	<div class="modal-header">
    	        	<h5 class="modal-title" id="reviewModalLabel">Submit Review</h5>
    	        	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
    	          		<span aria-hidden="true">&times;</span>
    	        	</button>
    	      	</div>
    	      	<div class="modal-body">
    	      		<h4 class="text-center mt-2 mb-4">
    	        		<i class="fas fa-star star-light submit_star mr-1" data-rating="1"></i>
                        <i class="fas fa-star star-light submit_star mr-1" data-rating="2"></i>
                        <i class="fas fa-star star-light submit_star mr-1" data-rating="3"></i>
                        <i class="fas fa-star star-light submit_star mr-1" data-rating="4"></i>
                        <i class="fas fa-star star-light submit_star mr-1" data-rating="5"></i>
    	        	</h4>
    	        	<div class="form-group">
                        <label for="user_name">Your Name:</label>
    	        		<input type="text" name="user_name" id="user_name" class="form-control" placeholder="Enter Your Name" />
    	        	</div>
    	        	<div class="form-group">
                        <label for="user_review">Comment:</label>
    	        		<textarea name="user_review" id="user_review" class="form-control" placeholder="Type Review Here"></textarea>
    	        	</div>
    	        	<div class="form-group text-center mt-4">
    	        		<button type="button" class="btn btn-primary" id="save_review">Submit</button>
    	        	</div>
    	      	</div>
        	</div>
      	</div>
    </div>

    <!-- Include jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
    $(document).ready(function(){
    var rating_data = 0;
    var product_id = $('.card').data('product-id'); // Retrieve the product ID

    // Open the review modal
    $('.add_review_btn').click(function(){
        $('#review_modal').modal('show');
    });

    // Highlight stars on mouse enter
    $(document).on('mouseenter', '.submit_star', function(){
        var rating = $(this).data('rating');
        reset_background();
        for(var count = 1; count <= rating; count++) {
            $('.submit_star[data-rating="' + count + '"]').addClass('text-warning');
        }
    });

    // Reset stars on mouse leave
    function reset_background() {
        $('.submit_star').each(function(){
            $(this).removeClass('text-warning');
        });
    }

    // Handle mouse leave for stars
    $(document).on('mouseleave', '.submit_star', function(){
        reset_background();
        for(var count = 1; count <= rating_data; count++) {
            $('.submit_star[data-rating="' + count + '"]').addClass('text-warning');
        }
    });

    // Set rating data on click
    $(document).on('click', '.submit_star', function(){
        rating_data = $(this).data('rating');
    });

    // Save review
    $('#save_review').click(function(){
        var user_name = $('#user_name').val().trim();
        var user_review = $('#user_review').val().trim();

        if(user_name === '' || user_review === '' || rating_data === 0) {
            alert("Please fill all fields and select a rating.");
            return false;
        } else {
            $.ajax({
                url: "submit_rating.php",
                method: "POST",
                data: {
                    action: 'submit_review',
                    product_id: product_id, // Include product ID
                    rating_data: rating_data, 
                    user_name: user_name, 
                    user_review: user_review
                },
                success: function(response) {
                    $('#review_modal').modal('hide');
                    load_rating_data();
                    alert(response);
                },
                error: function() {
                    alert("An error occurred while submitting your review. Please try again.");
                }
            });
        }
    });

    // Load rating data on page load
    load_rating_data();

    function load_rating_data() {
        $.ajax({
            url: "submit_rating.php",
            method: "POST",
            data: {
                action: 'load_data',
                product_id: product_id // Include product ID
            },
            dataType: "JSON",
            success: function(data) {
                $('.average_rating').text(data.average_rating);
                $('.total_review').text(data.total_review);

                // Update main stars
                $('.main_star').each(function(index){
                    if(Math.ceil(data.average_rating) > index){
                        $(this).addClass('text-warning').removeClass('star-light');
                    } else {
                        $(this).removeClass('text-warning').addClass('star-light');
                    }
                });

                // Update star counts
                $('.total_five_star_review').text(data.five_star_review);
                $('.total_four_star_review').text(data.four_star_review);
                $('.total_three_star_review').text(data.three_star_review);
                $('.total_two_star_review').text(data.two_star_review);
                $('.total_one_star_review').text(data.one_star_review);

                // Update progress bars
                if(data.total_review > 0){
                    $('#five_star_progress').css('width', (data.five_star_review / data.total_review) * 100 + '%');
                    $('#four_star_progress').css('width', (data.four_star_review / data.total_review) * 100 + '%');
                    $('#three_star_progress').css('width', (data.three_star_review / data.total_review) * 100 + '%');
                    $('#two_star_progress').css('width', (data.two_star_review / data.total_review) * 100 + '%');
                    $('#one_star_progress').css('width', (data.one_star_review / data.total_review) * 100 + '%');
                } else {
                    // If no reviews, set all progress bars to 0%
                    $('.progress-bar').css('width', '0%');
                }

                // Display reviews
                if(data.review_data.length > 0) {
                    var html = '';
                    for(var count = 0; count < data.review_data.length; count++) {
                        html += '<div class="row mb-3">';
                        html += '<div class="col-sm-1"><div class="rounded-circle bg-danger text-white pt-2 pb-2"><h3 class="text-center">' + data.review_data[count].user_name.charAt(0).toUpperCase() + '</h3></div></div>';
                        html += '<div class="col-sm-11">';
                        html += '<div class="card">';
                        html += '<div class="card-header"><b>' + data.review_data[count].user_name + '</b></div>';
                        html += '<div class="card-body">';
                        
                        for(var star = 1; star <= 5; star++) {
                            if(data.review_data[count].rating >= star){
                                html += '<i class="fas fa-star text-warning mr-1"></i>';
                            } else {
                                html += '<i class="fas fa-star star-light mr-1"></i>';
                            }
                        }

                        html += '<br />';
                        html += data.review_data[count].review ;
                        html += '</div>';
                        html += '<div class="card-footer text-right">On ' + data.review_data[count].datetime + '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                    }
                    $('#review_content').html(html);
                } else {
                    $('#review_content').html('<p class="text-center">No reviews yet.</p>');
                }
            },
            error: function() {
                alert("An error occurred while loading reviews. Please try again.");
            }
        });
    }
});
    </script>

    <!-- Optional PHP Code -->
    <?php
    // It seems like this is intended to close a PHP conditional
    /*
    }
    } else {
        echo '<p class="empty">No products added yet!</p>';
    }
    ?>
    */
    ?>
</body>
</html>







</script>
   <?php
      }
   }else{
      echo '<p class="empty">no products added yet!</p>';
   }
   ?>

</section>



</html>









<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>