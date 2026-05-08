<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Consult | BioPharma</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; scroll-behavior: smooth; overflow-x: hidden; background-color: #f8fafc; }
        .glass-nav { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
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

    <div class="flex-1 flex flex-col">

       



        <header class="bg-[#0f172a] py-20 text-center text-white relative overflow-hidden">

            <div class="relative z-10">

                <h1 class="text-5xl font-black mb-4 tracking-tight">Talk to a <span class="text-[#10b981]">Specialist</span></h1>

                <p class="text-slate-400 font-medium max-w-xl mx-auto">Video call with top-rated doctors within 15 minutes. Secure, private, and professional healthcare at your doorstep.</p>

            </div>

            <div class="absolute -top-24 -left-24 w-96 h-96 bg-[#10b981]/10 rounded-full blur-3xl"></div>

        </header>



        <main class="p-8 max-w-[1400px] mx-auto w-full">

           

            <div class="flex justify-between items-end mb-8">

                <div>

                    <h2 class="text-2xl font-black text-slate-800 tracking-tight">Featured <span class="text-[#059669]">Specialists</span></h2>

                    <p class="text-slate-400 text-sm font-medium">Verified experts online for instant consultation</p>

                </div>

                <div class="flex gap-3">

                    <button onclick="slideDocs('left')" class="w-10 h-10 bg-white border border-slate-200 rounded-full flex items-center justify-center text-slate-600 hover:bg-[#059669] hover:text-white transition shadow-sm">

                        <i class="fas fa-chevron-left text-xs"></i>

                    </button>

                    <button onclick="slideDocs('right')" class="w-10 h-10 bg-white border border-slate-200 rounded-full flex items-center justify-center text-slate-600 hover:bg-[#059669] hover:text-white transition shadow-sm">

                        <i class="fas fa-chevron-right text-xs"></i>

                    </button>

                    <button onclick="slideDocs('right')" class="bg-emerald-50 text-emerald-600 px-5 py-2 rounded-xl font-black uppercase text-[10px] tracking-widest hover:bg-emerald-600 hover:text-white transition ml-2">View All</button>

                </div>

            </div>



            <div id="docTrack" class="flex gap-8 overflow-x-auto scroll-smooth hide-scrollbar pb-10">

                <?php

                $doctors = [

                    ['Dr. Arpit Verma', 'General Physician', '12 Years Exp', '₹499', 'https://i.pravatar.cc/150?u=a1'],

                    ['Dr. Sneha Kapoor', 'Dermatologist', '8 Years Exp', '₹799', 'https://i.pravatar.cc/150?u=a2'],

                    ['Dr. Rahul Mehta', 'Pediatrician', '15 Years Exp', '₹599', 'https://i.pravatar.cc/150?u=a3'],

                    ['Dr. Priya Singh', 'Gynaecologist', '10 Years Exp', '₹699', 'https://i.pravatar.cc/150?u=a4'],

                    ['Dr. Amit Shah', 'Psychiatrist', '20 Years Exp', '₹999', 'https://i.pravatar.cc/150?u=a5'],

                    ['Dr. Kavita Rao', 'Dentist', '7 Years Exp', '₹399', 'https://i.pravatar.cc/150?u=a6']

                ];

                foreach($doctors as $doc): ?>

                <div class="min-w-[350px] bg-white p-8 rounded-[2.5rem] shadow-xl border border-slate-100 hover:border-[#10b981] transition-all duration-300 group">

                    <div class="flex items-center gap-6 mb-8 text-left">

                        <div class="relative">

                            <img src="<?php echo $doc[4]; ?>" class="w-20 h-20 rounded-[2rem] object-cover shadow-xl border-4 border-white group-hover:scale-105 transition-transform">

                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-[#10b981] border-4 border-white rounded-full"></div>

                        </div>

                        <div>

                            <h3 class="font-black text-lg text-slate-800 tracking-tight"><?php echo $doc[0]; ?></h3>

                            <p class="text-[#059669] text-[10px] font-black uppercase tracking-widest mt-1"><?php echo $doc[1]; ?></p>

                        </div>

                    </div>

                    <div class="bg-slate-50 rounded-2xl p-4 mb-8 flex justify-between items-center border border-slate-100">

                        <span class="text-[10px] font-bold text-slate-500 italic"><i class="fas fa-briefcase mr-2 text-[#10b981]"></i><?php echo $doc[2]; ?></span>

                        <span class="text-xl font-black text-slate-900"><?php echo $doc[3]; ?></span>

                    </div>

                    <button class="w-full py-4 bg-[#059669] text-white rounded-2xl font-black hover:bg-[#047857] transition-all shadow-xl uppercase text-[10px] tracking-widest active:scale-95">

                        Book Consult

                    </button>

                </div>

                <?php endforeach; ?>

            </div>



            <section class="bg-[#0f172a] rounded-[3rem] p-12 text-white mt-12 mb-20 relative overflow-hidden border border-slate-800">

                <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-12 text-left">

                    <div class="flex-1">

                        <span class="text-[#10b981] font-black text-[10px] uppercase tracking-widest bg-emerald-500/10 px-4 py-2 rounded-full mb-6 inline-block border border-emerald-500/20">Interactive Guide</span>

                        <h2 class="text-4xl font-black tracking-tight mb-4">Not sure who <br> to <span class="text-[#10b981]">Consult?</span></h2>

                        <p class="text-slate-400 font-medium leading-relaxed mb-8 max-w-md">Our smart symptom guide helps you identify the right specialist based on how you're feeling right now.</p>

                       

                        <div class="grid grid-cols-2 gap-4">

                            <button class="bg-white/5 border border-white/10 p-4 rounded-2xl hover:bg-[#10b981] hover:border-transparent transition-all text-left">

                                <i class="fas fa-head-side-virus mb-3 text-emerald-400"></i>

                                <p class="text-xs font-bold">Fever & Cold</p>

                            </button>

                            <button class="bg-white/5 border border-white/10 p-4 rounded-2xl hover:bg-[#10b981] hover:border-transparent transition-all text-left">

                                <i class="fas fa-allergies mb-3 text-emerald-400"></i>

                                <p class="text-xs font-bold">Skin Rashes</p>

                            </button>

                            <button class="bg-white/5 border border-white/10 p-4 rounded-2xl hover:bg-[#10b981] hover:border-transparent transition-all text-left">

                                <i class="fas fa-heartbeat mb-3 text-emerald-400"></i>

                                <p class="text-xs font-bold">Chest Pain</p>

                            </button>

                            <button class="bg-white/5 border border-white/10 p-4 rounded-2xl hover:bg-[#10b981] hover:border-transparent transition-all text-left text-xs font-bold">

                                See All Symptoms

                            </button>

                        </div>

                    </div>

                    <div class="w-full md:w-1/3 text-center">

                        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-10 rounded-[2.5rem] shadow-2xl">

                            <i class="fas fa-bolt text-4xl text-yellow-300 mb-6 animate-bounce"></i>

                            <h4 class="text-xl font-black mb-2">Instant Connect</h4>

                            <p class="text-xs text-emerald-50 font-medium mb-8">Get connected to a general physician in under 5 minutes for urgent triage.</p>

                            <button class="bg-white text-[#059669] w-full py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-xl">Start Urgent Call</button>

                        </div>

                    </div>

                </div>

            </section>



        </main>

        <footer class="py-10 text-center border-t border-slate-200">
            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">&copy; 2026 BioPharma TeleHealth Hub</p>
        </footer>
    </div>

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
        function slideDocs(direction) {
            const track = document.getElementById('docTrack');
            const scrollAmount = 380;
            if (direction === 'left') { track.scrollLeft -= scrollAmount; } 
            else { track.scrollLeft += scrollAmount; }
        }
    </script>
</body>
</html>