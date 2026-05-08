<?php
session_start();
include('config.php');

// 1. Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// 2. Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = mysqli_real_escape_string($conn, $_POST['username']);
    $new_email = mysqli_real_escape_string($conn, $_POST['email']);
    $new_password = $_POST['password'];

    // Update password only if the user types a new one
    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_sql = "UPDATE users SET username='$new_username', email='$new_email', password='$hashed_password' WHERE id=$user_id";
    } else {
        // Update without changing the password
        $update_sql = "UPDATE users SET username='$new_username', email='$new_email' WHERE id=$user_id";
    }

    if (mysqli_query($conn, $update_sql)) {
        $_SESSION['username'] = ucfirst($new_username);
        $message = "
        <div class='bg-emerald-50 text-emerald-700 p-4 rounded-2xl text-sm font-bold mb-8 border border-emerald-100 flex items-center gap-3 shadow-sm'>
            <i class='fas fa-check-circle text-xl text-emerald-500'></i> 
            Profile updated successfully!
        </div>";
    } else {
        $message = "
        <div class='bg-rose-50 text-rose-700 p-4 rounded-2xl text-sm font-bold mb-8 border border-rose-100 flex items-center gap-3 shadow-sm'>
            <i class='fas fa-exclamation-circle text-xl text-rose-500'></i> 
            Error updating profile. Please try again.
        </div>";
    }
}

// 3. Fetch current user data
$fetch_sql = "SELECT username, email FROM users WHERE id=$user_id";
$result = mysqli_query($conn, $fetch_sql);
$user_data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BioPharma | Edit Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex flex-col items-center justify-center p-6">

    <div class="w-full max-w-2xl">
        <!-- Top Navigation -->
        <div class="flex justify-between items-center mb-8 px-4">
            <div class="flex items-center gap-3 font-extrabold text-slate-800 text-2xl tracking-tight">
                <div class="bg-emerald-600 w-10 h-10 rounded-xl flex items-center justify-center text-white shadow-lg shadow-emerald-200">
                    <i class="fas fa-leaf text-lg"></i>
                </div>
                BioPharma
            </div>
            <a href="index.php" class="text-sm font-bold text-slate-500 hover:text-emerald-600 transition flex items-center gap-2 bg-white px-5 py-2.5 rounded-xl border border-slate-200 shadow-sm hover:shadow-md">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-[3rem] p-10 md:p-14 shadow-2xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden">
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-emerald-400/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10">
                <div class="text-center mb-10">
                    <!-- Profile Initial -->
                    <div class="w-24 h-24 mx-auto bg-gradient-to-tr from-emerald-600 to-teal-400 rounded-[2rem] text-white flex items-center justify-center text-4xl font-black shadow-xl shadow-emerald-200 mb-6 border-4 border-white transform rotate-3">
                        <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                    </div>
                    <h2 class="text-3xl font-black text-slate-800 tracking-tight">Personal Details</h2>
                    <p class="text-slate-500 font-medium mt-2">Manage your account information</p>
                </div>

                <?php echo $message; ?>

                <form action="edit_profile.php" method="POST" class="space-y-6">
                    
                    <!-- Username Field -->
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-widest mb-2 ml-2">Full Name</label>
                        <div class="relative">
                            <i class="far fa-user absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                            <input type="text" name="username" required 
                                value="<?php echo htmlspecialchars($user_data['username']); ?>" 
                                class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-4 pl-14 pr-6 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition text-sm font-bold text-slate-800 outline-none shadow-inner">
                        </div>
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-widest mb-2 ml-2">Email Address</label>
                        <div class="relative">
                            <i class="far fa-envelope absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                            <input type="email" name="email" required 
                                value="<?php echo htmlspecialchars($user_data['email']); ?>" 
                                class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-4 pl-14 pr-6 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition text-sm font-bold text-slate-800 outline-none shadow-inner">
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="pt-4 border-t border-slate-100">
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-widest mb-2 ml-2 flex justify-between items-center">
                            <span>New Password</span>
                            <span class="text-[10px] text-emerald-500 bg-emerald-50 px-2 py-1 rounded-lg">Optional</span>
                        </label>
                        <div class="relative">
                            <i class="fas fa-lock absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                            <input type="password" name="password" placeholder="Leave blank to keep current password" 
                                class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-4 pl-14 pr-6 focus:ring-2 focus:ring-emerald-500 focus:bg-white transition text-sm font-medium outline-none shadow-inner placeholder:text-slate-400">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-8">
                        <button type="submit" class="w-full bg-gradient-to-r from-emerald-600 to-teal-500 text-white py-4 rounded-2xl font-extrabold shadow-xl shadow-emerald-200 hover:-translate-y-1 hover:shadow-2xl transition-all duration-300">
                            Save Changes
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</body>
</html>