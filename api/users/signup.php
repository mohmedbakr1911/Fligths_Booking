<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . "/../../config/db.php";


    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = db()->prepare("INSERT INTO users (full_name, email, phone, password) VALUES (?, ?, ?, ?)");
    try {
        $stmt->execute([$full_name, $email, $phone, $password]);
        echo "<div class='alert alert-success'>Signup successful. <a href='login.php'>Login here</a></div>";
    } catch (PDOException $e) {
        echo "<div class='alert alert-danger'>Error: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">
    <h2>Signup</h2>
    <form method="POST">
        <input class="form-control mb-2" type="text" name="full_name" placeholder="Full Name" required>
        <input class="form-control mb-2" type="email" name="email" placeholder="Email" required>
        <input class="form-control mb-2" type="text" name="phone" placeholder="Phone">
        <input class="form-control mb-2" type="password" name="password" placeholder="Password" required>
        <button class="btn btn-primary w-100">Signup</button>
    </form>
</body>
</html>
