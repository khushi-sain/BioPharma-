<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BioPharma | Premium Healthcare Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-nav { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); }
        .sidebar-item:hover { background-color: #f0fdf4; color: #059669; }
        .modal { display: none; background: rgba(0,0,0,0.5); }
        .modal.active { display: flex; }
      .profile-dropdown { 
    display: none; 
    transform-origin: top right;
    transition: all 0.2s ease;
}
.profile-dropdown.show { 
    display: block; 
    animation: slideIn 0.2s ease-out;
}
@keyframes slideIn {
    from { opacity: 0; transform: translateY(10px) scale(0.95); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}
        .profile-trigger:hover .profile-dropdown { display: block; }
        .footer-link:hover { color: #10b981; transform: translateX(5px); transition: 0.3s; }
        .cat-card:hover img { transform: scale(1.1); transition: 0.4s; }
    </style>
</head>
<body class="bg-slate-50 flex min-h-screen">

    <div id="loginModal" class="modal fixed inset-0 z-[100] items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-white w-full max-w-md rounded-[2.5rem] p-10 shadow-2xl relative border border-slate-100">
        <button onclick="toggleModal()" class="absolute right-8 top-8 text-slate-400 hover:text-slate-600">
            <i class="fas fa-times text-xl"></i>
        </button>

        <div class="text-center mb-8">
            <div class="bg-emerald-600 w-14 h-14 rounded-2xl flex items-center justify-center text-white mx-auto mb-4 shadow-lg">
                <i class="fas fa-leaf text-2xl"></i>
            </div>
            <h2 id="modalTitle" class="text-2xl font-black text-slate-800 tracking-tight">Login to BioPharma</h2>
        </div>

        <!-- TABS FOR SWITCHING -->
        <div class="flex gap-2 mb-8 bg-slate-50 p-1.5 rounded-2xl">
            <button onclick="switchTab('login')" id="loginTab" class="flex-1 py-2.5 text-sm font-bold rounded-xl bg-white text-emerald-600 shadow-sm transition-all">Login</button>
            <button onclick="switchTab('signup')" id="signupTab" class="flex-1 py-2.5 text-sm font-bold rounded-xl text-slate-500 transition-all">Sign Up</button>
        </div>

        <!-- LOGIN FORM -->
        <form id="loginForm" action="auth.php" method="POST" class="space-y-4">
            <input type="hidden" name="action" value="login">
            <input type="email" name="email" required placeholder="Email ID" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-emerald-500 outline-none transition text-sm">
            <input type="password" name="password" required placeholder="Password" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-emerald-500 outline-none transition text-sm">
            <button type="submit" class="w-full bg-emerald-600 text-white py-4 rounded-2xl font-bold shadow-xl mt-4 hover:bg-emerald-700 transition">Login</button>
        </form>

        <!-- SIGNUP FORM -->
        <form id="signupForm" action="auth.php" method="POST" class="space-y-4 hidden">
            <input type="hidden" name="action" value="signup">
            <input type="text" name="username" required placeholder="Full Name" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-emerald-500 outline-none transition text-sm">
            <input type="email" name="email" required placeholder="Email ID" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-emerald-500 outline-none transition text-sm">
            <input type="password" name="password" required placeholder="Create Password" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 focus:ring-2 focus:ring-emerald-500 outline-none transition text-sm">
            <button type="submit" class="w-full bg-emerald-600 text-white py-4 rounded-2xl font-bold shadow-xl mt-4 hover:bg-emerald-700 transition">Create Account</button>
        </form>
    </div>
</div>

    <aside class="w-20 lg:w-72 bg-white border-r border-slate-200 sticky top-0 h-screen flex flex-col z-50 transition-all">
    <div class="p-6 flex items-center gap-3">
        <div class="bg-emerald-600 p-2.5 rounded-xl text-white shadow-lg shadow-emerald-200">
            <i class="fas fa-leaf text-xl"></i>
        </div>
        <span class="text-xl font-extrabold text-slate-800 hidden lg:block tracking-tight">BioPharma</span>
    </div>
    
    <nav class="flex-1 px-4 space-y-2 mt-6">
        <a href="index.php" class="flex items-center gap-4 p-3.5 bg-emerald-600 text-white rounded-2xl font-bold shadow-lg shadow-emerald-100">
            <i class="fas fa-th-large w-6 text-center"></i>
            <span class="hidden lg:block">Dashboard</span>
        </a>

        <a href="medicines.php" class="sidebar-item flex items-center gap-4 p-3.5 text-slate-500 rounded-2xl transition font-semibold hover:bg-emerald-50 hover:text-emerald-600">
            <i class="fas fa-pills w-6 text-center"></i>
            <span class="hidden lg:block">Medicines</span>
        </a>
   <a href="speciality.php" class="sidebar-item flex items-center gap-4 p-3.5 text-slate-500 rounded-2xl transition font-semibold hover:bg-emerald-50 hover:text-emerald-600">
            <i class="fas fa-tags w-6 text-center"></i>
            <span class="hidden lg:block">Specialty Care</span>
        </a>
          

        <a href="labs.php" class="sidebar-item flex items-center gap-4 p-3.5 text-slate-500 rounded-2xl transition font-semibold hover:bg-emerald-50 hover:text-emerald-600">
            <i class="fas fa-microscope w-6 text-center"></i>
            <span class="hidden lg:block">Lab Tests</span>
        </a>
<a href="fitness.php" class="sidebar-item flex items-center gap-4 p-3.5 text-slate-500 rounded-2xl transition font-semibold hover:bg-emerald-50 hover:text-emerald-600">
            <i class="fas fa-dumbbell w-6 text-center text-emerald-500"></i>
            <span class="hidden lg:block">Fitness & Nutrition</span>
        </a>
        <a href="consult.php" class="sidebar-item flex items-center gap-4 p-3.5 text-slate-500 rounded-2xl transition font-semibold hover:bg-emerald-50 hover:text-emerald-600">
            <i class="fas fa-user-md w-6 text-center"></i>
            <span class="hidden lg:block">Doctor Consult</span>
        </a>
 <a href="plus.php" class="sidebar-item flex items-center gap-4 p-3.5 text-slate-500 rounded-2xl transition font-semibold hover:bg-emerald-50 hover:text-emerald-600">
            <i class="fas fa-user-md w-6 text-center"></i>
            <span class="hidden lg:block">BioPharma PLUS</span>
        </a>
        <a href="insights.php" class="sidebar-item flex items-center gap-4 p-3.5 text-slate-500 rounded-2xl transition font-semibold hover:bg-emerald-50 hover:text-emerald-600">
            <i class="fas fa-book-medical w-6 text-center"></i>
            <span class="hidden lg:block">Health Insights</span>
        </a>

       
    </nav>

    <div class="p-6">
        
    </div>
</aside>

    <div class="flex-1 flex flex-col min-w-0">
      <header class="glass-nav border-b border-slate-200 sticky top-0 z-40 px-8 py-4 flex justify-between items-center shadow-sm">
    <div class="relative w-full max-w-xl">
          </div>
    
    <div class="flex items-center gap-6 ml-10">
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
            <div class="bg-emerald-600 rounded-[2.5rem] p-10 text-white relative overflow-hidden shadow-2xl shadow-emerald-100 mb-12">
                <div class="relative z-10 max-w-md">
                    <span class="bg-emerald-500/50 px-4 py-1.5 rounded-full text-[10px] font-extrabold uppercase border border-white/20">Summer Sale</span>
                    <h2 class="text-5xl font-extrabold mt-6 mb-4 leading-tight">Health Essentials <br> at your door.</h2>
                    <p class="text-emerald-100 text-lg mb-8 font-medium">Flat 25% OFF on first medicine order.</p>
                    <a href="medicines.php" class="inline-block bg-white text-emerald-700 px-10 py-4 rounded-2xl font-extrabold shadow-xl hover:scale-105 transition">Order Now</a>
                </div>
                <img src="https://cdn-icons-png.flaticon.com/512/2830/2830305.png" class="absolute right-0 bottom-[-20px] h-80 opacity-20">
            </div>

  <div class="mb-14">
    <h2 class="text-2xl font-extrabold text-slate-800 mb-8 tracking-tight">Lab Tests by Concern</h2>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-5">
        <?php 
        $concerns = [
            ['Full Body', 'rose-50', 'full body.png'],
            ['Vitamins', 'amber-50', 'vitamin.png'],
            ['Diabetes', 'blue-50', 'diabities.png'],
            ['Women Care', 'pink-50', 'womencare.png'],
            ['Skin Care', 'orange-50', 'skincare.png'],
            ['Thyroid', 'indigo-50', 'thyroid.png']
        ];

        // EK HI BAAR foreach CHALANA HAI
        foreach($concerns as $c): ?>
            <!-- Yeh link user ko book_test.php par le jayega -->
            <a href="book_test.php?test=<?php echo urlencode($c[0]); ?>" class="bg-white p-5 rounded-[2rem] text-center hover:shadow-2xl transition-all group cursor-pointer block border border-slate-50">
                <div class="w-full h-32 rounded-2xl overflow-hidden mx-auto mb-4 group-hover:scale-[1.03] transition duration-500">
                    <img src="images/<?php echo rawurlencode($c[2]); ?>" class="w-full h-full object-cover" alt="<?php echo $c[0]; ?>">
                </div>
                <p class="text-sm font-bold text-slate-700 leading-tight"><?php echo $c[0]; ?></p>
            </a>
        <?php endforeach; ?>
    </div>
</div>

          <div class="bg-gradient-to-br from-emerald-50 via-[#e0f8f1] to-teal-50 rounded-[2.5rem] p-8 lg:p-10 flex flex-col lg:flex-row items-center justify-between border border-emerald-100 shadow-xl mb-14 relative overflow-hidden">
    
    <div class="absolute top-0 left-0 w-72 h-72 bg-emerald-400/20 rounded-full blur-3xl -translate-y-1/2 -translate-x-1/4"></div>
    <div class="absolute bottom-0 right-0 w-80 h-80 bg-teal-400/20 rounded-full blur-3xl translate-y-1/3 translate-x-1/3"></div>

    <div class="flex-1 pr-0 lg:pr-8 relative z-10 w-full mb-8 lg:mb-0">
        <div class="inline-flex items-center gap-2 px-5 py-2 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-full text-[10px] font-black uppercase tracking-widest mb-6 shadow-lg shadow-emerald-200">
            <i class="fas fa-bolt text-yellow-300"></i> Flat 15% Off Rx Orders
        </div>

        <h3 class="text-4xl font-black text-slate-800 tracking-tight mb-4">Order with <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">Prescription</span></h3>
        
        <p class="text-slate-600 font-semibold mb-8 leading-relaxed max-w-lg">Simply upload your doctor's prescription and let our pharmacists do the rest. We'll arrange your medicines and deliver them in no time.</p>

        <div class="flex items-center gap-3 sm:gap-5 text-sm font-black text-slate-700">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center shadow-sm text-lg"><i class="fas fa-cloud-upload-alt"></i></div> 
                <span class="hidden sm:block">Upload</span>
            </div>
            
            <div class="w-6 sm:w-10 h-1 bg-gradient-to-r from-emerald-200 to-teal-200 rounded-full"></div>
            
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-teal-100 text-teal-600 flex items-center justify-center shadow-sm text-lg"><i class="fas fa-phone-alt"></i></div> 
                <span class="hidden sm:block">Confirm</span>
            </div>
            
            <div class="w-6 sm:w-10 h-1 bg-gradient-to-r from-teal-200 to-emerald-300 rounded-full"></div>
            
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-white border border-emerald-200 text-emerald-500 flex items-center justify-center shadow-sm text-lg"><i class="fas fa-motorcycle"></i></div> 
                <span class="hidden sm:block">Delivery</span>
            </div>
        </div>
    </div>

<div class="w-full lg:w-auto relative z-10">
    <form action="upload_prescription.php" method="POST" enctype="multipart/form-data" class="border-2 border-dashed border-emerald-400 bg-white/80 backdrop-blur-md rounded-[2rem] p-8 lg:p-10 text-center hover:bg-white hover:border-emerald-500 transition-all group shadow-xl shadow-emerald-900/5 min-w-[300px]">
        
        <input id="prescriptionFile" name="prescription" type="file" accept=".pdf,.jpg,.jpeg,.png" class="hidden" required>
        
        <div class="w-20 h-20 mx-auto bg-gradient-to-tr from-emerald-100 to-teal-50 rounded-2xl shadow-inner text-emerald-600 flex items-center justify-center text-3xl mb-5 group-hover:-translate-y-2 group-hover:scale-110 transition-all duration-300">
            <i class="fas fa-file-medical"></i>
        </div>
        
        <h4 class="font-extrabold text-slate-800 mb-1 text-xl">Upload Prescription</h4>
        <p class="text-[10px] text-slate-400 font-bold mb-6 uppercase tracking-widest">PDF, JPG, PNG (Max 5MB)</p>
        
        <button type="button" onclick="openPrescriptionPicker()" class="bg-slate-100 text-slate-600 w-full py-3 rounded-xl font-extrabold shadow-sm hover:bg-slate-200 transition-all mb-3">
            Select File
        </button>
        
        <p id="prescriptionFileName" class="text-xs text-emerald-600 font-bold mb-4 truncate">No file selected</p>
        
        <!-- YEH BUTTON FILE SELECT HONE KE BAAD DIKHEGA -->
        <button type="submit" id="submitPrescriptionBtn" class="hidden bg-gradient-to-r from-emerald-600 to-teal-500 text-white w-full py-4 rounded-xl font-extrabold shadow-xl shadow-emerald-200 hover:from-emerald-700 hover:to-teal-600 hover:-translate-y-1 transition-all">
            Order Medicines Now
        </button>
    </form>
</div>
        </div>
           <div class="mb-14">
    <h2 class="text-2xl font-extrabold text-slate-800 mb-8 tracking-tight">Shop by Categories</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-6">
        <?php 
        $shop_cats = [
            ['Must haves', 'musthave.png'],
            ['Summer store', 'summer store.png'],
            ['Vitamin store', 'vitaminstore.png'],
            ['Sexual wellness', 'sexualwellness.png'],
            ['Health food', 'healthfood.png'],
            ['Heart care', 'heartcare.png'],
            ['Diabetes', 'diabity.png']
        ];
        foreach($shop_cats as $cat): ?>
            <!-- Category ko clickable banaya gaya hai jo product_checkout.php par jayega -->
            <a href="product_checkout.php?category=<?php echo urlencode($cat[0]); ?>" class="cat-card text-center group cursor-pointer block">
                <div class="bg-white border border-slate-100 rounded-3xl p-3 mb-4 h-40 flex items-center justify-center shadow-sm group-hover:shadow-xl transition-all overflow-hidden">
                    <img src="images/<?php echo rawurlencode($cat[1]); ?>" class="w-full h-full object-cover rounded-2xl transition duration-500 group-hover:scale-110" alt="<?php echo $cat[0]; ?>">
                </div>
                <p class="text-sm font-bold text-slate-700"><?php echo $cat[0]; ?></p>
            </a>
        <?php endforeach; ?>
    </div>
</div>

  <div class="mb-14">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Featured Brands</h2>
            <p class="text-slate-400 text-sm font-medium mt-1">Trusted products from top companies</p>
        </div>
        <div class="flex gap-3">
            <button id="prevBrandBtn" class="w-10 h-10 bg-white text-slate-400 rounded-full flex items-center justify-center border border-slate-200 hover:bg-slate-50 hover:text-emerald-600 hover:scale-105 transition-all shadow-sm">
                <i class="fas fa-chevron-left text-sm"></i>
            </button>
            <button id="nextBrandBtn" class="w-10 h-10 bg-emerald-600 text-white rounded-full flex items-center justify-center shadow-md hover:bg-emerald-700 hover:scale-105 transition-all">
                <i class="fas fa-chevron-right text-sm"></i>
            </button>
        </div>
    </div>
    <div class="w-full overflow-hidden">
        <div id="brandsTrack" class="flex gap-6 transition-transform duration-500 ease-out">
            <?php
            $brands = [
                ['Dabur', 'dabur.png'],
                ['Himalaya', 'himalaya.png'],
                ['Ensure', 'ensure.png'],
                ['Accu-Chek', 'accu-check.png'],
                ['Baidyanath', 'badhya.png'],
                ['Patanjali', 'patanjali.png'],
                ['Zandu', 'zandulogo.png'],
                ['Horlicks', 'horlickslogo.png'],
                ['Mamaearth', 'mamaearth.png'],
                ['Cetaphil', 'cetaphillogo.png']
            ];
            foreach($brands as $b): ?>
            <div class="bg-white h-24 shrink-0 w-40 sm:w-48 lg:w-52 rounded-[1.5rem] overflow-hidden flex items-center justify-center hover:shadow-lg transition cursor-pointer">
                <img src="images/<?php echo rawurlencode($b[1]); ?>" alt="<?php echo $b[0]; ?>" class="w-full h-full <?php echo $b[0] === 'Accu-Chek' ? 'object-contain p-2' : 'object-cover'; ?> hover:scale-105 transition duration-500">
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<div class="mb-14">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">New Launches</h2>
            <p class="text-slate-400 text-sm font-medium mt-1">New wellness range just for you!</p>
        </div>
        <button class="bg-emerald-50 text-emerald-600 px-4 py-2 rounded-xl text-xs font-bold hover:bg-emerald-600 hover:text-white transition">View All</button>
    </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-6">
        <?php 
        $launches = [
            // Format: [ID, Name, MRP, Price, Discount, Image]
            [1, 'Calcium 1200mg', 'MRP ₹935.00', '₹504.90', '46% OFF', 'calcium.png'],
            [2, 'Lactation Supplement', 'MRP ₹394.00', '₹295.50', '25% OFF', 'lacton.png'],
            [3, 'Gaviscon Liquid', 'MRP ₹210.90', '₹158.18', '25% OFF', 'gavi.png'],
            [4, 'Zandu Relief Spray', 'MRP ₹188.00', '₹116.56', '38% OFF', 'zandu.png'],
            [5, 'Cetaphil Cleanser', 'MRP ₹1399.00', '₹993.29', '29% OFF', 'cetaphil.png'],
            [6, 'Baidyanath Vatakul', 'MRP ₹292.01', '₹239.45', '18% OFF', 'baidhyanath.png']
        ];
        foreach($launches as $l): ?>
        <div class="launch-card bg-white p-5 rounded-[2rem] border border-slate-100 flex flex-col justify-between group cursor-pointer relative">
            <div>
                <div class="h-40 flex items-center justify-center mb-4 bg-slate-50 rounded-2xl p-4 overflow-hidden relative">
                    <img src="images/<?php echo rawurlencode($l[5]); ?>" class="h-32 object-contain group-hover:scale-110 transition duration-500" alt="<?php echo $l[1]; ?>">
                    
                    <a href="cart_action.php?id=<?php echo $l[0]; ?>&action=add" 
                       class="absolute bottom-2 right-2 bg-emerald-600 text-white w-10 h-10 rounded-xl flex items-center justify-center shadow-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300 hover:bg-emerald-700">
                        <i class="fas fa-plus"></i>
                    </a>
                </div>
                <h4 class="text-xs font-bold text-slate-700 line-clamp-2 h-8 leading-tight mb-2"><?php echo $l[1]; ?></h4>
            </div>
            <div class="mt-2">
                <p class="text-[10px] text-slate-400 line-through font-semibold"><?php echo $l[2]; ?></p>
                <div class="flex items-center justify-between mt-1">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-black text-slate-900"><?php echo $l[3]; ?></span>
                        <span class="text-[10px] font-bold text-rose-500 bg-rose-50 px-1.5 py-0.5 rounded-lg"><?php echo $l[4]; ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<div class="mb-14">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">What Our Customers Say</h2>
            <p class="text-slate-400 text-sm font-medium mt-1">Real stories from our users</p>
        </div>
        <div class="flex gap-3">
            <button id="prevReviewBtn" class="w-10 h-10 bg-white text-slate-400 rounded-full flex items-center justify-center border border-slate-200 hover:bg-slate-50 hover:text-emerald-600 hover:scale-105 transition-all shadow-sm">
                <i class="fas fa-chevron-left text-sm"></i>
            </button>
            <button id="nextReviewBtn" class="w-10 h-10 bg-emerald-600 text-white rounded-full flex items-center justify-center shadow-md hover:bg-emerald-700 hover:scale-105 transition-all">
                <i class="fas fa-chevron-right text-sm"></i>
            </button>
        </div>
        
    </div>
    <div class="w-full overflow-hidden">
        <div id="reviewsTrack" class="flex gap-6 transition-transform duration-500 ease-out py-2">
            
            <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm relative shrink-0 w-80 sm:w-96 lg:w-[400px]">
                <i class="fas fa-quote-left text-4xl text-emerald-100 absolute top-6 right-6"></i>
                <div class="flex items-center gap-1 text-amber-400 text-sm mb-4">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <p class="text-slate-600 text-sm font-medium mb-6 leading-relaxed">"BioPharma has been a lifesaver. The prescription upload feature is seamless, and my elderly mother's diabetes medication arrives within an hour every single time."</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center font-bold">R</div>
                    <div>
                        <h5 class="text-sm font-bold text-slate-800">Rahul Sharma</h5>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Verified Buyer</p>
                    </div>
                </div>
            </div>
                       
            <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm relative shrink-0 w-80 sm:w-96 lg:w-[400px]">
                <i class="fas fa-quote-left text-4xl text-emerald-100 absolute top-6 right-6"></i>
                <div class="flex items-center gap-1 text-amber-400 text-sm mb-4">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                </div>
                <p class="text-slate-600 text-sm font-medium mb-6 leading-relaxed">"I booked a full body checkup for my husband. The phlebotomist was highly professional, on time, and the reports were uploaded to the dashboard the very next day."</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold">A</div>
                    <div>
                        <h5 class="text-sm font-bold text-slate-800">Anjali Desai</h5>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Lab Test Patient</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm relative shrink-0 w-80 sm:w-96 lg:w-[400px]">
                <i class="fas fa-quote-left text-4xl text-emerald-100 absolute top-6 right-6"></i>
                <div class="flex items-center gap-1 text-amber-400 text-sm mb-4">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <p class="text-slate-600 text-sm font-medium mb-6 leading-relaxed">"The doctor consultation feature is amazing. Got connected to a specialist within 10 minutes and the prescribed medicines were delivered the same evening."</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center font-bold">S</div>
                    <div>
                        <h5 class="text-sm font-bold text-slate-800">Sneha Patel</h5>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Teleconsult Patient</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm relative shrink-0 w-80 sm:w-96 lg:w-[400px]">
                <i class="fas fa-quote-left text-4xl text-emerald-100 absolute top-6 right-6"></i>
                <div class="flex items-center gap-1 text-amber-400 text-sm mb-4">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <p class="text-slate-600 text-sm font-medium mb-6 leading-relaxed">"Their summer sale discounts are genuinely the best online. Plus, the packaging is always secure and tamper-proof. Highly recommend BioPharma to everyone."</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center font-bold">V</div>
                    <div>
                        <h5 class="text-sm font-bold text-slate-800">Vikram Singh</h5>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Verified Buyer</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm relative shrink-0 w-80 sm:w-96 lg:w-[400px]">
                <i class="fas fa-quote-left text-4xl text-emerald-100 absolute top-6 right-6"></i>
                <div class="flex items-center gap-1 text-amber-400 text-sm mb-4">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                </div>
                <p class="text-slate-600 text-sm font-medium mb-6 leading-relaxed">"Managing my BP medications has never been easier. The automated monthly refills mean I never run out of my essential pills. Fantastic service."</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-bold">M</div>
                    <div>
                        <h5 class="text-sm font-bold text-slate-800">Manoj Gupta</h5>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Subscriber</p>
                    </div>
                </div>
            </div>

            <div class="bg-white p-8 rounded-[2rem] border border-slate-100 shadow-sm relative shrink-0 w-80 sm:w-96 lg:w-[400px]">
                <i class="fas fa-quote-left text-4xl text-emerald-100 absolute top-6 right-6"></i>
                <div class="flex items-center gap-1 text-amber-400 text-sm mb-4">
                    <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                </div>
                <p class="text-slate-600 text-sm font-medium mb-6 leading-relaxed">"Ordered baby formula and diapers late at night and they delivered it first thing in the morning. BioPharma is truly reliable when you need them the most."</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center font-bold">N</div>
                    <div>
                        <h5 class="text-sm font-bold text-slate-800">Neha Reddy</h5>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Verified Buyer</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="bg-slate-900 rounded-[3rem] p-12 text-white mb-20 relative overflow-hidden shadow-2xl">
    <div class="text-center mb-12 relative z-10">
        <h2 class="text-3xl font-extrabold tracking-tight mb-2">Why Choose <span class="text-emerald-400">BioPharma?</span></h2>
        <p class="text-slate-400 text-sm font-medium">Your trusted partner in health and wellness</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-10 relative z-10">
        <div class="text-center group">
            <div class="w-16 h-16 bg-emerald-500/20 text-emerald-400 rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl transition-transform group-hover:scale-110">
                <i class="fas fa-truck-fast"></i>
            </div>
            <h4 class="text-xl font-bold mb-2">Express Delivery</h4>
            <p class="text-slate-400 text-sm italic">Within 60 minutes</p>
        </div>

        <div class="text-center group">
            <div class="w-16 h-16 bg-blue-500/20 text-blue-400 rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl transition-transform group-hover:scale-110">
                <img src="https://img.icons8.com/fluency/96/verified-badge.png" alt="100% Genuine" class="w-9 h-9 object-contain">
            </div>
            <h4 class="text-xl font-bold mb-2">100% Genuine</h4>
            <p class="text-slate-400 text-sm italic">Verified products only</p>
        </div>

        <div class="text-center group">
            <div class="w-16 h-16 bg-amber-500/20 text-amber-400 rounded-2xl flex items-center justify-center mx-auto mb-6 text-2xl transition-transform group-hover:scale-110">
                <i class="fas fa-hand-holding-heart"></i>
            </div>
            <h4 class="text-xl font-bold mb-2">Best Prices</h4>
            <p class="text-slate-400 text-sm italic">Up to 25% savings</p>
        </div>
    </div>

    <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-500/10 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-blue-500/10 rounded-full blur-3xl"></div>
</div>
           
                
        </main>

      <footer class="bg-white border-t border-slate-200">
    <div class="max-w-[1400px] mx-auto px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-8 lg:gap-12 mb-16">
            
            <div class="lg:col-span-2">
                <div class="flex items-center gap-2 mb-6 font-extrabold text-slate-800 text-2xl">
                    <div class="bg-emerald-600 p-2 rounded-lg text-white shadow-lg"><i class="fas fa-leaf text-sm"></i></div> BioPharma
                </div>
                <p class="text-slate-500 text-sm leading-relaxed mb-6 italic">Premium Digital Healthcare Partner.</p>
                <div class="flex gap-4">

                </div>
            </div>

            <div>
                <h5 class="font-black text-slate-900 mb-7 text-xs uppercase tracking-widest">Our Services</h5>
                <ul class="text-slate-500 text-sm space-y-4 font-bold">
                    <li class="footer-link cursor-pointer hover:text-emerald-600 transition">Medicine Order</li>
                    <li class="footer-link cursor-pointer hover:text-emerald-600 transition">Lab Test Bookings</li>
                    <li class="footer-link cursor-pointer hover:text-emerald-600 transition">Doctor Consultations</li>
                </ul>
            </div>

            <div>
                <h5 class="font-black text-slate-900 mb-7 text-xs uppercase tracking-widest">Support</h5>
                <ul class="text-slate-500 text-sm space-y-4 font-bold">
                    <li class="footer-link cursor-pointer hover:text-emerald-600 transition">Privacy Policy</li>
                    <li class="footer-link cursor-pointer hover:text-emerald-600 transition">Terms of Service</li>
                    <li class="footer-link cursor-pointer hover:text-emerald-600 transition">Contact us </li>
                </ul>
            </div>

            <div>
                <h5 class="font-black text-slate-900 mb-7 text-xs uppercase tracking-widest">Contact Us</h5>
                <ul class="text-slate-500 text-sm space-y-4 font-bold">
                    <li class="flex items-start gap-3 cursor-pointer hover:text-emerald-600 transition">
                        <i class="fas fa-map-marker-alt mt-1 text-emerald-600"></i>
                        <span>India</span>
                    </li>
                    <li class="flex items-center gap-3 cursor-pointer hover:text-emerald-600 transition">
                        <i class="fas fa-envelope text-emerald-600"></i>
                        <span>kspharma@gmail.com</span>
                    </li>
                    <li class="flex items-center gap-3 cursor-pointer hover:text-emerald-600 transition">
                        <i class="fas fa-phone-alt text-emerald-600"></i>
                        <span>+91 1800 123 4567</span>
                    </li>
                </ul>
            </div>

            <div>
                <h5 class="font-black text-slate-900 mb-7 text-xs uppercase tracking-widest">Need Help?</h5>
                <ul class="text-slate-500 text-sm space-y-4 font-bold">
                    <li class="footer-link cursor-pointer hover:text-emerald-600 transition">FAQs</li>
                    <li class="footer-link cursor-pointer hover:text-emerald-600 transition">Returns Policy</li>
                </ul>
            </div>

        </div>
        <div class="border-t border-slate-100 pt-10 text-center">
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">&copy; 2026 BioPharma.</p>
        </div>
    </div>
</footer>
    </div>

  

    <script>
        function toggleModal() {
            document.getElementById('loginModal').classList.toggle('active');
        }

        function setupCarousel(trackId, prevBtnId, nextBtnId) {
            const track = document.getElementById(trackId);
            const prevBtn = document.getElementById(prevBtnId);
            const nextBtn = document.getElementById(nextBtnId);
            
            if (!track || !prevBtn || !nextBtn) return;

            let currentScroll = 0;
            
            nextBtn.addEventListener('click', () => {
                const itemWidth = track.children[0].getBoundingClientRect().width;
                const gap = 24; 
                const maxScroll = track.scrollWidth - track.parentElement.clientWidth;
                
                currentScroll += itemWidth + gap;
                if (currentScroll > maxScroll) currentScroll = maxScroll;
                track.style.transform = `translateX(-${currentScroll}px)`;
            });
            
            prevBtn.addEventListener('click', () => {
                const itemWidth = track.children[0].getBoundingClientRect().width;
                const gap = 24;
                
                currentScroll -= itemWidth + gap;
                if (currentScroll < 0) currentScroll = 0;
                track.style.transform = `translateX(-${currentScroll}px)`;
            });
        }
        function toggleDropdown(event) {
    event.stopPropagation();
    const dropdown = document.getElementById('profileDropdown');
    dropdown.classList.toggle('show');
}

function openPrescriptionPicker() {
    const input = document.getElementById('prescriptionFile');
    if (input) input.click();
}

const prescriptionFileInput = document.getElementById('prescriptionFile');
if (prescriptionFileInput) {
    prescriptionFileInput.addEventListener('change', function () {
        const nameTag = document.getElementById('prescriptionFileName');
        if (!nameTag) return;
        nameTag.textContent = this.files && this.files.length ? this.files[0].name : 'No file selected';
    });
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
function switchTab(tab) {
    const loginForm = document.getElementById('loginForm');
    const signupForm = document.getElementById('signupForm');
    const loginTab = document.getElementById('loginTab');
    const signupTab = document.getElementById('signupTab');
    const modalTitle = document.getElementById('modalTitle');

    if (tab === 'login') {
        loginForm.classList.remove('hidden');
        signupForm.classList.add('hidden');
        loginTab.className = "flex-1 py-2.5 text-sm font-bold rounded-xl bg-white text-emerald-600 shadow-sm transition-all";
        signupTab.className = "flex-1 py-2.5 text-sm font-bold rounded-xl text-slate-500 transition-all";
        modalTitle.innerText = "Login to BioPharma";
    } else {
        signupForm.classList.remove('hidden');
        loginForm.classList.add('hidden');
        signupTab.className = "flex-1 py-2.5 text-sm font-bold rounded-xl bg-white text-emerald-600 shadow-sm transition-all";
        loginTab.className = "flex-1 py-2.5 text-sm font-bold rounded-xl text-slate-500 transition-all";
        modalTitle.innerText = "Create Your Account";
    }
}
// Function to trigger file input
function openPrescriptionPicker() {
    document.getElementById('prescriptionFile').click();
}

// Logic to show button after file selection
const fileInput = document.getElementById('prescriptionFile');
const fileNameDisplay = document.getElementById('prescriptionFileName');
const orderBtn = document.getElementById('submitPrescriptionBtn');

if (fileInput) {
    fileInput.addEventListener('change', function() {
        if (this.files && this.files.length > 0) {
            // File select ho gayi hai
            fileNameDisplay.textContent = "✅ " + this.files[0].name;
            orderBtn.classList.remove('hidden'); // Button se 'hidden' class hata di
            orderBtn.classList.add('block');    // Button ko display kar diya
        } else {
            // File select nahi hui
            fileNameDisplay.textContent = "No file selected";
            orderBtn.classList.add('hidden');
        }
    });
}
function showOrderBtn(input) {
    const fileName = document.getElementById('prescriptionFileName');
    const orderBtn = document.getElementById('submitPrescriptionBtn');

    if (input.files && input.files[0]) {
        // File ka naam dikhao
        fileName.innerHTML = "✅ " + input.files[0].name;
        // Button ko block level par show karo
        orderBtn.style.display = "block";
    } else {
        fileName.innerHTML = "No file selected";
        orderBtn.style.display = "none";
    }
}
        setupCarousel('brandsTrack', 'prevBrandBtn', 'nextBrandBtn');
        setupCarousel('reviewsTrack', 'prevReviewBtn', 'nextReviewBtn');
    </script>
</body>
</html>