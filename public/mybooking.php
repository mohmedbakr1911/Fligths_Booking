<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__.'/../config/db.php';
$stmt = db()->prepare("SELECT b.id, f.flight_no , f.depart_time, f.arrive_time, b.passengers, b.created_at 
                       FROM bookings b
                       JOIN flights f ON b.flight_id = f.id
                       WHERE b.user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$bookings = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookings</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2>Welcome <?php echo $_SESSION['full_name']; ?>, Your Bookings</h2>
    <table class="table table-bordered">
        <tr>
            <th>Booking ID</th>
            <th>Flight</th>
            <th>From</th>
            <th>To</th>
            <th>Passengers</th>
            <th>Date</th>
        </tr>
        <?php foreach($bookings as $b): ?>
        <tr>
            <td><?php echo $b['id']; ?></td>
            <td><?php echo $b['flight_no']; ?></td>
            <td><?php echo $b['depart_time']; ?></td>
            <td><?php echo $b['arrive_time']; ?></td>
            <td><?php echo $b['passengers']; ?></td>
            <td><?php echo $b['created_at']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
