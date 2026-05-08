<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('config.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exclusive Offers | BioPharma</title>
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
        .offer-card:hover .copy-btn { opacity: 1; }
    </style>
</head>
<body class="min-h-screen flex flex-col">

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
            <div class="bg-[#059669] p-2 rounded-lg text-white shadow-lg">
                <i class="fas fa-leaf text-sm"></i>
            </div>
            <a href="index.php" class="text-xl font-extrabold text-slate-800 tracking-tight">BioPharma</a>
        </div>
        
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-5">
                <a href="offers.php" class="text-sm font-bold text-[#059669] transition">Offers</a>

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
                            <div class="flex items-center gap-4 px-4 py-4 border-b border-slate-100/50 mb-2 bg-gradient-to-br from-slate-50 to-emerald-50/30 rounded-2xl text-left">
                                <div class="w-12 h-12 rounded-2xl bg-[#059669] text-white flex items-center justify-center text-xl font-black shadow-inner">
                                    <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                                </div>
                                <div class="flex-1">
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

    <main class="max-w-6xl mx-auto p-6 py-12 flex-grow w-full">
        <div class="text-center mb-16">
            <span class="bg-emerald-50 text-[#059669] px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border border-emerald-100">BioPharma Savings</span>
            <h1 class="text-5xl font-black text-slate-900 mt-6 tracking-tight">Deals of the <span class="text-[#059669]">Day</span></h1>
            <p class="text-slate-500 mt-4 font-medium text-lg italic">Grab the best discounts on healthcare essentials before they expire.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php 
            $offers = [
                ['Flat 25% Off', 'On first 3 medicine orders', 'FIRST25', 'bg-rose-50', 'text-rose-600'],
                ['Extra ₹100 Off', 'On orders above ₹999', 'HEALTH100', 'bg-blue-50', 'text-blue-600'],
                ['Free Delivery', 'Across all orders this week', 'FREESHIP', 'bg-emerald-50', 'text-emerald-600'],
                ['BioPharma PLUS', 'Get 5% extra discount', 'PLUSUSER', 'bg-slate-900', 'text-white'],
                ['Lab Test Deal', 'Up to 60% off on full body checkups', 'CHECKUP60', 'bg-amber-50', 'text-amber-600'],
                ['Vitamin Special', 'Buy 1 Get 1 on Multivitamins', 'B1G1VIT', 'bg-purple-50', 'text-purple-600']
            ];
            foreach($offers as $o): ?>
            <div class="offer-card bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm hover:shadow-xl transition-all relative overflow-hidden group">
                <div class="flex flex-col h-full">
                    <div class="mb-6">
                        <h3 class="text-2xl font-black text-slate-800 leading-tight mb-2"><?php echo $o[0]; ?></h3>
                        <p class="text-slate-400 text-sm font-medium italic"><?php echo $o[1]; ?></p>
                    </div>
                    
                    <div class="mt-auto">
                        <div class="flex items-center justify-between bg-slate-50 border border-dashed border-slate-200 p-4 rounded-2xl relative overflow-hidden">
                            <span class="font-black text-slate-800 tracking-widest uppercase text-sm"><?php echo $o[2]; ?></span>
                            <button onclick="copyCode('<?php echo $o[2]; ?>', this)" class="bg-slate-900 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-[#059669] transition-all transform active:scale-90">Copy</button>
                        </div>
                    </div>
                </div>
                <div class="absolute -top-10 -right-10 w-24 h-24 <?php echo $o[3]; ?> opacity-10 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>

    <footer class="py-12 text-center text-slate-400 border-t border-slate-50 bg-white">
        <p class="text-[10px] font-black uppercase tracking-widest">&copy; 2026 BioPharma Discount Center</p>
    </footer>

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