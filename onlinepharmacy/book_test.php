<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
include('config.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$test_name = isset($_GET['test']) ? $_GET['test'] : 'General Lab Test';

// Fetch Contractors List
$contractors = mysqli_query($conn, "SELECT id, name FROM contractors WHERE status = 'active'");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book <?php echo $test_name; ?> | BioPharma</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6">

    <div class="max-w-2xl w-full bg-white rounded-[3rem] shadow-2xl p-8 md:p-12 border border-slate-100">
        <div class="mb-8">
            <h1 class="text-3xl font-black text-slate-800">Book Your <span class="text-emerald-600"><?php echo htmlspecialchars($test_name); ?></span></h1>
            <p class="text-slate-500 font-medium mt-2">Please select your preferred time and contractor for the sample collection.</p>
        </div>

        <form action="process_booking.php" method="POST" class="space-y-6">
            <input type="hidden" name="test_name" value="<?php echo $test_name; ?>">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Date Selection -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-2">Preferred Date</label>
                    <input type="date" name="booking_date" required min="<?php echo date('Y-m-d'); ?>" 
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-4 px-6 focus:ring-2 focus:ring-emerald-500 outline-none font-bold">
                </div>

                <!-- Time Selection -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 ml-2">Preferred Time</label>
                    <input type="time" name="booking_time" required 
                           class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-4 px-6 focus:ring-2 focus:ring-emerald-500 outline-none font-bold">
                </div>
            </div>

            <!-- Contractor Selection -->
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2 ml-2">Choose Collection Partner (Contractor)</label>
                <select name="contractor_id" required class="w-full bg-slate-50 border border-slate-200 rounded-2xl py-4 px-6 focus:ring-2 focus:ring-emerald-500 outline-none font-bold text-slate-700 appearance-none cursor-pointer">
                    <option value="" disabled selected>Select a Contractor</option>
                    <?php while($row = mysqli_fetch_assoc($contractors)): ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Report Info Box -->
            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-5 flex gap-4 items-start">
                <div class="text-blue-500 mt-1"><i class="fas fa-info-circle"></i></div>
                <p class="text-sm text-blue-700 font-medium leading-relaxed">
                    <strong>Note:</strong> Sample collection will happen at your doorstep. After the collection, your digital report will be available on your dashboard within <strong>7 days</strong>.
                </p>
            </div>

            <button type="submit" class="w-full bg-emerald-600 text-white py-5 rounded-[2rem] font-black text-lg shadow-xl shadow-emerald-100 hover:bg-emerald-700 transition transform hover:-translate-y-1">
                Apply & Confirm Booking
            </button>
        </form>
    </div>

</body>
</html>