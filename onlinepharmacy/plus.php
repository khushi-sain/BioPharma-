<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('config.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BioPharma PLUS | Premium Healthcare</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; color: #1e293b; }
        .glass-nav { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); }
        .plus-gradient { background: linear-gradient(135deg, #059669 0%, #10b981 100%); }
        .animate-float { animation: float 6s ease-in-out infinite; }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .glow-effect { box-shadow: 0 0 50px -10px rgba(16, 185, 129, 0.4); }
        
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

    

    <header class="py-20 px-6 text-center">
        <div class="inline-block px-4 py-1.5 rounded-full bg-emerald-50 border border-emerald-100 text-[#059669] text-[10px] font-black uppercase tracking-widest mb-6">Subscription</div>
        <h1 class="text-6xl font-extrabold mb-6 tracking-tight text-slate-900">Unlock <span class="text-[#059669]">Premium</span> Care</h1>
        <p class="text-slate-500 max-w-xl mx-auto font-medium text-lg">Experience healthcare without boundaries. Free deliveries, exclusive discounts, and priority support.</p>
    </header>

    <main class="max-w-6xl mx-auto px-6 pb-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-24">
            <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 text-center shadow-sm hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-truck-fast text-[#059669] text-3xl"></i>
                </div>
                <h3 class="font-bold text-xl mb-2 text-slate-800 tracking-tight">Free Delivery</h3>
                <p class="text-slate-500 text-sm font-medium leading-relaxed">No shipping costs on any order size, ever.</p>
            </div>

            <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 text-center shadow-sm hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-microscope text-[#059669] text-3xl"></i>
                </div>
                <h3 class="font-bold text-xl mb-2 text-slate-800 tracking-tight">Lab Benefits</h3>
                <p class="text-slate-500 text-sm font-medium leading-relaxed">Free home sample collection for all lab tests.</p>
            </div>

            <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 text-center shadow-sm hover:shadow-xl transition-all duration-300">
                <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-headset text-[#059669] text-3xl"></i>
                </div>
                <h3 class="font-bold text-xl mb-2 text-slate-800 tracking-tight">Priority Help</h3>
                <p class="text-slate-500 text-sm font-medium leading-relaxed">Skip the queue with dedicated support lines.</p>
            </div>
        </div>

        <section class="relative bg-slate-900 rounded-[4rem] p-10 md:p-20 overflow-hidden glow-effect">
            <div class="absolute top-0 left-0 w-96 h-96 bg-emerald-500/20 rounded-full blur-[100px] -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-emerald-400/10 rounded-full blur-[100px] translate-x-1/3 translate-y-1/3"></div>

            <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-16">
                <div class="flex-1 text-center lg:text-left text-left">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500/10 border border-emerald-500/20 rounded-full text-[#10b981] text-[10px] font-black uppercase tracking-widest mb-8">
                        <i class="fas fa-crown"></i> Premium Access
                    </div>
                    <h2 class="text-5xl font-black text-white leading-tight mb-6 tracking-tight text-left">Ready to join the <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-emerald-200">PLUS Family?</span></h2>
                    <p class="text-slate-400 text-lg font-medium mb-10 max-w-lg">Get started today and save an average of ₹8,000 annually on your healthcare expenses.</p>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-6 justify-center lg:justify-start">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center border border-white/10">
                                <i class="fas fa-shield-check text-emerald-400"></i>
                            </div>
                            <div class="text-left">
                                <p class="text-white font-bold text-sm leading-none">Safe & Secure</p>
                                <p class="text-slate-500 text-[10px] font-bold uppercase mt-1">Encrypted Payment</p>
                            </div>
                        </div>
                        <div class="w-px h-10 bg-slate-800 hidden sm:block"></div>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center border border-white/10">
                                <i class="fas fa-undo text-emerald-400"></i>
                            </div>
                            <div class="text-left">
                                <p class="text-white font-bold text-sm leading-none">Cancel Anytime</p>
                                <p class="text-slate-500 text-[10px] font-bold uppercase mt-1">No Questions Asked</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full max-w-sm">
                    <div class="animate-float">
                        <div class="bg-white rounded-[3rem] p-2 shadow-2xl relative">
                            <div class="absolute -top-5 left-1/2 -translate-x-1/2 bg-[#fbbf24] text-slate-900 px-6 py-2 rounded-full font-black text-[10px] uppercase tracking-widest shadow-lg">
                                Best Value
                            </div>
                            
                            <div class="bg-slate-50 rounded-[2.5rem] p-10 border border-white">
                                <div class="text-center mb-10">
                                    <h4 class="text-slate-500 font-black text-[10px] uppercase tracking-widest mb-4">Annual Membership</h4>
                                    <div class="flex items-center justify-center gap-1">
                                        <span class="text-2xl font-bold text-slate-400 mb-4">₹</span>
                                        <span class="text-7xl font-black text-slate-900 tracking-tighter">499</span>
                                        <span class="text-lg font-bold text-slate-400 mt-6">/yr</span>
                                    </div>
                                    <p class="text-emerald-600 font-bold text-sm mt-4 italic">Only ₹41 per month!</p>
                                </div>

                                <ul class="space-y-4 mb-10 text-left">
                                    <li class="flex items-center gap-3 text-slate-700 font-bold text-sm">
                                        <i class="fas fa-check-circle text-emerald-500 text-lg"></i> Unlimited Free Delivery
                                    </li>
                                    <li class="flex items-center gap-3 text-slate-700 font-bold text-sm">
                                        <i class="fas fa-check-circle text-emerald-500 text-lg"></i> Extra 5% Medicines Off
                                    </li>
                                    <li class="flex items-center gap-3 text-slate-700 font-bold text-sm">
                                        <i class="fas fa-check-circle text-emerald-500 text-lg"></i> Priority Customer Care
                                    </li>
                                </ul>

                                <button class="w-full plus-gradient text-white py-5 rounded-[2rem] font-black uppercase text-xs tracking-[0.2em] shadow-xl shadow-emerald-200 hover:scale-105 transition-all active:scale-95">
                                    Become a Member
                                </button>
                                <p class="text-center text-[9px] text-slate-400 font-bold uppercase mt-6 tracking-widest">T&C Apply • Instant Activation</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <div class="mt-24 mb-12">
            <h2 class="text-3xl font-black text-slate-800 text-center mb-12">Compare <span class="text-[#059669]">Plans</span></h2>
            <div class="bg-white rounded-[2.5rem] border border-slate-100 overflow-hidden shadow-sm max-w-4xl mx-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="p-8 text-slate-400 font-black text-[10px] uppercase tracking-widest">Features</th>
                            <th class="p-8 text-slate-800 font-black text-sm">Standard</th>
                            <th class="p-8 text-[#059669] font-black text-sm">PLUS</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm font-semibold text-slate-600">
                        <tr class="border-b border-slate-50">
                            <td class="p-8">Delivery Fees</td>
                            <td class="p-8">₹40 - ₹100</td>
                            <td class="p-8 font-bold text-emerald-600">Always FREE</td>
                        </tr>
                        <tr class="border-b border-slate-50">
                            <td class="p-8">Extra Discount</td>
                            <td class="p-8">None</td>
                            <td class="p-8 font-bold text-emerald-600">Flat 5% Extra</td>
                        </tr>
                        <tr>
                            <td class="p-8">Home Sample Collection</td>
                            <td class="p-8">₹200</td>
                            <td class="p-8 font-bold text-emerald-600">Always FREE</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <footer class="py-12 text-center text-slate-400 border-t border-slate-100 bg-white">
        <p class="text-[10px] font-black uppercase tracking-widest">&copy; 2026 BioPharma Premium Division</p>
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