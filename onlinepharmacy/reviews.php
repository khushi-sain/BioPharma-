<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('config.php'); 

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle saving a new review
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_review'])) {
    $medicine_id = intval($_POST['medicine_id']);
    $rating = intval($_POST['rating']);
    $review_text = mysqli_real_escape_string($conn, $_POST['review_text']);

    $insert_sql = "INSERT INTO reviews (user_id, medicine_id, rating, review_text) VALUES ('$user_id', '$medicine_id', '$rating', '$review_text')";
    
    if (mysqli_query($conn, $insert_sql)) {
        echo "<script>window.location.href=window.location.href;</script>";
    } else {
        echo "<script>alert('Error submitting review. Please try again.');</script>";
    }
}

// Handle deleting a review
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_sql = "DELETE FROM reviews WHERE id = $delete_id AND user_id = $user_id";
    mysqli_query($conn, $delete_sql);
    echo "<script>window.location.href=window.location.pathname;</script>";
}

// Fetch available medicines for the dropdown
$medicines_list = [];
$med_query = mysqli_query($conn, "SELECT id, name FROM medicines ORDER BY name ASC");
if ($med_query) {
    while ($m = mysqli_fetch_assoc($med_query)) {
        $medicines_list[] = $m;
    }
}

// Fetch the user's past reviews
$reviews = [];
$review_query = "SELECT r.*, m.name as medicine_name 
                 FROM reviews r 
                 LEFT JOIN medicines m ON r.medicine_id = m.id 
                 WHERE r.user_id = $user_id 
                 ORDER BY r.id DESC";
