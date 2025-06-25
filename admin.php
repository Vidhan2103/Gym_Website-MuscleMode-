<?php
include "db.php";

// Optional: Protect this page with admin login (future upgrade)
$result = mysqli_query($conn, "
    SELECT enquiries.id, users.name, users.email, enquiries.plan, enquiries.message, enquiries.submitted_at
    FROM enquiries
    JOIN users ON enquiries.user_id = users.id
    ORDER BY enquiries.submitted_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel – Enquiries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>All Plan Enquiries</h2>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Plan</th>
                <th>Message</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= $row['name'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['plan'] ?></td>
                <td><?= $row['message'] ?></td>
                <td><?= $row['submitted_at'] ?></td>
                <td>
                    <a href="delete_enquiry.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                       onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
</body>
</html>
