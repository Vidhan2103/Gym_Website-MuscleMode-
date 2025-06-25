<?php
include "db.php";

if (isset($_GET["id"])) {
    $id = $_GET["id"];
    $sql = "DELETE FROM enquiries WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        header("Location: admin.php");
    } else {
        echo "Failed to delete.";
    }
}
?>
