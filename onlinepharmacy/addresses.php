<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// 1. Handle Admin Adding a New Address
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_admin_address'])) {
    $selected_user_id = intval($_POST['user_id']);
    $label = mysqli_real_escape_string($conn, $_POST['label']);
    $full_address = mysqli_real_escape_string($conn, $_POST['full_address']);
    $pincode = mysqli_real_escape_string($conn, $_POST['pincode']);

    $insert_sql = "INSERT INTO addresses (user_id, label, full_address, pincode) VALUES ('$selected_user_id', '$label', '$full_address', '$pincode')";
    
    if (mysqli_query($conn, $insert_sql)) {
        // Refresh the same page automatically
        echo "<script>window.location.href=window.location.href;</script>";
    } else {
        echo "<script>alert('Error adding address.');</script>";
    }
}

// 2. Handle Delete Action
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_sql = "DELETE FROM addresses WHERE id = $delete_id";
    if (mysqli_query($conn, $delete_sql)) {
        // Remove query parameters to prevent re-deletion on refresh
        echo "<script>alert('Address deleted successfully!'); window.location.href=window.location.pathname;</script>";
    }
}

// 3. Fetch all users for the dropdown menu in the Add Form
$users_list = [];
$user_query = "SELECT id, username, email FROM users";
$user_result = mysqli_query($conn, $user_query);
if ($user_result) {
    while ($u = mysqli_fetch_assoc($user_result)) {
        $users_list[] = $u;
    }
}

