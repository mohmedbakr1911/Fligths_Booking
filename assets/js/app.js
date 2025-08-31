 (async function(){
const form = document.getElementById('searchForm');
const results = document.getElementById('results');
if (!form || !results) return;
form.addEventListener('submit', async (e)=>{
e.preventDefault();
const data = new FormData(form);
const params = new URLSearchParams(data);
results.innerHTML = '<div class="text-center py-4">Loading…</div>';
const res = await fetch('../api/flights/search.php?' +
params.toString());
const json = await res.json();
if (!json.data || json.data.length === 0) {
results.innerHTML = '<div class="alert alert-warning">No flights found. </div>';
return;
}
results.innerHTML = json.data
  .map(
    (f) => `
<div class="card mb-3">
    <div class="card-body d-flex justify-content-between align-items-center">
      <div>
        <div class="fw-semibold">${f.airline} ${f.flight_no}</div>
        <div class="text-muted small">${f.origin} → ${f.destination}</div>
        <div class="small">Depart: ${f.depart_time} — Arrive: ${
      f.arrive_time
    }</div>
      </div>
      <div class="text-end">
        <div class="fs-5">$${Number(f.price).toFixed(2)}</div>
        <a class="btn btn-primary mt-2" href="result.php?from=${encodeURIComponent(
          f.origin
        )}&to=${encodeURIComponent(f.destination)}&depart=${encodeURIComponent(
      new Date(f.depart_time).toISOString().slice(0, 10)
    )}&passengers=${encodeURIComponent(form.passengers.value)}">Details</a>
        <a class="btn btn-success mt-2" href="booking.php?flight_id=${
          f.id
        }&passengers=${encodeURIComponent(form.passengers.value)}">Book</a>
      </div>
    </div>
  </div>
`
  )
  .join("");
});
})();

// Handle booking form submission (AJAX)
(async function () {
  const form = document.getElementById("bookingForm");
  const status = document.getElementById("bookingStatus");
  if (!form || !status) return;
  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    status.innerHTML = "";
    const data = Object.fromEntries(new FormData(form).entries());
    const res = await fetch("../api/booking/create.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data),
    });
    const json = await res.json();
    if (json.success) {
      status.innerHTML = `<div class="alert alert-success">Booking confirmed! ID: ${
        json.booking_id
      }.
Total: $${Number(json.total).toFixed(2)}</div>`;
      form.reset();
    } else {
      status.innerHTML = `<div class="alert alert-danger">${
        json.error || "Something went wrong"
      }</div>`;
    }
  });
})();