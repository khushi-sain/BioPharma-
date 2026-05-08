<?php
$host = "localhost";
$user = "root";
$pass = ""; 
$dbname = "pharmacy_management";

echo "Testing DB connection...\n";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Connected successfully to $dbname\n";

$result = mysqli_query($conn, "SHOW TABLES");
if ($result) {
    echo "Tables in DB:\n";
    while ($row = mysqli_fetch_array($result)) {
        echo "- " . $row[0] . "\n";
    }
} else {
    echo "No tables or query failed.\n";
}

mysqli_close($conn);
?>
