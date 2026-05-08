<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Specialty Care | BioPharma</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .glass-nav { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); }
        
        /* Dropdown Animation Logic */
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
    </style>
</head>
<body class="min-h-screen flex flex-col">

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
                    <button onclick="toggleModal()" class="flex items-center gap-2 text-slate-700 font-bold text-sm bg-slate-100 px-6 py-2.5 rounded-2xl hover:bg-emerald-600 hover:text-white transition shadow-sm">
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
                                <div class="flex-1">
                                    <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest leading-none mb-1">Account</p>
                                    <div class="flex items-center justify-between">
                                        <p class="text-base font-extrabold text-slate-800 truncate"><?php echo $_SESSION['username']; ?></p>
                                        <a href="edit_profile.php" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition uppercase tracking-wider">Edit</a>
                                    </div>
                                </div>
                            </div>
                            <div class="py-1 space-y-1">
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

    <main class="p-8 max-w-[1400px] mx-auto w-full">
        
        <div class="bg-emerald-600 rounded-[2.5rem] p-12 text-center text-white mb-16 shadow-2xl relative overflow-hidden">
            <div class="relative z-10">
                <span class="bg-emerald-500/50 px-4 py-1.5 rounded-full text-[10px] font-extrabold uppercase border border-white/20 tracking-widest">Premium Healthcare</span>
                <h2 class="text-5xl font-black mt-6 mb-4 leading-tight tracking-tight">Expert Medical Care</h2>
                <p class="text-emerald-50 max-w-2xl mx-auto text-lg font-medium">Access priority booking with world-class specialists across various medical disciplines with state-of-the-art facilities.</p>
            </div>
            <div class="absolute top-0 right-0 w-80 h-80 bg-emerald-500/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
        </div>

        <div class="mb-20 text-center">
            <h2 class="text-3xl font-black text-slate-800 mb-4 tracking-tight">How it Works</h2>
            <div class="flex flex-wrap justify-center gap-12 mt-12">
                <div class="flex flex-col items-center max-w-[200px]">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-emerald-600 shadow-xl text-xl mb-4 border border-slate-100 italic font-black">1</div>
                    <h4 class="font-bold text-slate-800 mb-2">Pick Specialty</h4>
                    <p class="text-xs text-slate-400 font-medium leading-relaxed">Select the medical department you need for consultation.</p>
                </div>
                <div class="hidden md:block w-20 h-[2px] bg-slate-200 mt-8"></div>
                <div class="flex flex-col items-center max-w-[200px]">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-emerald-600 shadow-xl text-xl mb-4 border border-slate-100 italic font-black">2</div>
                    <h4 class="font-bold text-slate-800 mb-2">Choose Doctor</h4>
                    <p class="text-xs text-slate-400 font-medium leading-relaxed">Browse through our verified expert list and reviews.</p>
                </div>
                <div class="hidden md:block w-20 h-[2px] bg-slate-200 mt-8"></div>
                <div class="flex flex-col items-center max-w-[200px]">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-emerald-600 shadow-xl text-xl mb-4 border border-slate-100 italic font-black">3</div>
                    <h4 class="font-bold text-slate-800 mb-2">Visit Clinic</h4>
                    <p class="text-xs text-slate-400 font-medium leading-relaxed">Get your consultation scheduled instantly online.</p>
                </div>
            </div>
        </div>

        <div class="mb-20">
            <h2 class="text-2xl font-black text-slate-800 mb-8 tracking-tight">Medical Specialties</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php 
                $specs = [
                    ['Cardiology', 'Heart & Vascular Health', 'fa-heartbeat', 'text-rose-500', 'bg-rose-50'],
                    ['Neurology', 'Brain & Nervous System', 'fa-brain', 'text-purple-500', 'bg-purple-50'],
                    ['Oncology', 'Cancer Support & Care', 'fa-ribbon', 'text-blue-500', 'bg-blue-50'],
                    ['Pediatrics', 'Child Health Specialist', 'fa-baby', 'text-amber-500', 'bg-amber-50'],
                    ['Dermatology', 'Skin, Hair & Nail Care', 'fa-allergies', 'text-orange-500', 'bg-orange-50'],
                    ['Dentistry', 'Complete Oral Hygiene', 'fa-tooth', 'text-emerald-500', 'bg-emerald-50']
                ];
                foreach($specs as $s): ?>
                <div class="bg-white p-10 rounded-[2.5rem] border border-slate-100 shadow-xl hover:border-emerald-300 transition-all text-center group cursor-pointer">
                    <div class="w-20 h-20 <?php echo $s[4]; ?> <?php echo $s[3]; ?> rounded-[2rem] flex items-center justify-center mx-auto mb-8 text-3xl group-hover:scale-110 transition-transform duration-500 shadow-inner">
                        <i class="fas <?php echo $s[2]; ?>"></i>
                    </div>
                    <h3 class="font-black text-2xl text-slate-800 mb-2 tracking-tight"><?php echo $s[0]; ?></h3>
                    <p class="text-slate-400 font-medium mb-10 leading-relaxed"><?php echo $s[1]; ?></p>
                    <button class="w-full py-4 bg-slate-900 text-white rounded-2xl font-bold hover:bg-emerald-600 transition shadow-lg shadow-slate-200 active:scale-95">Book Specialist</button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="max-w-3xl mx-auto mb-20">
            <h2 class="text-2xl font-black text-slate-800 mb-8 text-center tracking-tight">Frequently Asked Questions</h2>
            <div class="space-y-4">
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <h4 class="font-bold text-slate-800 mb-2">How do I reschedule my appointment?</h4>
                    <p class="text-sm text-slate-500 font-medium leading-relaxed">You can reschedule via the 'My Bookings' section in your dashboard up to 4 hours before the scheduled slot.</p>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                    <h4 class="font-bold text-slate-800 mb-2">Are video consultations available?</h4>
                    <p class="text-sm text-slate-500 font-medium leading-relaxed">Yes, many of our specialists offer premium digital consultations via the integrated 'BioPharma Live' video platform.</p>
                </div>
            </div>
        </div>

    </main>

    <footer class="bg-white border-t border-slate-200 p-8 text-center mt-auto">
        <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest leading-none mb-1">Health is Wealth</p>
        <p class="text-slate-800 text-xs font-bold uppercase tracking-widest">&copy; 2026 BioPharma Specialty Care Division.</p>
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