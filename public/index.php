<?php /* public/index.php */ ?>
<!doctype html>

<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Flight Booker</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/
bootstrap.min.css" rel="stylesheet">
<link href="../assets/css/styles.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
<div class="container">
<a class="navbar-brand fw-bold" href="#">✈️ Flight Booker</a>
</div>
</nav>
<main class="container py-5">
<div class="row justify-content-center">
<div class="col-lg-8">
<div class="card shadow-sm">
<div class="card-body p-4">
<h1 class="h4 mb-4">Search Flights</h1>
<form id="searchForm" class="row g-3" action="result.php" method="GET">
<div class="col-md-6">
<label class="form-label">From</label>
<input type="text" class="form-control" name="from"
placeholder="CAI" required>
</div>
<div class="col-md-6">
<label class="form-label">To</label>
<input type="text" class="form-control" name="to"
placeholder="DXB" required>
</div>
<div class="col-md-6">
<label class="form-label">Departure</label>
<input type="date" class="form-control" name="depart"
required>
</div>
<div class="col-md-6">
<label class="form-label">Passengers</label>
<input type="number" class="form-control" name="passengers"
min="1" value="1" required>
</div>
<div class="col-12 d-grid">
<button type="submit" class="btn btn-primary btn-lg">Find
flights</button>
</div>
</form>
</div>
</div>

<div id="results" class="mt-4"></div>
</div>
</div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/
bootstrap.bundle.min.js"></script>
<script>const APP_URL = '<?php echo htmlspecialchars(constant('APP_URL') ??
'http://localhost'); ?>';</script>
<script src="../assets/js/app.js"></script>
</body>
</html>