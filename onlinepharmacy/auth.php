<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    if ($action == 'signup') {
        // --- SIGN UP LOGIC ---
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        
        // 1. Check if email already exists
        $check_sql = "SELECT id FROM users WHERE email = '$email'";
        $check_result = mysqli_query($conn, $check_sql);
        
        if (mysqli_num_rows($check_result) > 0) {
            echo "<script>alert('An account with this email already exists! Please login.'); window.location.href='index.php';</script>";
        } else {
            // 2. Securely hash the password and insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert_sql = "INSERT INTO users (email, password, username) VALUES ('$email', '$hashed_password', '$username')";
            
            if (mysqli_query($conn, $insert_sql)) {
                // 3. Log them in immediately after successful signup
                $_SESSION['user_id'] = mysqli_insert_id($conn);
                $_SESSION['username'] = ucfirst($username);
                echo "<script>window.location.href='index.php';</script>";
            } else {
                echo "<script>alert('Database Error during signup. Try again.'); window.location.href='index.php';</script>";
            }
        }

    } elseif ($action == 'login') {
        // --- LOGIN LOGIC ---
        $login_sql = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $login_sql);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            // Verify password matches hash in database
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['username'] = $row['username'];
                echo "<script>window.location.href='index.php';</script>";
            } else {
                echo "<script>alert('Incorrect Password!'); window.location.href='index.php';</script>";
            }
        } else {
            echo "<script>alert('No account found with this email. Please sign up!'); window.location.href='index.php';</script>";
        }
    }
}
?>