<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('config.php'); 

// Redirect to home if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$orders = [];

// Fetch orders for this specific user. 
// Note: Adjust 'total_amount', 'status', and 'created_at' if your database columns have different names.
$order_query = "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY id DESC";
$result = mysqli_query($conn, $order_query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $orders[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders | BioPharma</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .glass-nav { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); }
        
        .profile-dropdown { 
            display: none; 
            transform-origin: top right;
            transition: all 0.2s ease;
            z-index: 100;
        }
        .profile-dropdown.show { 
            display: block; 
            animation: slideIn 0.2s ease-out;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(10px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .order-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .order-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -20px rgba(5, 150, 105, 0.2); }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <header class="glass-nav border-b border-slate-200 sticky top-0 z-40 px-8 py-4 flex justify-between items-center shadow-sm">
        <a href="index.php" class="flex items-center gap-3 hover:opacity-80 transition">
            <div class="bg-emerald-600 p-2 rounded-lg text-white shadow-lg shadow-emerald-100">
                <i class="fas fa-leaf text-sm"></i>
            </div>
            <h1 class="text-xl font-extrabold text-slate-800 tracking-tight">BioPharma</h1>
        </a>

        <div class="flex items-center gap-6">
            <div class="flex items-center gap-5">
                <a href="index.php" class="text-sm font-bold text-slate-600 hover:text-emerald-600 transition">Dashboard</a>
                <a href="offers.php" class="text-sm font-bold text-slate-600 hover:text-emerald-600 transition">Offers</a>

                <a href="cart.php" class="relative flex items-center justify-center w-10 h-10 bg-[#eefaf5] rounded-xl group hover:shadow-md transition-all">
                    <i class="fas fa-shopping-cart text-[#059669] text-lg"></i>
                    <span class="absolute -top-1.5 -right-1.5 bg-[#f84464] text-white text-[10px] font-bold h-5 w-5 rounded-full flex items-center justify-center border-2 border-white shadow-sm">
                        <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : '0'; ?>
                    </span>
                </a>

                <div class="relative z-50">
                    <button onclick="toggleDropdown(event)" id="profileBtn" class="h-12 w-12 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center font-bold shadow-xl border-2 border-white transition-transform hover:scale-105">
                        <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                    </button>
                    
                    <div id="profileDropdown" class="profile-dropdown absolute right-0 top-[110%] w-72 bg-white/95 backdrop-blur-2xl shadow-[0_30px_60px_-15px_rgba(0,0,0,0.15)] rounded-[2rem] p-3 text-slate-800 border border-slate-100">
                        <div class="flex items-center gap-4 px-4 py-4 border-b border-slate-100/50 mb-2 bg-gradient-to-br from-slate-50 to-emerald-50/30 rounded-2xl">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg font-black shadow-inner">
                                <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                            </div>
                            <div class="flex-1">
                                <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest leading-none mb-1">Account</p>
                                <div class="flex items-center justify-between">
                                    <p class="text-base font-extrabold text-slate-800 truncate"><?php echo $_SESSION['username']; ?></p>
                                    <a href="edit_profile.php" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition uppercase tracking-wider">Edit</a>
                                </div>
                            </div>
                        </div>
                        <div class="py-1 space-y-1">
                            <a href="orders.php" class="block px-5 py-3 bg-emerald-50/80 rounded-xl text-emerald-800 font-bold text-sm transition-all">Order History</a>
                            <a href="logout.php" class="block px-5 py-3 hover:bg-rose-50 rounded-xl text-slate-600 hover:text-rose-600 font-bold text-sm transition-all hover:translate-x-1">Sign Out</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto p-6 py-12 flex-grow w-full">
        <div class="mb-12">
            <h2 class="text-4xl font-black text-slate-900 tracking-tight">Your <span class="text-emerald-600">Orders</span></h2>
            <p class="text-slate-400 font-medium mt-1 uppercase text-[10px] tracking-[0.2em]">Manage your healthcare history</p>
        </div>

        <?php if (empty($orders)): ?>
            <!-- Empty State if no orders exist -->
            <div class="bg-white rounded-[3rem] p-16 text-center border border-slate-100 shadow-sm mb-12">
                <div class="w-32 h-32 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-8 shadow-inner">
                    <i class="fas fa-box-open text-5xl text-slate-300"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 mb-3 tracking-tight">No orders yet</h3>
                <p class="text-slate-500 font-medium mb-8 max-w-sm mx-auto">Looks like you haven't placed any medicine or health product orders yet.</p>
                <a href="medicines.php" class="inline-block bg-gradient-to-r from-emerald-600 to-teal-500 text-white px-10 py-4 rounded-2xl font-extrabold hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 shadow-emerald-200 shadow-lg">Start Shopping</a>
            </div>
        <?php else: ?>
            <!-- Dynamic Order List -->
            <div class="space-y-6 mb-12">
                <?php foreach ($orders as $order): ?>
                    <div class="order-card bg-white p-6 sm:p-8 rounded-[2rem] border border-slate-100 shadow-sm flex flex-col md:flex-row justify-between md:items-center gap-6 cursor-pointer">
                        <div class="flex items-start gap-5">
                            <div class="w-16 h-16 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-[1.5rem] flex items-center justify-center text-emerald-600 shadow-inner shrink-0 border border-emerald-100/50">
                                <i class="fas fa-prescription-bottle-alt text-2xl"></i>
                            </div>
                            <div>
                                <div class="flex flex-wrap items-center gap-3 mb-1.5">
                                    <h4 class="font-extrabold text-slate-800 text-xl">Order #<?php echo $order['id']; ?></h4>
                                    
                                    <?php 
                                        // Status Color Logic
                                        $status = isset($order['status']) ? $order['status'] : 'Pending';
                                        $statusColor = 'bg-amber-100 text-amber-700 border-amber-200'; // Default
                                        
                                        if(strtolower($status) == 'delivered') {
                                            $statusColor = 'bg-emerald-100 text-emerald-700 border-emerald-200';
                                        } elseif(strtolower($status) == 'cancelled') {
                                            $statusColor = 'bg-rose-100 text-rose-700 border-rose-200';
                                        }
                                    ?>
                                    <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border <?php echo $statusColor; ?>">
                                        <?php echo $status; ?>
                                    </span>
                                </div>
                                <p class="text-sm font-bold text-slate-400 flex items-center gap-2">
                                    <i class="far fa-calendar-alt"></i> 
                                    <?php echo isset($order['created_at']) ? date('F d, Y', strtotime($order['created_at'])) : 'Recent'; ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between md:justify-end gap-8 w-full md:w-auto border-t md:border-none border-slate-100 pt-5 md:pt-0">
                            <div class="text-left md:text-right">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Amount</p>
                                <p class="text-2xl font-black text-slate-800 tracking-tight">
                                    ₹<?php echo isset($order['total_amount']) ? number_format($order['total_amount'], 2) : '0.00'; ?>
                                </p>
                            </div>
                            <button class="bg-slate-50 hover:bg-emerald-600 text-slate-600 hover:text-white w-12 h-12 rounded-2xl flex items-center justify-center transition-all shadow-sm group">
                                <i class="fas fa-chevron-right group-hover:translate-x-1 transition-transform"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Support Banner -->
        <div class="p-10 bg-white rounded-[3rem] border border-dashed border-slate-300 text-center relative overflow-hidden group hover:border-emerald-300 transition-colors">
            <div class="absolute -top-20 -right-20 w-40 h-40 bg-emerald-50 rounded-full blur-3xl pointer-events-none group-hover:bg-emerald-100 transition-colors"></div>
            <div class="relative z-10">
                <h4 class="font-extrabold text-slate-800 text-lg mb-2">Something wrong with an order?</h4>
                <p class="text-sm text-slate-500 mb-6 font-semibold">Contact our 24/7 support for returns or cancellations.</p>
                <button class="bg-slate-100 text-slate-600 px-8 py-3.5 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition-all shadow-sm">Get Help</button>
            </div>
        </div>
    </main>

    <footer class="p-12 text-center text-slate-400 text-[10px] font-black uppercase tracking-[0.3em] border-t border-slate-200 bg-white mt-auto">
        &copy; 2026 BioPharma Premium Division
    </footer>

    <script>
        function toggleDropdown(event) {
            event.stopPropagation();
            document.getElementById('profileDropdown').classList.toggle('show');
        }

        window.onclick = function(event) {
            const dropdown = document.getElementById('profileDropdown');
            const btn = document.getElementById('profileBtn');
            if (dropdown && dropdown.classList.contains('show')) {
                if (!btn.contains(event.target) && !dropdown.contains(event.target)) {
                    dropdown.classList.remove('show');
                }
            }
        }
    </script>
</body>
</html>