// 4. Fetch all addresses WITH User details to display in the table
$addresses = [];
$query = "SELECT addresses.*, users.username, users.email 
          FROM addresses 
          LEFT JOIN users ON addresses.user_id = users.id 
          ORDER BY addresses.id DESC";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $addresses[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Addresses | BioPharma Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .glass-nav { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); }
        .modal { display: none; background: rgba(0,0,0,0.5); }
        .modal.active { display: flex; }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <!-- Add Address Modal (Admin Side) -->
    <div id="addModal" class="modal fixed inset-0 z-[100] items-center justify-center p-4 backdrop-blur-sm">
        <div class="bg-white w-full max-w-md rounded-[2.5rem] p-8 shadow-2xl relative border border-slate-100">
            <button onclick="toggleModal()" class="absolute right-6 top-6 text-slate-400 hover:text-slate-800 transition bg-slate-50 w-8 h-8 rounded-full flex items-center justify-center">
                <i class="fas fa-times"></i>
            </button>
            
            <div class="text-center mb-6">
                <div class="bg-emerald-100 w-12 h-12 rounded-2xl flex items-center justify-center text-emerald-600 mx-auto mb-4">
                    <i class="fas fa-map-marker-alt text-xl"></i>
                </div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">Add Customer Address</h2>
            </div>

            <!-- Fix: action="" rakha gaya hai taaki 404 error na aaye -->
            <form action="" method="POST" class="space-y-4">
                <input type="hidden" name="add_admin_address" value="1">
                
                <!-- Select Customer -->
                <select name="user_id" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-4 px-6 focus:ring-2 focus:ring-emerald-500 transition text-sm outline-none font-bold text-slate-700 cursor-pointer">
                    <option value="" disabled selected>Select Customer</option>
                    <?php foreach ($users_list as $user): ?>
                        <option value="<?php echo $user['id']; ?>">
                            <?php echo htmlspecialchars($user['username'] . " (" . $user['email'] . ")"); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Select Label -->
                <select name="label" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-4 px-6 focus:ring-2 focus:ring-emerald-500 transition text-sm outline-none font-bold text-slate-700 cursor-pointer">
                    <option value="" disabled selected>Select Label (Home, Office)</option>
                    <option value="Home">Home</option>
                    <option value="Office">Office</option>
                    <option value="Other">Other</option>
                </select>

                <!-- Full Address -->
                <textarea name="full_address" required rows="3" placeholder="Full Address (Flat No, Street, City)" class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-4 px-6 focus:ring-2 focus:ring-emerald-500 transition text-sm outline-none resize-none"></textarea>
                
                <!-- Pincode -->
                <input type="text" name="pincode" required placeholder="Pincode" maxlength="6" class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-4 px-6 focus:ring-2 focus:ring-emerald-500 transition text-sm outline-none">
                
                <button type="submit" class="w-full bg-emerald-600 text-white py-4 rounded-2xl font-bold shadow-xl shadow-emerald-200 mt-4 hover:bg-emerald-700 hover:-translate-y-1 transition-all">Save Address</button>
            </form>
        </div>
    </div>

    <!-- Top Navigation -->
    <header class="glass-nav border-b border-slate-200 sticky top-0 z-50 px-8 py-4 flex justify-between items-center shadow-sm">
        <a href="index.php" class="flex items-center gap-3">
            <div class="bg-emerald-600 p-2 rounded-lg text-white shadow-lg">
                <i class="fas fa-leaf text-sm"></i>
            </div>
            <span class="text-xl font-extrabold text-slate-800 tracking-tight">BioPharma Admin</span>
        </a>
        
        <div class="flex items-center gap-4">
            <a href="index.php" class="text-sm font-bold text-slate-500 hover:text-emerald-600 transition bg-white px-5 py-2.5 rounded-xl border border-slate-200 shadow-sm hover:shadow-md">
                <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
            </a>
        </div>
    </header>

    <main class="max-w-7xl mx-auto p-6 py-12 flex-grow w-full text-left">
        
        <!-- Page Header with ADD Button -->
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Manage <span class="text-emerald-600">Customer Addresses</span></h1>
                <p class="text-slate-500 font-medium mt-1">View and manage all saved delivery locations.</p>
            </div>
            <button onclick="toggleModal()" class="bg-emerald-600 text-white px-6 py-3 rounded-2xl font-bold text-sm shadow-lg shadow-emerald-100 hover:bg-emerald-700 transition-all flex items-center gap-2">
                <i class="fas fa-plus"></i> Add Address
            </button>
        </div>

        <!-- Database Table Wrapper -->
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    
                    <!-- Table Headers -->
                    <thead>
                        <tr class="bg-emerald-50 border-b border-emerald-100">
                            <th class="p-5 text-xs font-black text-emerald-800 uppercase tracking-widest">ID</th>
                            <th class="p-5 text-xs font-black text-emerald-800 uppercase tracking-widest">Customer Info</th>
                            <th class="p-5 text-xs font-black text-emerald-800 uppercase tracking-widest">Address Label</th>
                            <th class="p-5 text-xs font-black text-emerald-800 uppercase tracking-widest">Full Address</th>
                            <th class="p-5 text-xs font-black text-emerald-800 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    
                    <!-- Table Body -->
                    <tbody class="divide-y divide-slate-50">
                        <?php if (empty($addresses)): ?>
                            <tr>
                                <td colspan="5" class="p-10 text-center text-slate-400 font-semibold">
                                    <i class="fas fa-map-marked-alt text-3xl mb-3 block text-slate-300"></i>
                                    No customer addresses found in the database.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($addresses as $addr): ?>
                                <tr class="hover:bg-slate-50/50 transition">
                                    
                                    <td class="p-5 text-sm text-slate-400 font-bold">#<?php echo $addr['id']; ?></td>
                                    
                                    <td class="p-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-xs">
                                                <?php echo isset($addr['username']) ? strtoupper(substr($addr['username'], 0, 1)) : 'U'; ?>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-800"><?php echo htmlspecialchars($addr['username'] ?? 'Unknown User'); ?></p>
                                                <p class="text-[10px] font-bold text-slate-400"><?php echo htmlspecialchars($addr['email'] ?? 'No Email'); ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="p-5">
                                        <?php 
                                            $icon = 'fa-map-marker-alt';
                                            if($addr['label'] == 'Home') $icon = 'fa-home';
                                            if($addr['label'] == 'Office') $icon = 'fa-briefcase';
                                        ?>
                                        <div class="flex items-center gap-2">
                                            <i class="fas <?php echo $icon; ?> text-emerald-500"></i>
                                            <span class="text-sm font-bold text-slate-800"><?php echo htmlspecialchars($addr['label']); ?></span>
                                        </div>
                                        <p class="text-xs font-semibold text-slate-500 mt-1">PIN: <?php echo htmlspecialchars($addr['pincode']); ?></p>
                                    </td>

                                    <td class="p-5">
                                        <p class="text-sm font-medium text-slate-600 max-w-xs leading-relaxed">
                                            <?php echo htmlspecialchars($addr['full_address']); ?>
                                        </p>
                                    </td>
                                    
                                    <td class="p-5 text-right">
                                        <!-- Fix: Current page par re-direct karne ka code -->
                                        <a href="?delete_id=<?php echo $addr['id']; ?>" onclick="return confirm('Are you sure you want to delete this address?');" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition shadow-sm">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        function toggleModal() {
            document.getElementById('addModal').classList.toggle('active');
        }
    </script>
</body>
</html>