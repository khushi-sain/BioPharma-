<?php
session_start();
include('config.php');

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$cat = isset($_GET['cat']) ? mysqli_real_escape_string($conn, $_GET['cat']) : '';
?>
<!DOCTYPE html>
<html lang="en"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BioPharma | Healthcare</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .glass-nav { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); }
        .sidebar-link.active { background-color: #ecfdf5; color: #059669; font-weight: 700; border-radius: 12px; }
        .product-card:hover { transform: translateY(-4px); }
        
      
        .profile-dropdown { 
            display: none; 
            transform-origin: top right;
            transition: all 0.2s ease;
            z-index: 100;
        }
        .profile-dropdown.show { 
            display: block; 
            animation: slideIn 0.25s cubic-bezier(0.17, 0.67, 0.83, 0.67);
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(10px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes bump { 0% { transform: scale(1); } 50% { transform: scale(1.3); } 100% { transform: scale(1); } }
        .cart-bump { animation: bump 0.3s ease-out; }
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

    <form action="" method="GET" class="hidden md:block flex-1 max-w-xl mx-10">
        <div class="relative">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input type="text" name="search" value="<?php echo htmlspecialchars($search ?? ''); ?>" 
                   placeholder="Search medicines, vitamins..." 
                   class="w-full bg-slate-100 border-none rounded-2xl py-3 pl-12 pr-4 focus:ring-2 focus:ring-emerald-500 transition-all text-sm font-medium">
        </div>
    </form>
    
    <div class="flex items-center gap-6">
        <div class="flex items-center gap-6">
            <a href="offers.php" class="flex items-center gap-2 text-sm font-bold text-slate-600 hover:text-emerald-600 transition relative group">
               
                <span>Offers</span>
              
            </a>

            <a href="cart.php" class="relative flex items-center justify-center w-10 h-10 bg-[#eefaf5] rounded-xl group hover:shadow-md transition-all">
                <i class="fas fa-shopping-cart text-[#059669] text-lg"></i>
                <span id="cart-count" class="absolute -top-1.5 -right-1.5 bg-[#f84464] text-white text-[10px] font-bold h-5 w-5 rounded-full flex items-center justify-center border-2 border-white shadow-sm">
                    <?php echo isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : '0'; ?>
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
                                <p class="text-base font-extrabold text-slate-800 truncate"><?php echo $_SESSION['username']; ?></p>
                            </div>
                        </div>
                        <div class="py-1 space-y-1 text-left">
                            <a href="orders.php" class="block px-5 py-3 hover:bg-emerald-50 rounded-xl text-slate-600 hover:text-emerald-800 font-bold text-sm transition-all hover:translate-x-1">Order History</a>
                            <a href="help.php" class="block px-5 py-3 hover:bg-emerald-50 rounded-xl text-slate-600 hover:text-emerald-800 font-bold text-sm transition-all hover:translate-x-1">Help & Support</a>
                            <a href="logout.php" class="block px-5 py-3 hover:bg-rose-50 rounded-xl text-slate-600 hover:text-rose-600 font-bold text-sm transition-all hover:translate-x-1">Sign Out</a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>

    <div class="flex max-w-7xl mx-auto w-full px-6 py-10 gap-10">
        <aside class="w-56 flex-shrink-0 hidden lg:block">
            <div class="sticky top-28">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-5 px-2">Categories</h3>
                <nav class="space-y-1">
                    <a href="?" class="sidebar-link flex items-center gap-3 px-4 py-3 text-sm font-medium <?php echo $cat == '' ? 'active' : 'text-slate-600 hover:bg-slate-100'; ?>">
                        All Products
                    </a>
                    <?php
                    $cat_res = mysqli_query($conn, "SELECT DISTINCT category FROM medicines");
                    while($c = mysqli_fetch_assoc($cat_res)):
                    ?>
                    <a href="?cat=<?php echo urlencode($c['category']); ?>" 
                       class="sidebar-link flex items-center gap-3 px-4 py-3 text-sm font-medium <?php echo ($cat == $c['category']) ? 'active' : 'text-slate-600 hover:bg-slate-100'; ?>">
                        <?php echo $c['category']; ?>
                    </a>
                    <?php endwhile; ?>
                </nav>
            </div>
        </aside>

        <main class="flex-1">
            <div class="mb-8">
                <h1 class="text-3xl font-black text-slate-900 tracking-tight"><?php echo $cat ?: 'Medical Essentials'; ?></h1>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php
                $query = "SELECT * FROM medicines WHERE 1=1";
                if($search) $query .= " AND (name LIKE '%$search%' OR category LIKE '%$search%')";
                if($cat) $query .= " AND category = '$cat'";
                
                $res = mysqli_query($conn, $query);
                while($row = mysqli_fetch_assoc($res)):
                    $pid = $row['id'];
                    $qty = isset($_SESSION['cart'][$pid]) ? $_SESSION['cart'][$pid] : 0;
                ?>
                <div class="product-card bg-white rounded-[2rem] p-5 border border-slate-100 hover:border-emerald-100 transition-all flex flex-col group">
                    <div class="h-44 bg-slate-50 rounded-[1.5rem] mb-5 flex items-center justify-center p-6 relative group-hover:bg-emerald-50/50 transition-colors">
                        <img src="uploads/<?php echo $row['image']; ?>" class="h-full object-contain group-hover:scale-110 transition duration-500" onerror="this.src='https://cdn-icons-png.flaticon.com/512/822/822143.png'">
                    </div>
                    
                    <div class="flex-1 flex flex-col">
                        <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full w-fit mb-3 uppercase tracking-wider"><?php echo $row['category']; ?></span>
                        <h4 class="font-bold text-slate-800 text-base leading-snug line-clamp-2 h-12 mb-4"><?php echo $row['name']; ?></h4>
                        
                        <div class="mt-auto flex justify-between items-center pt-4 border-t border-slate-50">
                            <span class="text-xl font-black text-slate-900">₹<?php echo $row['price']; ?></span>
                            
                            <div id="controls-<?php echo $pid; ?>" class="flex items-center">
                                <?php if($qty > 0): ?>
                                    <div class="flex items-center bg-slate-900 rounded-xl overflow-hidden shadow-md">
                                        <button onclick="updateQty(<?php echo $pid; ?>, 'remove')" class="px-3 py-2 text-white hover:bg-emerald-600"><i class="fas fa-minus text-[10px]"></i></button>
                                        <span class="px-2 text-white font-bold text-sm min-w-[20px] text-center"><?php echo $qty; ?></span>
                                        <button onclick="updateQty(<?php echo $pid; ?>, 'add')" class="px-3 py-2 text-white hover:bg-emerald-600"><i class="fas fa-plus text-[10px]"></i></button>
                                    </div>
                                <?php else: ?>
                                    <button onclick="updateQty(<?php echo $pid; ?>, 'add')" class="bg-slate-900 text-white px-6 py-2.5 rounded-xl text-xs font-bold hover:bg-emerald-600 transition-all shadow-md active:scale-95">Add</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </main>
    </div>

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

   
        function updateQty(id, action) {
            const container = document.getElementById(`controls-${id}`);
            fetch(`cart_action.php?id=${id}&action=${action}&ajax=1`)
                .then(res => res.json())
                .then(data => {
                    const badge = document.getElementById('cart-count');
                    badge.innerText = data.total_cart_count;
                    badge.classList.add('cart-bump');
                    setTimeout(() => badge.classList.remove('cart-bump'), 300);

                    if (data.item_qty > 0) {
                        container.innerHTML = `
                            <div class="flex items-center bg-slate-900 rounded-xl overflow-hidden shadow-md">
                                <button onclick="updateQty(${id}, 'remove')" class="px-3 py-2 text-white hover:bg-emerald-600"><i class="fas fa-minus text-[10px]"></i></button>
                                <span class="px-2 text-white font-bold text-sm min-w-[20px] text-center">${data.item_qty}</span>
                                <button onclick="updateQty(${id}, 'add')" class="px-3 py-2 text-white hover:bg-emerald-600"><i class="fas fa-plus text-[10px]"></i></button>
                            </div>`;
                    } else {
                        container.innerHTML = `<button onclick="updateQty(${id}, 'add')" class="bg-slate-900 text-white px-6 py-2.5 rounded-xl text-xs font-bold hover:bg-emerald-600 transition-all shadow-md active:scale-95">Add</button>`;
                    }
                });
        }
    </script>
</body>
</html>