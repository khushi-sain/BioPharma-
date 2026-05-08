<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
    die("Error: Please login first.");
}

$user_id = $_SESSION['user_id'];
$total = mysqli_real_escape_string($conn, $_POST['final_price'] ?? $_POST['grand_total'] ?? 0);
$address = mysqli_real_escape_string($conn, $_POST['final_address'] ?? $_POST['address'] ?? 'No Address');
$category = mysqli_real_escape_string($conn, $_POST['category'] ?? 'Medicine');

// 1. Order Table mein Entry
$sql = "INSERT INTO orders (user_id, total_price, address, status) VALUES ('$user_id', '$total', '$address', 'Pending')";

if (mysqli_query($conn, $sql)) {
    $order_id = mysqli_insert_id($conn);

    // 2. Agar Cart mein items hain (Cart flow)
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $id => $qty) {
            $item_qty = is_array($qty) ? $qty['quantity'] : $qty;
            $med = mysqli_fetch_assoc(mysqli_query($conn, "SELECT price FROM medicines WHERE id=" . intval($id)));
            $price = $med['price'];

            mysqli_query($conn, "INSERT INTO order_items (order_id, medicine_id, quantity, price_at_time) VALUES ('$order_id', '$id', '$item_qty', '$price')");
            mysqli_query($conn, "UPDATE medicines SET stock_qty = stock_qty - $item_qty WHERE id=" . intval($id));
        }
        unset($_SESSION['cart']); // Order ke baad cart saaf
    } 
    // 3. Agar Direct Checkout hai (Bina cart ke)
    else {
        // Yahan aap product details insert kar sakte ho jo checkout page se aayi hain
        // Example: mysqli_query($conn, "INSERT INTO order_items ...");
    }
// success.php ki jagah order_success.php likho
header("Location: order_success.php?id=$order_id");
exit();
   
} else {
    echo "Query Error: " . mysqli_error($conn);
}
?>