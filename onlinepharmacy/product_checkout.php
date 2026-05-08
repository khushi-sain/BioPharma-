<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];


// --- YAHAN PAR ADD KARNA HAI ---
$category = isset($_GET['category']) ? htmlspecialchars($_GET['category']) : "General Health";
$base_price = isset($_GET['price']) ? intval($_GET['price']) : 499;
// Default values (In real app, fetch these from DB or GET/POST)
$category = isset($_GET['category']) ? htmlspecialchars($_GET['category']) : "General Health";
$base_price = 499; // Base price for the bundle

// Fetch user's saved address from DB
$saved_address = "";
// Query column name should match Database column name
$addr_query = "SELECT address_line FROM addresses WHERE user_id = $user_id LIMIT 1";
$addr_result = mysqli_query($conn, $addr_query);
if ($addr_result && mysqli_num_rows($addr_result) > 0) {
    $addr_row = mysqli_fetch_assoc($addr_result);
    $saved_address = $addr_row['address_line'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | BioPharma</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 p-6">

    <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8 mt-10">
        
        <!-- Left: Order Details -->
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
            <h2 class="text-2xl font-black mb-6">Confirm <span class="text-emerald-600">Order</span></h2>
            
            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl mb-6">
                <div class="w-16 h-16 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 text-xl">
                    <i class="fas fa-box"></i>
                </div>
                <div>
                    <h4 class="font-bold text-slate-800"><?php echo $category; ?> Bundle</h4>
                    <p class="text-xs text-slate-500">Premium Quality Assured</p>
                </div>
            </div>

            <!-- Quantity Selector -->
            <div class="mb-8">
                <label class="block text-sm font-bold text-slate-700 mb-3">Quantity</label>
                <div class="flex items-center gap-4 bg-slate-100 w-fit p-2 rounded-2xl">
                    <button type="button" onclick="updateQty(-1)" class="w-10 h-10 bg-white rounded-xl shadow-sm hover:text-emerald-600 transition font-bold">-</button>
                    <span id="qtyDisplay" class="font-bold text-lg w-8 text-center">1</span>
                    <button type="button" onclick="updateQty(1)" class="w-10 h-10 bg-white rounded-xl shadow-sm hover:text-emerald-600 transition font-bold">+</button>
                </div>
            </div>

            <!-- Address Section -->
            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-3">Delivery Address</label>
                <textarea id="deliveryAddress" name="address" placeholder="Enter your full address" class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-sm font-medium outline-none focus:ring-2 focus:ring-emerald-500 h-24 transition"><?php echo htmlspecialchars($saved_address); ?></textarea>
            </div>
        </div>

        <!-- Right: Summary & Payment -->
        <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-slate-100 h-fit">
            <h3 class="text-xl font-bold mb-6">Price Details</h3>
            
            <!-- Coupon Option -->
            <div class="flex gap-2 mb-8">
                <input type="text" id="couponCode" placeholder="Enter Coupon (e.g. BIO25)" class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-emerald-500">
                <button onclick="applyCoupon()" class="bg-slate-800 text-white px-6 py-3 rounded-xl font-bold text-xs hover:bg-black transition">Apply</button>
            </div>

            <div class="space-y-4 text-sm font-bold text-slate-600">
                <div class="flex justify-between">
                    <span>Price (<span id="itemCount">1</span> Item)</span>
                    <span id="subtotal">₹<?php echo $base_price; ?></span>
                </div>
                <div class="flex justify-between">
                    <span>Delivery Charges</span>
                    <span class="text-emerald-600">FREE</span>
                </div>
                <div class="flex justify-between text-rose-500">
                    <span>Coupon Discount</span>
                    <span id="discountText">- ₹0</span>
                </div>
                <hr class="border-dashed border-slate-200">
                <div class="flex justify-between text-xl font-black text-slate-800">
                    <span>Total Amount</span>
                    <span id="totalAmount">₹<?php echo $base_price; ?></span>
                </div>
            </div>

            <form action="place_order.php" method="POST" class="mt-8">
                <input type="hidden" name="qty" id="formQty" value="1">
                <input type="hidden" name="final_price" id="formPrice" value="<?php echo $base_price; ?>">
                <input type="hidden" name="category" value="<?php echo $category; ?>">
                <!-- This will sync with the textarea -->
                <input type="hidden" name="final_address" id="formAddress" value="<?php echo htmlspecialchars($saved_address); ?>">
                
                <button type="submit" onclick="syncAddress()" class="w-full bg-emerald-600 text-white py-4 rounded-2xl font-black shadow-lg shadow-emerald-100 hover:bg-emerald-700 hover:-translate-y-1 transition-all duration-300">
                    Place Order Now
                </button>
            </form>
            
            <p class="text-[10px] text-center text-slate-400 mt-4 font-bold uppercase tracking-widest">Secure SSL Encrypted Payment</p>
        </div>
    </div>

    <script>
        let qty = 1;
        let basePrice = <?php echo $base_price; ?>;
        let discountAmount = 0;
        let isCouponApplied = false;

        function updateQty(val) {
            qty = Math.max(1, qty + val);
            document.getElementById('qtyDisplay').innerText = qty;
            document.getElementById('formQty').value = qty;
            document.getElementById('itemCount').innerText = qty;
            
            // Re-calculate discount if coupon was already applied
            if(isCouponApplied) {
                discountAmount = (basePrice * qty) * 0.25;
            }
            
            calculateTotal();
        }

        function applyCoupon() {
            let code = document.getElementById('couponCode').value.trim().toUpperCase();
            if(code === 'BIO25') {
                isCouponApplied = true;
                discountAmount = (basePrice * qty) * 0.25;
                alert('Success: 25% Discount Applied!');
            } else {
                alert('Error: Invalid Coupon Code');
                isCouponApplied = false;
                discountAmount = 0;
            }
            calculateTotal();
        }

        function calculateTotal() {
            let subtotal = basePrice * qty;
            let total = subtotal - discountAmount;
            
            document.getElementById('subtotal').innerText = '₹' + subtotal;
            document.getElementById('discountText').innerText = '- ₹' + Math.round(discountAmount);
            document.getElementById('totalAmount').innerText = '₹' + Math.round(total);
            document.getElementById('formPrice').value = Math.round(total);
        }

        function syncAddress() {
            // Textarea ki value ko hidden field mein daalna takki POST ho sake
            document.getElementById('formAddress').value = document.getElementById('deliveryAddress').value;
        }
    </script>
</body>
</html>