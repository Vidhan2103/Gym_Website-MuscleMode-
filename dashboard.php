<?php
session_start();
include "db.php";

// Redirect if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];

// Get user name
$user_query = mysqli_query($conn, "SELECT name FROM users WHERE id = $user_id");
$user_data = mysqli_fetch_assoc($user_query);
$name = $user_data["name"];

// Get recent enquiries
$enquiries = mysqli_query($conn, "SELECT * FROM enquiries WHERE user_id = $user_id ORDER BY submitted_at DESC");

// Count enquiries per plan
$count_result = mysqli_query($conn, "
    SELECT plan, COUNT(*) as count FROM enquiries 
    WHERE user_id = $user_id 
    GROUP BY plan
");
$plan_counts = [];
while ($row = mysqli_fetch_assoc($count_result)) {
    $plan_counts[$row['plan']] = $row['count'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      background: url('images/gym-bg.jpg') no-repeat center center fixed;
      background-size: cover;
      background-attachment: fixed;
      color: white;
    }

    .card {
      background-color: rgba(0, 0, 0, 0.7);
      color: white;
      border: none;
    }

    .card-header {
      background-color: rgba(0, 0, 0, 0.85);
    }

    a.btn {
      color: white;
    }

    a.btn:hover {
      background-color: white;
      color: black;
    }
  </style>
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand"><i class="fa-solid fa-dumbbell"></i> MuscleMode-->Dashboard</span>
        <a href="logout.php" class="btn btn-outline-light">Logout</a>
    </div>
</nav>

<div class="container mt-4">
    <h4>Welcome, <?= htmlspecialchars($name)?> ! </h4>

    <!-- Plan Summary -->
    <div class="row mt-3">
        <?php foreach (["Monthly", "Quarterly", "Yearly"] as $plan): ?>
        <div class="col-md-4">
            <div class="card border-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= $plan ?></h5>
                    <p class="card-text"><?= $plan_counts[$plan] ?? 0 ?> Enquiries</p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<!-- Dashboard Cards for Plans -->
<div class="container mt-4">
  <div class="row">
    <div class="col-md-6">
      <div class="card border-primary mb-3">
        <div class="card-header bg-primary text-white"><i class="fa-solid fa-dumbbell"></i> Workout Plans</div>
        <div class="card-body">
          <p class="card-text">Access predefined workout routines tailored for your level.</p>
          <a href="plans/beginner.php" class="btn btn-outline-primary btn-sm">Beginner Plan</a>
          <a href="plans/intermediate.php" class="btn btn-outline-primary btn-sm">Intermediate Plan</a>
          <a href="plans/advanced.php" class="btn btn-outline-primary btn-sm">Advanced Plan</a>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card border-success mb-3">
        <div class="card-header bg-success text-white"><i class="fa-solid fa-apple-alt"></i> Diet Plans</div>
        <div class="card-body">
          <p class="card-text">Check out meal plans based on your fitness goals.</p>
          <a href="diets/muscle_gain.php" class="btn btn-outline-success btn-sm">Muscle Gain</a>
          <a href="diets/fat_loss.php" class="btn btn-outline-success btn-sm">Fat Loss</a>
          <a href="diets/maintenance.php" class="btn btn-outline-success btn-sm">Maintenance</a>
        </div>
      </div>
    </div>
  </div>
</div>

    <!-- Enquiry Form -->
    <div class="card mt-4">
        <div class="card-header bg-primary text-white">
            Submit a New Enquiry
        </div>
        <div class="card-body">
            <form id="enquiryForm">
                <input type="hidden" name="user_id" value="<?= $user_id ?>">
                <select name="plan" class="form-select mb-2" required>
                    <option value="">Choose Plan</option>
                    <option>Monthly</option>
                    <option>Quarterly</option>
                    <option>Yearly</option>
                </select>
                <textarea name="message" class="form-control mb-2" placeholder="Your fitness goal..." required></textarea>
                <button class="btn btn-success">Submit Enquiry</button>
            </form>
            <div id="response" class="mt-2"></div>
        </div>
    </div>

    <!-- Recent Enquiries with Delete Option -->
    <div class="card mt-4">
        <div class="card-header bg-info text-white">
            Your Recent Enquiries
        </div>
        <ul class="list-group list-group-flush" id="enquiryList">
            <?php if (mysqli_num_rows($enquiries) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($enquiries)): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-start">
                        <div>
                            <strong><?= $row['plan'] ?></strong> - <?= htmlspecialchars($row['message']) ?><br>
                            <small class="text-muted"><?= date("d M, Y h:i A", strtotime($row['submitted_at'])) ?></small>
                        </div>
                        <button class="btn btn-sm btn-danger ms-3 deleteBtn" data-id="<?= $row['id'] ?>">Delete</button>
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <li class="list-group-item">No enquiries submitted yet.</li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<!-- AJAX Scripts -->
<script>
$("#enquiryForm").submit(function(e) {
    e.preventDefault();
    $.post("enquiry.php", $(this).serialize(), function(data) {
        $("#response").html("<div class='alert alert-success'>" + data + "</div>");
        $("#enquiryForm")[0].reset();
        setTimeout(() => location.reload(), 1500);
    });
});

$(".deleteBtn").click(function() {
    const enquiryId = $(this).data("id");
    if (confirm("Are you sure you want to delete this enquiry?")) {
        $.post("delete_user_enquiry.php", { id: enquiryId }, function(response) {
            alert(response);
            location.reload();
        });
    }
});
</script>

</body>
</html>
