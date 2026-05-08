<?php
session_start();
include('config.php');

// Agar user login nahi hai toh pop-up dikhao aur wapas bhejo
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login first to upload a prescription!'); window.location.href='index.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['prescription'])) {
    $user_id = $_SESSION['user_id'];
    $file = $_FILES['prescription'];
    
    // Upload folder banayein agar nahi hai toh
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    // Safe file name generate karein
    $file_extension = pathinfo($file["name"], PATHINFO_EXTENSION);
    $new_file_name = "rx_user" . $user_id . "_" . time() . "." . $file_extension;
    $target_file = $target_dir . $new_file_name;
    
    // File upload logic
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        // Database mein save karein
        $sql = "INSERT INTO prescriptions (user_id, file_name) VALUES ('$user_id', '$new_file_name')";
        
        if (mysqli_query($conn, $sql)) {
            // YAHAN HAI AAPKA SUCCESS POP-UP
            echo "<script>
                alert('  order Successfull.');
                window.location.href='order_success.php';
            </script>";
            exit();
        } else {
            echo "<script>alert('Database Error. Order failed!'); window.location.href='index.php';</script>";
        }
    } else {
        echo "<script>alert('Error uploading file. Please try again.'); window.location.href='index.php';</script>";
    }
} else {
    // Agar koi direct is file par aata hai toh wapas index par bhej do
    header("Location: index.php");
    exit();
}
?>