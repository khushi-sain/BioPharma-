<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('config.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness & Nutrition | BioPharma</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; overflow-x: hidden; }
        .glass-nav { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .product-card:hover img { transform: scale(1.08); transition: 0.4s; }
        
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
<body class="min-h-screen">

    <header class="glass-nav border-b border-slate-200 sticky top-0 z-50 px-8 py-4 flex justify-between items-center shadow-sm">
        <div class="flex items-center gap-3">
            <div class="bg-emerald-600 p-2 rounded-lg text-white shadow-lg shadow-emerald-100">
                <i class="fas fa-leaf text-sm"></i>
            </div>
            <a href="index.php" class="text-xl font-extrabold text-slate-800 tracking-tight">BioPharma</a>
        </div>
        
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-5">
                <a href="offers.php" class="text-sm font-bold text-slate-600 hover:text-emerald-600 transition">Offers</a>

                <a href="cart.php" class="relative flex items-center justify-center w-10 h-10 bg-[#eefaf5] rounded-xl group hover:shadow-md transition-all">
                    <i class="fas fa-shopping-cart text-[#059669] text-lg"></i>
                    <span class="absolute -top-1.5 -right-1.5 bg-[#f84464] text-white text-[10px] font-bold h-5 w-5 rounded-full flex items-center justify-center border-2 border-white shadow-sm">
                        <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : '0'; ?>
                    </span>
                </a>

                <?php if(!isset($_SESSION['user_id'])): ?>
                    <button onclick="toggleModal()" class="flex items-center gap-2 text-white font-bold text-sm bg-emerald-600 px-6 py-2.5 rounded-2xl hover:bg-emerald-700 transition shadow-sm">
                        <i class="far fa-user-circle text-lg"></i> Login
                    </button>
                <?php else: ?>
                    <div class="relative z-50">
                        <button onclick="toggleDropdown(event)" id="profileBtn" class="h-12 w-12 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center font-bold shadow-xl border-2 border-white transition-transform hover:scale-105">
                            <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                        </button>
                        
                        <div id="profileDropdown" class="profile-dropdown absolute right-0 top-[115%] w-72 bg-white/95 backdrop-blur-2xl shadow-[0_30px_60px_-15px_rgba(0,0,0,0.15)] rounded-[2rem] p-3 text-slate-800 border border-slate-100">
                            <div class="flex items-center gap-4 px-4 py-4 border-b border-slate-100/50 mb-2 bg-gradient-to-br from-slate-50 to-emerald-50/30 rounded-2xl">
                                <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg font-black shadow-inner">
                                    <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                                </div>
                                <div class="flex-1 text-left">
                                    <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest leading-none mb-1">Account</p>
                                    <div class="flex items-center justify-between">
                                        <p class="text-base font-extrabold text-slate-800 truncate"><?php echo $_SESSION['username']; ?></p>
                                        <a href="edit_profile.php" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition uppercase tracking-wider">Edit</a>
                                    </div>
                                </div>
                            </div>
                            <div class="py-1 space-y-1 text-left">
                                <a href="orders.php" class="block px-5 py-3 hover:bg-emerald-50/80 rounded-xl text-slate-600 hover:text-emerald-800 font-bold text-sm transition-all hover:translate-x-1">Order History</a>
                                <a href="addresses.php" class="block px-5 py-3 hover:bg-emerald-50/80 rounded-xl text-slate-600 hover:text-emerald-800 font-bold text-sm transition-all hover:translate-x-1">My Addresses</a>
                                <a href="reviews.php" class="block px-5 py-3 hover:bg-emerald-50/80 rounded-xl text-slate-600 hover:text-emerald-800 font-bold text-sm transition-all hover:translate-x-1">Customer Reviews</a>
                                <a href="help.php" class="block px-5 py-3 hover:bg-emerald-50/80 rounded-xl text-slate-600 hover:text-emerald-800 font-bold text-sm transition-all hover:translate-x-1">Help & Support</a>
                                <a href="logout.php" class="block px-5 py-3 hover:bg-rose-50 rounded-xl text-slate-600 hover:text-rose-600 font-bold text-sm transition-all hover:translate-x-1">Sign Out</a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="max-w-6xl mx-auto p-6 py-12">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-16 gap-6">
            <div>
                <h1 class="text-5xl font-black text-slate-900 tracking-tight">Fitness & <span class="text-emerald-600">Diet</span></h1>
                <p class="text-slate-500 mt-2 font-medium italic">Premium nutrition for a stronger, healthier version of you.</p>
            </div>
            <div class="flex gap-4">
                <a href="index.php" class="bg-white border border-slate-200 text-slate-600 px-6 py-3 rounded-2xl font-bold hover:bg-slate-50 transition">Dashboard</a>
                <a href="medicines.php" class="bg-slate-900 text-white px-6 py-3 rounded-2xl font-bold hover:bg-emerald-600 transition shadow-lg">Shop Now</a>
            </div>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-20">
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-xl shadow-slate-200/50 text-center hover:-translate-y-2 transition-transform">
                <div class="w-20 h-20 bg-emerald-50 text-4xl flex items-center justify-center rounded-3xl mx-auto mb-6">🥤</div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Supplements</h3>
                <p class="text-slate-400 text-sm mb-6 leading-relaxed">Boost your muscle recovery with high-quality Whey and BCAA.</p>
                <button class="w-full py-4 bg-emerald-600 text-white rounded-2xl font-bold hover:bg-emerald-700 transition shadow-lg shadow-emerald-100 uppercase text-[10px] tracking-widest">Explore</button>
            </div>
            
            <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-xl shadow-slate-200/50 text-center hover:-translate-y-2 transition-transform">
                <div class="w-20 h-20 bg-emerald-50 text-4xl flex items-center justify-center rounded-3xl mx-auto mb-6">🥗</div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Weight Loss</h3>
                <p class="text-slate-400 text-sm mb-6 leading-relaxed">Scientifically backed diet plans for sustainable weight loss.</p>
                <button class="w-full py-4 bg-emerald-600 text-white rounded-2xl font-bold hover:bg-emerald-700 transition shadow-lg shadow-emerald-100 uppercase text-[10px] tracking-widest">Get Plan</button>
            </div>

            <div class="bg-emerald-600 rounded-[2.5rem] p-8 text-white flex flex-col justify-center items-center text-center shadow-2xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2 group-hover:scale-150 transition-transform duration-700"></div>
                <i class="fas fa-apple-alt text-5xl mb-6"></i>
                <h3 class="text-2xl font-black mb-2 tracking-tight">Nutri-Consult</h3>
                <p class="opacity-90 mb-8 font-medium">Speak to a certified sports nutritionist for personalized advice.</p>
                <button class="bg-white text-emerald-600 px-10 py-3 rounded-2xl font-bold hover:scale-105 transition active:scale-95 shadow-xl">Book Call</button>
            </div>
        </div>

        <div class="mb-20">
            <div class="flex justify-between items-end mb-10">
                <div>
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight">Featured <span class="text-emerald-600">Supplements</span></h2>
                    <p class="text-slate-500 font-medium">Tested for purity, designed for performance.</p>
                </div>
                <div class="flex gap-3">
                    <button onclick="slideSupps('left')" class="w-12 h-12 bg-white border border-slate-200 rounded-full flex items-center justify-center text-slate-600 hover:bg-emerald-600 hover:text-white transition shadow-sm">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button onclick="slideSupps('right')" class="w-12 h-12 bg-white border border-slate-200 rounded-full flex items-center justify-center text-slate-600 hover:bg-emerald-600 hover:text-white transition shadow-sm">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>

            <div id="suppContainer" class="flex gap-6 overflow-x-auto scroll-smooth hide-scrollbar pb-4">
                <?php 
                $products = [
                    ['Whey Protein Isolate', '₹2,499', 'wheyprotein.png'],
                    ['Creatine Monohydrate', '₹899', 'ceratine.png'],
                    ['Pre-Workout Blast', '₹1,299', 'preworkoutblast.png'],
                    ['Daily Multivitamin', '₹599', 'dailymultivita.png']
                ];
                foreach($products as $p): ?>
                <div class="product-card min-w-[280px] bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-xl transition-all group flex flex-col justify-between">
                    <div>
                        <div class="h-48 bg-slate-50 rounded-2xl flex items-center justify-center mb-6 overflow-hidden">
                            <img src="images/<?php echo rawurlencode($p[2]); ?>" class="h-32 object-contain transition-transform duration-500" alt="<?php echo $p[0]; ?>">
                        </div>
                        <h4 class="font-bold text-slate-800 text-sm mb-2 leading-tight"><?php echo $p[0]; ?></h4>
                    </div>
                    <div class="flex justify-between items-center mt-4">
                        <span class="text-xl font-black text-slate-900"><?php echo $p[1]; ?></span>
                        <button class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-600 hover:text-white transition active:scale-90"><i class="fas fa-plus"></i></button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <footer class="py-10 text-center border-t border-slate-100">
        <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">&copy; 2026 BioPharma Fitness Division</p>
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

        function slideSupps(direction) {
            const container = document.getElementById('suppContainer');
            const scrollAmount = 300;
            if (direction === 'left') { container.scrollLeft -= scrollAmount; } 
            else { container.scrollLeft += scrollAmount; }
        }
    </script>
</body>
</html>