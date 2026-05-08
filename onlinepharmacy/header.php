<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('config.php');
?>
<div id="loginModal" class="modal fixed inset-0 z-[100] items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-[2.5rem] p-10 shadow-2xl relative">
        <button onclick="toggleModal()" class="absolute right-8 top-8 text-slate-400 hover:text-slate-600">
            <i class="fas fa-times text-xl"></i>
        </button>
        <div class="text-center mb-8">
            <div class="bg-emerald-600 w-14 h-14 rounded-2xl flex items-center justify-center text-white mx-auto mb-4 shadow-lg shadow-emerald-100">
                <i class="fas fa-leaf text-2xl"></i>
            </div>
            <h2 class="text-2xl font-black text-slate-800">Login to BioPharma</h2>
        </div>
        <form action="auth.php" method="POST" class="space-y-4">
            <input type="email" name="email" required placeholder="Email ID" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-emerald-500 transition">
            <input type="password" name="password" required placeholder="Password" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-emerald-500 transition">
            <button type="submit" class="w-full bg-emerald-600 text-white py-4 rounded-2xl font-bold shadow-xl shadow-emerald-100 mt-4">Login</button>
        </form>
    </div>
</div>

<header class="glass-nav border-b border-slate-200 sticky top-0 z-40 px-8 py-4 flex justify-between items-center w-full">
    <div class="relative w-full max-w-xl">
        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
        <input type="text" placeholder="Search medicines, categories..." 
               class="w-full bg-slate-100 border-none rounded-2xl py-3.5 pl-12 pr-4 focus:ring-2 focus:ring-emerald-500 transition-all text-sm font-medium">
    </div>
    
    <div class="flex items-center gap-6 ml-10">
        <div class="flex items-center gap-4">
            <a href="offers.php" class="text-sm font-bold text-slate-600 hover:text-emerald-600">Offers</a>
            
            <?php if(!isset($_SESSION['user_id'])): ?>
                <button onclick="toggleModal()" class="flex items-center gap-2 text-slate-700 font-bold text-sm bg-slate-100 px-6 py-3 rounded-2xl hover:bg-emerald-600 hover:text-white transition">
                    Login
                </button>
            <?php else: ?>
                <a href="cart.php" class="relative p-3 bg-white border border-slate-200 rounded-2xl hover:shadow-xl transition">
                    <i class="fas fa-shopping-basket text-slate-600"></i>
                    <span class="absolute -top-1.5 -right-1.5 bg-rose-500 text-white text-[10px] w-5 h-5 rounded-full flex items-center justify-center font-bold border-2 border-white">
                        <?php echo isset($_SESSION['cart_count']) ? $_SESSION['cart_count'] : '0'; ?>
                    </span>
                </a>
                <div class="relative profile-trigger group cursor-pointer h-11 w-11 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-bold">
                    <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                    <div class="profile-dropdown absolute right-0 top-full w-48 bg-white shadow-2xl rounded-2xl mt-2 p-2 text-slate-800 border border-slate-100">
                        <a href="profile.php" class="block px-4 py-2 hover:bg-slate-50 rounded-xl text-sm font-bold">My Profile</a>
                        <a href="logout.php" class="block px-4 py-2 hover:bg-rose-50 rounded-xl text-rose-600 font-bold text-sm">Logout</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>

<script>
    function toggleModal() {
        document.getElementById('loginModal').classList.toggle('active');
    }
</script>