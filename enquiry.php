<?php
include "db.php";
include "send_mail.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_POST["user_id"];
    $plan = mysqli_real_escape_string($conn, $_POST["plan"]);
    $message = mysqli_real_escape_string($conn, $_POST["message"]);

    $sql = "INSERT INTO enquiries (user_id, plan, message) VALUES ('$user_id', '$plan', '$message')";

    if (mysqli_query($conn, $sql)) {
        $to = "vidhansharma2103@gmail.com"; // Recipient
        $subject = "New Gym Plan Enquiry Submitted";
        $body = "A user has submitted an enquiry:\n\nPlan: $plan\nMessage: $message";

        if (sendEmail($to, $subject, $body)) {
            echo "Enquiry submitted successfully! Email sent.";
        } else {
            echo "Enquiry submitted successfully! (Email failed)";
        }
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
