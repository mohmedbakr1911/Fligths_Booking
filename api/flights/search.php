<?php
    require_once __DIR__.'/../../config/db.php';
    header('Content-Type: application/json');
    try {
    $from = strtoupper(trim($_GET['from'] ?? ''));
    $to = strtoupper(trim($_GET['to'] ?? ''));
    $depart = $_GET['depart'] ?? '';
    $passengers = max(1, (int)($_GET['passengers'] ?? 1));
    if (!$from || !$to || !$depart) {
    http_response_code(422);
    echo json_encode(['error' => 'Missing parameters']);
    exit;
    }
    $sql = "
    SELECT f.id, al.name AS airline, f.flight_no, o.code AS origin, d.code AS
    destination,
    f.depart_time, f.arrive_time, f.price, f.seats_available
    FROM flights f
    JOIN airlines al ON al.id=f.airline_id
    JOIN airports o ON o.id=f.origin_id
    JOIN airports d ON d.id=f.destination_id
    WHERE o.code = :from AND d.code = :to
    AND DATE(f.depart_time) = :depart
    AND f.seats_available >= :p
    ORDER BY f.depart_time ASC
    ";
    $stmt = db()->prepare($sql);
    $stmt->execute([':from'=>$from, ':to'=>$to, ':depart'=>$depart,
    ':p'=>$passengers]);

    echo json_encode(['data' => $stmt->fetchAll()]);
    } catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["error" => "Server error", "details" => $e->getMessage()]);
    }

 ?>