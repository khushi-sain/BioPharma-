<?php
include('config.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $test_name = mysqli_real_escape_string($conn, $_POST['test_name']);
    $date = mysqli_real_escape_string($conn, $_POST['booking_date']);
    $time = mysqli_real_escape_string($conn, $_POST['booking_time']);
    $contractor_id = intval($_POST['contractor_id']);

    $sql = "INSERT INTO test_bookings (user_id, test_name, booking_date, booking_time, contractor_id, status) 
            VALUES ('$user_id', '$test_name', '$date', '$time', '$contractor_id', 'Pending')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>
            alert('Booking Successful! Your report will be ready in 7 days after sample collection.');
            window.location.href = 'index.php';
        </script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>