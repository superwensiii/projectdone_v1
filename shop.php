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
   <title>Shop</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products">

   <h1 class="heading">Latest Products.</h1>

   <div class="box-container">

   <?php
     $select_products = $conn->prepare("SELECT * FROM `products`"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/jpg<?= $fetch_product['image_01']; ?>" alt="">
      <div class="name"><?= $fetch_product['name']; ?></div>
      
      <div class="flex">
         <div class="price"><span>P</span><?= $fetch_product['price']; ?><span>/-</span></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
      
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">no products found!</p>';
   }
   ?>

   
   </div>

   </div>

</section>

<style>

   .h1 {
   text-align: center;
   }
 .latest-product {
    max-width: 1200px;
    margin: 40px auto;
    padding: 20px;
}

/* Product Container */
.product-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* 3 products per row */
    gap: 20px;
}

/* Product Card */
.product-details {
    
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    text-align: center;
    
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

/* Product Image */
.product-image {
    width: 100%;
    height: 200px;
    object-fit: cover;
    margin-bottom: 15px;
    border-radius: 5px;
}

/* Product Price */
.product-price {
    font-size: 1.2rem;
    font-weight: bold;
    color: #444;
    margin: 10px 0;
}

/* Add to Cart Button */
.add-to-cart-btn {
    background-color: #007bff;
    color: #ffffff;
    border: none;
    padding: 10px;
    font-size: 1rem;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s ease;
    margin-top: 15px;
}

.add-to-cart-btn:hover {
    background-color: #0056b3;
}

/* Responsive Design */
@media (max-width: 768px) {
    .product-container {
        grid-template-columns: repeat(2, 1fr); /* 2 items per row on medium screens */
    }
}

@media (max-width: 480px) {
    .product-container {
        grid-template-columns: 1fr; /* 1 item per row on small screens */
    }
}
</style>
<h1> OUR PRODUCT </h1>
<section class="latest-product">
    <div class="product-container">
    <?php
        // Array to store product details
        $products = [
            ['id' => 1, 'name' => 'Product 1', 'price' => 10.00, 'image' => 'images/bankk.png'],
            ['id' => 2, 'name' => 'Product 2', 'price' => 20.00, 'image' => 'images/bankk.png'],
            ['id' => 3, 'name' => 'Product 3', 'price' => 30.00, 'image' => 'images/bankk.png'],
            ['id' => 4, 'name' => 'Product 4', 'price' => 40.00, 'image' => 'images/bankk.png'],
            ['id' => 5, 'name' => 'Product 5', 'price' => 50.00, 'image' => 'images/bankk.png'],
            ['id' => 6, 'name' => 'Product 6', 'price' => 60.00, 'image' => 'images/bankk.png'],
            ['id' => 7, 'name' => 'Product 7', 'price' => 70.00, 'image' => 'images/bankk.png'],
            ['id' => 8, 'name' => 'Product 8', 'price' => 80.00, 'image' => 'images/bankk.png'],
            ['id' => 9, 'name' => 'Product 9', 'price' => 90.00, 'image' => 'images/bankk.png'],
        ];

        foreach ($products as $product) {
            ?>
            <form action="" method="post" class="product-details">
                <input type="hidden" name="pid" value="<?= $product['id']; ?>">
                <input type="hidden" name="name" value="<?= $product['name']; ?>">
                <input type="hidden" name="price" value="<?= $product['price']; ?>">
                <input type="hidden" name="image" value="<?= $product['image']; ?>">
                
                <img src="<?= $product['image']; ?>" alt="<?= $product['name']; ?>" class="product-image">
                <h3><?= $product['name']; ?></h3>
                <p class="product-description">This is an amazing product that offers fantastic features and benefits. Perfect for anyone looking to enhance their experience!</p>
                <p class="product-price">$<?= number_format($product['price'], 2); ?></p>
                <input type="number" name="qty" class="qty" min="1" max="99" value="1">
                <button type="submit" name="add_to_cart" class="add-to-cart-btn">Add to Cart</button>
            </form>
            <?php
        }
        ?>
        <div class="product-details">
            <img src="images/bankk.png" alt="Product 1" class="product-image">
            <h3>Product 1</h3>
            <p class="product-description">This is an amazing product that offers fantastic features and benefits. Perfect for anyone looking to enhance their experience!</p>
            <p class="product-price">$10.00</p>
            <button class="add-to-cart-btn">Add to Cart</button>
            
        </div>
        <div class="product-details">
            <img src="images/fafan.png" alt="Product 2" class="product-image">
            <h3>Product 2</h3>
            <p class="product-description">Experience high quality and durability with Product 2. It's designed to meet your needs and exceed your expectations!</p>
            <p class="product-price">$20.00</p>
            <button class="add-to-cart-btn">Add to Cart</button>
        </div>
        <div class="product-details">
            <img src="images/bankk.png" alt="Product 3" class="product-image">
            <h3>Product 3</h3>
            <p class="product-description">Product 3 is a revolutionary item that combines innovation and style. Don't miss out on this must-have product!</p>
            <p class="product-price">$30.00</p>
            <button class="add-to-cart-btn">Add to Cart</button>
        </div>
    </div>

    <div class="product-container">
        <div class="product-details">
            <img src="images/bankk.png" alt="Product 4" class="product-image">
            <h3>Product 4</h3>
            <p class="product-description">Discover the versatility of Product 4, designed for everyday use. It's the perfect addition to your collection!</p>
            <p class="product-price">$40.00</p>
            <button class="add-to-cart-btn">Add to Cart</button>
        </div>
        <div class="product-details">
            <img src="images/bankk.png" alt="Product 5" class="product-image">
            <h3>Product 5</h3>
            <p class="product-description">With Product 5, enjoy premium quality at an affordable price. It's perfect for gifting or personal use!</p>
            <p class="product-price">$50.00</p>
            <button class="add-to-cart-btn">Add to Cart</button>
        </div>
        <div class="product-details">
            <img src="images/bankk.png" alt="Product 6" class="product-image">
            <h3>Product 6</h3>
            <p class="product-description">Product 6 stands out with its unique features and elegant design. A must-have for anyone who appreciates quality!</p>
            <p class="product-price">$60.00</p>
            <button class="add-to-cart-btn">Add to Cart</button>
        </div>
    </div>

    <div class="product-container">
        <div class="product-details">
            <img src="images/bankk.png" alt="Product 7" class="product-image">
            <h3>Product 7</h3>
            <p class="product-description">Elevate your style with Product 7, a perfect blend of function and aesthetics. Ideal for any occasion!</p>
            <p class="product-price">$70.00</p>
            <button class="add-to-cart-btn">Add to Cart</button>
        </div>
        <div class="product-details">
            <img src="images/bankk.png" alt="Product 8" class="product-image">
            <h3>Product 8</h3>
            <p class="product-description">Product 8 offers unmatched performance and reliability. Experience the difference in quality today!</p>
            <p class="product-price">$80.00</p>
            <button class="add-to-cart-btn">Add to Cart</button>
        </div>
        <div class="product-details">
            <img src="images/bankk.png" alt="Product 9" class="product-image">
            <h3>Product 9</h3>
            <p class="product-description">Last but not least, Product 9 combines tradition and modernity, making it a great choice for everyone!</p>
            <p class="product-price">$90.00</p>
            <button class="add-to-cart-btn">Add to Cart</button>
        </div>
    </div>
</section>













<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>