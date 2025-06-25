<?php
session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    echo "Unauthorized!";
    exit;
}

$user_id = $_SESSION["user_id"];
$enquiry_id = $_POST["id"] ?? null;

if ($enquiry_id) {
    // Check if enquiry belongs to the user
    $check = mysqli_query($conn, "SELECT * FROM enquiries WHERE id = $enquiry_id AND user_id = $user_id");
    if (mysqli_num_rows($check) > 0) {
        mysqli_query($conn, "DELETE FROM enquiries WHERE id = $enquiry_id");
        echo "Deleted";
    } else {
        echo "You can only delete your own enquiries.";
    }
} else {
    echo "Invalid ID.";
}
?>
