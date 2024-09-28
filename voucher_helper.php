<?php
// voucher_helper.php

function check_and_award_voucher($conn, $customer_id) {
    $three_months_ago = date("Y-m-d H:i:s", strtotime('-3 months'));

    // Count purchases in the last 3 months
    $select_purchases = $conn->prepare("SELECT COUNT(*) as purchase_count FROM `customer_purchase_history` WHERE customer_id = ? AND purchase_date >= ?");
    $select_purchases->execute([$customer_id, $three_months_ago]);
    $result = $select_purchases->fetch(PDO::FETCH_ASSOC);

    // If customer has made 5 or more purchases in the last 3 months, award a voucher
    if ($result['purchase_count'] >= 5) {
        // Generate a voucher code
        $voucher_code = "VOUCHER" . uniqid();
        $discount_amount = 10.00;  // Example: a $10 voucher
        $issue_date = date("Y-m-d H:i:s");
        $expiration_date = date("Y-m-d H:i:s", strtotime('+1 month')); // Voucher expires in 1 month

        // Insert voucher into the database
        $insert_voucher = $conn->prepare("INSERT INTO `customer_vouchers` (customer_id, voucher_code, discount_amount, issue_date, expiration_date) VALUES (?, ?, ?, ?, ?)");
        $insert_voucher->execute([$customer_id, $voucher_code, $discount_amount, $issue_date, $expiration_date]);

        echo "<p>Congratulations! You've earned a voucher for your loyalty: $voucher_code (₱$discount_amount off)</p>";
    }
}
?>
