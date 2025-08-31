<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2>Welcome, <?php echo $_SESSION['full_name']; ?> 🎉</h2>
    <a href="../../public/mybooking.php" class="btn btn-primary">My booking</a>
    <a class="btn btn-danger" href="logout.php">Logout</a>
</body>
</html>
