<?php
require_once __DIR__.'/../../config/db.php';
session_start();
header('Content-Type: application/json');

try {
    $input = $_POST ?: json_decode(file_get_contents('php://input'), true) ?: [];

    $flight_id   = (int)($input['flight_id'] ?? 0);
    $full_name   = trim($input['full_name'] ?? '');
    $email       = trim($input['email'] ?? '');
    $phone       = trim($input['phone'] ?? '');
    $passengers  = max(1, (int)($input['passengers'] ?? 1));

    if (!$flight_id || !$full_name || !$email) {
        http_response_code(422);
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }

    // ✅ CHECK USER LOGIN
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'You must log in to book']);
        exit;
    }
    $user_id = $_SESSION['user_id'];

    $f = db()->prepare('SELECT price, seats_available FROM flights WHERE id=:id FOR UPDATE');
    db()->beginTransaction();
    $f->execute([':id'=>$flight_id]);
    $flight = $f->fetch();

    if (!$flight) { db()->rollBack(); http_response_code(404); echo json_encode(['error'=>'Flight not found']); exit; }
    if ((int)$flight['seats_available'] < $passengers) { db()->rollBack(); http_response_code(409); echo json_encode(['error'=>'Not enough seats']); exit; }

    $total = $passengers * (float)$flight['price'];

    // ✅ INSERT WITH user_id
    $ins = db()->prepare('INSERT INTO bookings (flight_id, user_id, full_name, email, phone, passengers, total_price) 
                          VALUES (:f, :u, :n, :e, :p, :ps, :t)');
    $ins->execute([
        ':f'  => $flight_id,
        ':u'  => $user_id,
        ':n'  => $full_name,
        ':e'  => $email,
        ':p'  => $phone,
        ':ps' => $passengers,
        ':t'  => $total
    ]);

    $upd = db()->prepare('UPDATE flights SET seats_available = seats_available - :ps WHERE id=:id');
    $upd->execute([':ps'=>$passengers, ':id'=>$flight_id]);

    db()->commit();
    echo json_encode(['success'=>true, 'booking_id'=>db()->lastInsertId(), 'total'=>$total]);

} catch (Throwable $e) {
    if (db()->inTransaction()) db()->rollBack();
    http_response_code(500);
    echo json_encode(['error' => 'Server error']);
}
