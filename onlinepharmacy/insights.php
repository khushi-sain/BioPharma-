<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('config.php'); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Insights | BioPharma</title>
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

 

  <main class="max-w-4xl mx-auto p-6 py-12">

        <div class="mb-12">

            <h2 class="text-4xl font-black text-slate-900 tracking-tight mb-2">Latest <span class="text-[#059669]">Articles</span></h2>

            <p class="text-slate-500 font-medium text-lg">Curated medical advice and lifestyle tips from our health experts.</p>

        </div>



        <?php

        $articles = [

            ['Boosting Immunity', 'Nutrition', 'Learn how these 5 superfoods can strengthen your immune system naturally against seasonal infections.', 'boostimmu.jpg', 'article.php?slug=boosting-immunity'],

            ['Mental Health at Work', 'Wellness', 'Simple daily habits to reduce workplace stress, prevent burnout, and improve mental clarity.', 'mentalhealth.png', 'article.php?slug=mental-health-at-work'],

            ['Heart Healthy Yoga', 'Fitness', 'A guide to 10-minute daily yoga poses that improve blood circulation and cardiovascular health.', 'healthheartyoga.png', 'article.php?slug=heart-healthy-yoga'],

            ['Diabetes Management', 'Medical', 'Understanding blood sugar levels: Tips for maintaining a balanced lifestyle with Type 2 Diabetes.', 'diabitymanagement.png', 'article.php?slug=diabetes-management'],

            ['Skincare for Summer', 'Lifestyle', 'Expert dermatologist advice on protecting your skin from UV damage and staying hydrated.', 'skincareforsummer.png', 'article.php?slug=skincare-for-summer'],

            ['The Power of Sleep', 'Wellness', 'Why 8 hours of quality sleep is more important than your workout for muscle and brain recovery.', 'thepowerofsleep.png', 'article.php?slug=the-power-of-sleep'],

            ['Superfoods for Brain', 'Nutrition', 'Boost your cognitive function and memory with these nutrient-dense foods like walnuts and berries.', 'superfoodforbrain.png', 'article.php?slug=superfoods-for-brain'],

            ['Dealing with Allergies', 'Medical', 'Seasonal allergy triggers and how modern antihistamines work to provide instant relief.', 'dealwithallergie.png', 'article.php?slug=dealing-with-allergies'],

            ['Post-Workout Meals', 'Fitness', 'The perfect ratio of protein and carbs you need to eat within 45 minutes of training.', 'postworkout.png', 'article.php?slug=post-workout-meals'],

            ['Hydration Myths', 'Lifestyle', 'Is drinking 8 glasses of water a day a myth? Learn about your body’s true hydration requirements.', 'hydrationmyth.png', 'article.php?slug=hydration-myths']

        ];

        foreach($articles as $art): ?>

        <article class="bg-white rounded-[2.5rem] p-8 border border-slate-100 mb-12 shadow-sm hover:shadow-2xl hover:border-[#10b981]/30 transition-all duration-500 group cursor-pointer">

            <div class="h-64 bg-[#f0fdf4] rounded-[2rem] mb-6 overflow-hidden relative">

                <img src="images/<?php echo rawurlencode($art[3]); ?>" alt="<?php echo $art[0]; ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">

                <div class="absolute top-6 left-6">

                    <span class="text-[10px] font-black text-[#059669] bg-white px-4 py-2 rounded-xl uppercase tracking-widest shadow-sm border border-emerald-50">

                        <?php echo $art[1]; ?>

                    </span>

                </div>

            </div>

           

            <h2 class="text-3xl font-black text-slate-900 mt-4 mb-4 group-hover:text-[#059669] transition-colors tracking-tight">

                <?php echo $art[0]; ?>

            </h2>

            <p class="text-slate-500 leading-relaxed mb-8 font-medium italic">

                <?php echo $art[2]; ?>

            </p>

           

            <div class="flex items-center justify-between border-t border-slate-50 pt-6">

                <?php if (!empty($art[4])): ?>
                <a href="<?php echo $art[4]; ?>" class="font-black text-[11px] uppercase tracking-widest text-slate-800 hover:text-[#059669] flex items-center gap-2 group/btn transition-all">

                    Read Full Article

                    <i class="fas fa-chevron-right text-[9px] group-hover/btn:translate-x-1 transition-transform"></i>

                </a>
                <?php else: ?>
                <button class="font-black text-[11px] uppercase tracking-widest text-slate-400 flex items-center gap-2 cursor-not-allowed">

                    Read Full Article

                    <i class="fas fa-chevron-right text-[9px]"></i>

                </button>
                <?php endif; ?>

                <div class="flex gap-4 text-slate-300">

                    <i class="far fa-heart hover:text-rose-500 transition-colors"></i>

                    <i class="far fa-bookmark hover:text-[#059669] transition-colors"></i>

                </div>

            </div>

        </article>

        <?php endforeach; ?>

    </main>


    <footer class="p-12 text-center bg-white border-t border-slate-50 mt-12">
        <div class="mb-6">
            <span class="text-xl font-black italic tracking-tighter text-slate-800">BioPharma <span class="text-[#059669]">Insights</span></span>
        </div>
        <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">
            &copy; 2026 BioPharma Healthcare. All medical content is verified by certified professionals.
        </p>
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