$result = mysqli_query($conn, $review_query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $reviews[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reviews | BioPharma</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .glass-nav { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); }
        .profile-dropdown { display: none; transform-origin: top right; transition: all 0.2s ease; z-index: 100; }
        .profile-dropdown.show { display: block; animation: slideIn 0.2s ease-out; }
        @keyframes slideIn { from { opacity: 0; transform: translateY(10px) scale(0.95); } to { opacity: 1; transform: translateY(0) scale(1); } }
        .modal { display: none; background: rgba(0,0,0,0.5); }
        .modal.active { display: flex; }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <!-- Write a Review Modal -->
    <div id="reviewModal" class="modal fixed inset-0 z-[110] items-center justify-center p-4 backdrop-blur-sm">
        <div class="bg-white w-full max-w-md rounded-[2.5rem] p-8 shadow-2xl relative border border-slate-100">
            <button onclick="toggleReviewModal()" class="absolute right-6 top-6 text-slate-400 hover:text-slate-800 transition bg-slate-50 w-8 h-8 rounded-full flex items-center justify-center">
                <i class="fas fa-times"></i>
            </button>
            <div class="text-center mb-6">
                <div class="bg-emerald-100 w-12 h-12 rounded-2xl flex items-center justify-center text-[#059669] mx-auto mb-4">
                    <i class="fas fa-star text-xl"></i>
                </div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">Write a Review</h2>
            </div>
            
            <form action="" method="POST" class="space-y-4">
                <input type="hidden" name="submit_review" value="1">
                
                <!-- Product Selection -->
                <select name="medicine_id" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-4 px-6 focus:ring-2 focus:ring-[#059669] transition text-sm outline-none font-bold text-slate-700 cursor-pointer">
                    <option value="" disabled selected>Select Product to Review</option>
                    <?php foreach ($medicines_list as $med): ?>
                        <option value="<?php echo $med['id']; ?>"><?php echo htmlspecialchars($med['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            
            <!-- NEW: Name Field -->
            <div class="relative">
                <input type="text" name="reviewer_name" required 
                       value="<?php echo $_SESSION['username']; ?>" 
                       placeholder="Your Display Name" 
                       class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-4 px-6 focus:ring-2 focus:ring-[#059669] transition text-sm outline-none font-bold text-slate-700">
            </div>
                <!-- Rating -->
                <select name="rating" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-4 px-6 focus:ring-2 focus:ring-[#059669] transition text-sm outline-none font-bold text-slate-700 cursor-pointer">
                    <option value="" disabled selected>Rate this product</option>
                    <option value="5">⭐⭐⭐⭐⭐ (5/5) - Excellent</option>
                    <option value="4">⭐⭐⭐⭐ (4/5) - Very Good</option>
                    <option value="3">⭐⭐⭐ (3/5) - Average</option>
                    <option value="2">⭐⭐ (2/5) - Poor</option>
                    <option value="1">⭐ (1/5) - Terrible</option>
                </select>

                <!-- Review Text -->
                <textarea name="review_text" required rows="4" placeholder="Tell others about your experience with this product..." class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-4 px-6 focus:ring-2 focus:ring-[#059669] transition text-sm outline-none resize-none"></textarea>
                
                <button type="submit" class="w-full bg-[#059669] text-white py-4 rounded-2xl font-bold shadow-xl shadow-emerald-200 mt-4 hover:bg-[#047857] hover:-translate-y-1 transition-all">Post Review</button>
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
                        <a href="reviews.php" class="block px-5 py-3 bg-emerald-50 rounded-xl text-[#059669] font-bold text-sm transition-all">Customer Reviews</a>
                        <a href="logout.php" class="block px-5 py-3 hover:bg-rose-50 rounded-xl text-slate-600 hover:text-rose-600 font-bold text-sm transition-all hover:translate-x-1">Sign Out</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto p-6 py-12 flex-grow w-full text-left">
        
        <!-- Header & Add Review Button -->
        <div class="flex justify-between items-center mb-10">
            <div class="flex items-center gap-4">
                <a href="index.php" class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-slate-400 hover:text-[#059669] shadow-sm transition-colors border border-slate-100">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">My <span class="text-[#059669]">Reviews</span></h1>
            </div>
            <button onclick="toggleReviewModal()" class="bg-[#059669] text-white px-6 py-3 rounded-2xl font-bold text-xs shadow-lg shadow-emerald-100 hover:bg-[#047857] transition-all flex items-center gap-2">
                <i class="fas fa-pen"></i> Write Review
            </button>
        </div>

        <?php if (empty($reviews)): ?>
            <!-- Empty State -->
            <div class="bg-white p-10 md:p-20 rounded-[3rem] border border-slate-100 shadow-sm text-center">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-8 text-slate-300">
                    <i class="fas fa-comment-dots text-4xl"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 mb-2">No reviews yet!</h3>
                <p class="text-slate-400 font-medium mb-10 italic leading-relaxed max-w-md mx-auto">Your feedback helps millions make better health decisions. Start reviewing your purchased items.</p>
                <button onclick="toggleReviewModal()" class="bg-[#059669] text-white px-10 py-4 rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-xl shadow-emerald-100 hover:bg-[#047857] hover:scale-105 transition inline-block">
                    Write Your First Review
                </button>
            </div>
        <?php else: ?>
            <!-- Reviews Grid -->
            <div class="grid grid-cols-1 gap-6">
                <?php foreach ($reviews as $review): ?>
                    <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-lg transition-all group relative">
                        <a href="?delete_id=<?php echo $review['id']; ?>" onclick="return confirm('Are you sure you want to delete this review?');" class="absolute top-6 right-6 text-slate-300 hover:text-rose-500 transition">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                        
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-[#059669] flex items-center justify-center text-lg border border-emerald-100">
                                <i class="fas fa-prescription-bottle-alt"></i>
                            </div>
                            <div>
                                <h4 class="font-extrabold text-slate-800"><?php echo htmlspecialchars($review['medicine_name'] ?? 'Unknown Product'); ?></h4>
                                <div class="flex items-center gap-1 text-sm mt-1">
                                    <?php 
                                        $rating = intval($review['rating']);
                                        for($i=1; $i<=5; $i++) {
                                            if($i <= $rating) {
                                                echo '<i class="fas fa-star text-amber-400"></i>';
                                            } else {
                                                echo '<i class="far fa-star text-slate-200"></i>';
                                            }
                                        }
                                    ?>
                                    <span class="text-xs text-slate-400 ml-2 font-medium">
                                        <?php echo date('M d, Y', strtotime($review['created_at'])); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <p class="text-slate-600 text-sm leading-relaxed font-medium bg-slate-50 p-5 rounded-2xl border border-slate-100">
                            "<?php echo nl2br(htmlspecialchars($review['review_text'])); ?>"
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </main>

    <footer class="py-12 text-center text-slate-400 border-t border-slate-50 bg-white mt-auto">
        <p class="text-[10px] font-black uppercase tracking-widest">&copy; 2026 BioPharma Feedback Center</p>
    </footer>

    <script>
        function toggleReviewModal() { 
            document.getElementById('reviewModal').classList.toggle('active'); 
        }
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