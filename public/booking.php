<?php
// booking.php
if (!isset($_GET['flight_id'])) {
    die("Flight not selected.");
}
$flight_id = (int)$_GET['flight_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Flight</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-3">
                <div class="card-header bg-primary text-white text-center">
                    <h3>Book Flight #<?php echo $flight_id; ?></h3>
                </div>
                <div class="card-body p-4">
                    <form id="bookingForm" method="POST" action="../api/booking/create.php">
                        <input type="hidden" name="flight_id" value="<?php echo $flight_id; ?>">

                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Passengers</label>
                            <input type="number" name="passengers" class="form-control" value="1" min="1" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Confirm Booking</button>
                    </form>

                    <!-- Message Box -->
                    <div id="messageBox" class="mt-3" style="display:none;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById("bookingForm").addEventListener("submit", async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());

    const res = await fetch("../api/booking/create.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
    });

    const result = await res.json();
    const msgBox = document.getElementById("messageBox");

    if (result.success) {
        msgBox.className = "alert alert-success mt-3";
        msgBox.innerText = "✅ Booking done successfully!";
    } else {
        msgBox.className = "alert alert-danger mt-3";
        msgBox.innerText = "❌ Booking failed: " + result.error;
    }
    msgBox.style.display = "block";
});
</script>

</body>
</html>
