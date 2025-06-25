<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

    if (mysqli_query($conn, $sql)) {
        $_SESSION["user_id"] = mysqli_insert_id($conn);
        header("Location: dashboard.php");
    } else {
        $error = "Email already exists!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign Up - FitZone</title>
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
    <div class="card p-4 shadow-lg" style="max-width: 450px; width: 100%;">
        <h3 class="text-center mb-3"><i class="fa-solid fa-user-plus text-success"></i> Create Account</h3>
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Name</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fa-solid fa-user"></i></span>
                    <input type="text" name="name" class="form-control" required>
                </div>
            </div>
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
            <button class="btn btn-success w-100">Sign Up</button>
        </form>
        <p class="mt-3 text-center">Already have an account? <a href="login.php">Login</a></p>
    </div>
</div>

</body>
</html>
