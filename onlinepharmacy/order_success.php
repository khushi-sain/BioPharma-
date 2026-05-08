<?php
session_start();
include('config.php');

// URL se ID uthao
$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Agar order id nahi hai toh home page bhej do
if ($order_id == 0) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success | BioPharma</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-50 h-screen flex items-center justify-center font-['Plus_Jakarta_Sans']">

    <div class="max-w-md w-full bg-white p-10 rounded-[3rem] shadow-2xl border border-slate-100 text-center">
        <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-3xl flex items-center justify-center text-4xl mx-auto mb-6 shadow-lg">
            <i class="fas fa-check-circle"></i>
        </div>
        
        <h2 class="text-3xl font-black text-slate-800 mb-2">Order Placed!</h2>
        <p class="text-slate-500 font-semibold mb-8 text-sm">Your order #<?php echo $order_id; ?> has been received and will be delivered soon.</p>
        
        <div class="space-y-3">
            <a href="index.php" class="block w-full bg-emerald-600 text-white py-4 rounded-2xl font-bold shadow-lg hover:bg-emerald-700 transition transform hover:-translate-y-1">
                Back to Dashboard
            </a>
            <a href="orders.php" class="block w-full bg-slate-100 text-slate-600 py-4 rounded-2xl font-bold hover:bg-slate-200 transition">
                View My Orders
            </a>
        </div>
        
        <p class="mt-8 text-[10px] text-slate-400 font-black uppercase tracking-widest">Thank you for choosing BioPharma</p>
    </div>

</body>
</html>