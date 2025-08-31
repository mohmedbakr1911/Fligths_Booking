<?php
require_once __DIR__.'/../config/db.php';
$from = strtoupper(trim($_GET['from'] ?? ''));
$to = strtoupper(trim($_GET['to'] ?? ''));
$depart = $_GET['depart'] ?? '';
$passengers = max(1, (int)($_GET['passengers'] ?? 1));
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
$rows = $stmt->fetchAll();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Results</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/
bootstrap.min.css" rel="stylesheet">

</head>
<body class="container py-4">
<h1 class="h4 mb-3">Available flights</h1>
<?php if (!$rows): ?>
<div class="alert alert-warning">No flights found.</div>
<?php else: ?>
<div class="list-group">
<?php foreach ($rows as $r): ?>
<div class="list-group-item">
<div class="d-flex justify-content-between align-items-center">
<div>
<div class="fw-semibold"><?php echo
htmlspecialchars($r['airline'].' '.$r['flight_no']); ?></div>
<div class="text-muted small"><?php echo
htmlspecialchars($r['origin'].' → '.$r['destination']); ?></div>
<div class="small">Depart: <?php echo
htmlspecialchars($r['depart_time']); ?> — Arrive: <?php echo
htmlspecialchars($r['arrive_time']); ?></div>
</div>
<div class="text-end">
<div class="fs-5">$<?php echo number_format($r['price'],2);?></div>
<a class="btn btn-primary mt-2" href="booking.php?flight_id=<?php echo (int)$r['id']; ?>&passengers=<?php echo (int)$passengers; ?>">Book</a>
</div>
</div>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>
</body>
</html>