<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('config.php');

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $cart_empty = true;
} else {
    $cart_empty = false;
    $placeholders = implode(',', array_fill(0, count($_SESSION['cart']), '?'));
    $ids = array_keys($_SESSION['cart']);
    
    $stmt = $conn->prepare("SELECT * FROM medicines WHERE id IN ($placeholders)");
    $stmt->bind_param(str_repeat('i', count($ids)), ...$ids);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart | BioPharma</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
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
        .modal { display: none; background: rgba(0,0,0,0.5); }
        .modal.active { display: flex; }
    </style>
</head>
<body class="min-h-screen pb-20">

    <div id="loginModal" class="modal fixed inset-0 z-[110] items-center justify-center p-4">
        <div class="bg-white w-full max-w-md rounded-[2.5rem] p-10 shadow-2xl relative border border-slate-100 text-center">
            <button onclick="toggleModal()" class="absolute right-8 top-8 text-slate-400 hover:text-slate-600">
                <i class="fas fa-times text-xl"></i>
            </button>
            <div class="bg-[#059669] w-14 h-14 rounded-2xl flex items-center justify-center text-white mx-auto mb-4 shadow-lg">
                <i class="fas fa-leaf text-2xl"></i>
            </div>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight mb-8">Login to BioPharma</h2>
            <form action="auth.php" method="POST" class="space-y-4">
                <input type="email" name="email" required placeholder="Email ID" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-[#059669] transition text-sm">
                <input type="password" name="password" required placeholder="Password" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-[#059669] transition text-sm">
                <button type="submit" class="w-full bg-[#059669] text-white py-4 rounded-2xl font-bold shadow-xl mt-4 hover:bg-[#047857] transition">Login</button>
            </form>
        </div>
    </div>

    <header class="glass-nav border-b border-slate-200 sticky top-0 z-50 px-8 py-4 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-3">
            <div class="bg-[#059669] p-2 rounded-lg text-white shadow-lg shadow-emerald-100">
                <i class="fas fa-leaf text-sm"></i>
            </div>
            <a href="index.php" class="text-xl font-extrabold text-slate-800 tracking-tight">BioPharma</a>
        </div>
        
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-5">
                <a href="offers.php" class="text-sm font-bold text-slate-600 hover:text-[#059669] transition">Offers</a>

                <a href="cart.php" class="relative flex items-center justify-center w-10 h-10 bg-[#eefaf5] rounded-xl group hover:shadow-md transition-all">
                    <i class="fas fa-shopping-cart text-[#059669] text-lg"></i>
                    <span class="absolute -top-1.5 -right-1.5 bg-[#f84464] text-white text-[10px] font-bold h-5 w-5 rounded-full flex items-center justify-center border-2 border-white shadow-sm">
                        <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : '0'; ?>
                    </span>
                </a>

                <?php if(!isset($_SESSION['user_id'])): ?>
                    <button onclick="toggleModal()" class="flex items-center gap-2 text-white font-bold text-sm bg-[#059669] px-6 py-2.5 rounded-2xl hover:bg-[#047857] transition shadow-sm">
                        <i class="far fa-user-circle text-lg"></i> Login
                    </button>
                <?php else: ?>
                    <div class="relative z-50">
                        <button onclick="toggleDropdown(event)" id="profileBtn" class="h-12 w-12 rounded-2xl bg-[#059669] text-white flex items-center justify-center font-bold shadow-xl border-2 border-white transition-transform hover:scale-105">
                            <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                        </button>
                        
                        <div id="profileDropdown" class="profile-dropdown absolute right-0 top-[115%] w-72 bg-white/95 backdrop-blur-2xl shadow-[0_30px_60px_-15px_rgba(0,0,0,0.15)] rounded-[2rem] p-3 text-slate-800 border border-slate-100">
                            <div class="flex items-center gap-4 px-4 py-4 border-b border-slate-100/50 mb-2 bg-gradient-to-br from-slate-50 to-emerald-50/30 rounded-2xl">
                                <div class="w-12 h-12 rounded-2xl bg-[#059669] text-white flex items-center justify-center text-xl font-black shadow-inner">
                                    <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                                </div>
                                <div class="flex-1 text-left">
                                    <p class="text-[10px] font-black text-[#059669] uppercase tracking-widest leading-none mb-1">Account</p>
                                    <div class="flex items-center justify-between">
                                        <p class="text-base font-extrabold text-slate-800 truncate"><?php echo $_SESSION['username']; ?></p>
                                        <a href="edit_profile.php" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition uppercase tracking-wider">Edit</a>
                                    </div>
                                </div>
                            </div>
                            <div class="py-1 space-y-1 text-left">
                                <a href="orders.php" class="block px-5 py-3 hover:bg-emerald-50/80 rounded-xl text-slate-600 hover:text-[#059669] font-bold text-sm transition-all hover:translate-x-1">Order History</a>
                                <a href="addresses.php" class="block px-5 py-3 hover:bg-emerald-50/80 rounded-xl text-slate-600 hover:text-[#059669] font-bold text-sm transition-all hover:translate-x-1">My Addresses</a>
                                <a href="reviews.php" class="block px-5 py-3 hover:bg-emerald-50/80 rounded-xl text-slate-600 hover:text-[#059669] font-bold text-sm transition-all hover:translate-x-1">Customer Reviews</a>
                                <a href="help.php" class="block px-5 py-3 hover:bg-emerald-50/80 rounded-xl text-slate-600 hover:text-[#059669] font-bold text-sm transition-all hover:translate-x-1">Help & Support</a>
                                <a href="logout.php" class="block px-5 py-3 hover:bg-rose-50 rounded-xl text-slate-600 hover:text-rose-600 font-bold text-sm transition-all hover:translate-x-1">Sign Out</a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <nav class="bg-white border-b border-slate-200 px-6 py-4 flex justify-between items-center sticky top-[73px] z-40">
        <div class="flex items-center gap-2">
            <a href="medicines.php" class="bg-slate-100 p-2 rounded-xl text-slate-600 hover:bg-emerald-50 hover:text-emerald-600 transition">
                <i class="fas fa-arrow-left"></i>
            </a>
            <span class="text-xl font-bold text-slate-800 ml-2">Review Cart</span>
        </div>
        <div class="text-sm font-bold text-slate-500">
            <?php echo !$cart_empty ? array_sum($_SESSION['cart']) : '0'; ?> Items
        </div>
    </nav>

    <main class="max-w-5xl mx-auto p-6">
        <?php if ($cart_empty): ?>
            <div class="text-center py-20">
                <div class="bg-slate-100 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-shopping-basket text-3xl text-slate-300"></i>
                </div>
                <h2 class="text-2xl font-bold text-slate-800">Your cart is empty</h2>
                <p class="text-slate-500 mt-2">Looks like you haven't added any medicines yet.</p>
                <a href="medicines.php" class="inline-block mt-8 bg-[#059669] text-white px-8 py-3 rounded-2xl font-bold hover:bg-emerald-700 transition shadow-lg shadow-emerald-100">
                    Browse Medicines
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-4 text-left">
                    <?php 
                    $total_bill = 0;
                    while($row = $result->fetch_assoc()): 
                        $id = $row['id'];
                        $qty = $_SESSION['cart'][$id];
                        $subtotal = $row['price'] * $qty;
                        $total_bill += $subtotal;
                    ?>
                    <div class="bg-white rounded-[2rem] p-4 flex items-center gap-6 border border-slate-100 shadow-sm transition hover:shadow-md">
                        <div class="w-24 h-24 bg-slate-50 rounded-2xl flex-shrink-0 p-2">
                            <img src="uploads/<?php echo $row['image']; ?>" class="w-full h-full object-contain" onerror="this.src='https://cdn-icons-png.flaticon.com/512/822/822143.png'">
                        </div>
                        
                        <div class="flex-1">
                            <span class="text-[10px] font-bold text-[#059669] uppercase tracking-widest"><?php echo $row['category']; ?></span>
                            <h3 class="font-bold text-slate-800 text-lg leading-tight"><?php echo $row['name']; ?></h3>
                            <p class="text-slate-400 text-sm mt-1">Unit Price: ₹<?php echo $row['price']; ?></p>
                        </div>

                        <div class="flex flex-col items-end gap-3">
                            <div class="flex items-center bg-slate-900 rounded-xl overflow-hidden shadow-sm">
                                <a href="cart_action.php?id=<?php echo $id; ?>&action=remove" class="px-3 py-2 text-white hover:bg-rose-500 transition-colors">
                                    <i class="fas fa-minus text-[10px]"></i>
                                </a>
                                <span class="px-3 text-white font-bold text-sm"><?php echo $qty; ?></span>
                                <a href="cart_action.php?id=<?php echo $id; ?>&action=add" class="px-3 py-2 text-white hover:bg-[#059669] transition-colors">
                                    <i class="fas fa-plus text-[10px]"></i>
                                </a>
                            </div>
                            <p class="font-black text-slate-900">₹<?php echo number_format($subtotal, 2); ?></p>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>

                <div class="lg:col-span-1 text-left">
                    <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm sticky top-24">
                        <h3 class="text-xl font-bold text-slate-800 mb-6">Bill Summary</h3>
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between text-slate-500 font-medium">
                                <span>Basket Total</span>
                                <span>₹<?php echo number_format($total_bill, 2); ?></span>
                            </div>
                            <div class="flex justify-between text-slate-500 font-medium">
                                <span>Delivery Fee</span>
                                <span class="text-[#059669] font-bold">FREE</span>
                            </div>
                            <div class="border-t border-dashed border-slate-200 pt-4 flex justify-between">
                                <span class="text-lg font-bold text-slate-800">Amount Payable</span>
                                <span class="text-xl font-black text-slate-900">₹<?php echo number_format($total_bill, 2); ?></span>
                            </div>
                        </div>
                        <button onclick="window.location.href='checkout.php'" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-bold text-lg hover:bg-[#059669] transition-all shadow-xl shadow-slate-100">
                            Proceed to Checkout
                        </button>
                        <p class="text-center text-[10px] text-slate-400 mt-4 px-4 leading-relaxed uppercase font-black">
                            Secure Checkout enabled
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <script>
        function toggleModal() { document.getElementById('loginModal').classList.toggle('active'); }
        function toggleDropdown(event) {
            event.stopPropagation();
            document.getElementById('profileDropdown').classList.toggle('show');
        }
        window.onclick = function(event) {
            const dropdown = document.getElementById('profileDropdown');
            if (dropdown && dropdown.classList.contains('show')) {
                if (!event.target.closest('#profileBtn') && !event.target.closest('#profileDropdown')) {
                    dropdown.classList.remove('show');
                }
            }
        }
    </script>
</body>
</html>