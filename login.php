<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        header("Location: dashboard.php");
    } else {
        $error = "Invalid credentials!";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Login - MuscleMode</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
 body {
    background: url('images/gym-bg-1.jpg') no-repeat center center fixed;
    background-size: cover;
    background-attachment: fixed;
    color: white;
  }
</style>
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow-lg" style="max-width: 400px; width: 100%;">
        <h3 class="text-center mb-3"><i class="fa-solid fa-dumbbell text-primary"></i> MuscleMode Login</h3>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control" required>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" required>
                </div>
            </div>
            <button class="btn btn-primary w-100">Login</button>
        </form>
        <p class="mt-3 text-center">Don’t have an account? <a href="signup.php">Sign up</a></p>
    </div>
</div>

</body>
</html>

