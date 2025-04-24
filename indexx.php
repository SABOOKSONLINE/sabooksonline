<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SA Books Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet"/>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body { background-color: #f8f9fa; }
    .card-icon { font-size: 1.8rem; }
    .top-card { min-height: 120px; }
    .tab-content ul { list-style: none; padding-left: 0; }
    .tab-content li { padding: 6px 0; border-bottom: 1px solid #eee; }
  </style>
</head>
<body>
  <div class="container-fluid p-4">
    <h3 class="mb-4 fw-bold animate__animated animate__fadeInDown">📚 SA Books Online - Analytics Dashboard</h3>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
      <div class="col-md-4">
        <div class="card shadow top-card animate__animated animate__fadeInUp">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <h5>Books Listed</h5>
              <h2>427</h2>
            </div>
            <i class="bi bi-journal-bookmark-fill card-icon text-primary"></i>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow top-card animate__animated animate__fadeInUp">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <h5>Subscribers</h5>
              <h2>443</h2>
            </div>
            <i class="bi bi-people-fill card-icon text-success"></i>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card shadow top-card animate__animated animate__fadeInUp">
          <div class="card-body d-flex justify-content-between align-items-center">
            <div>
              <h5>Income</h5>
              <h2>R 3,745.00</h2>
            </div>
            <i class="bi bi-currency-exchange card-icon text-danger"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts -->
    <div class="row g-4 mb-4">
      <div class="col-md-6">
        <div class="card shadow animate__animated animate__fadeInLeft">
          <div class="card-body">
            <h5>Viewer Type Breakdown</h5>
            <canvas id="viewerDoughnut"></canvas>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card shadow animate__animated animate__fadeInRight">
          <div class="card-body">
            <h5>Popular Cities (South Africa)</h5>
            <canvas id="citiesBar"></canvas>
          </div>
        </div>
      </div>
    </div>

    <!-- Top Viewed Tabs -->
    <div class="card shadow mb-4 animate__animated animate__fadeInUp">
      <div class="card-body">
        <h5 class="mb-3">Top 5 Most Viewed</h5>
        <ul class="nav nav-tabs" id="topTab" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#booksTab">Books</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profilesTab">Profiles</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#eventsTab">Events</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#servicesTab">Services</button>
          </li>
        </ul>
        <div class="tab-content mt-3">
          <div class="tab-pane fade show active" id="booksTab">
            <ul>
              <li>📘 *Bloodline of Kemet* – 348 views</li>
              <li>📘 *Mindful Hustle* – 275 views</li>
              <li>📘 *Cape Town Noir* – 241 views</li>
              <li>📘 *Ubuntu Code* – 213 views</li>
              <li>📘 *Jozi Tales* – 201 views</li>
            </ul>
          </div>
          <div class="tab-pane fade" id="profilesTab">
            <ul>
              <li>🧑 Zinhle M. – 189 views</li>
              <li>🧑 Kabelo N. – 172 views</li>
              <li>🧑 Ayesha K. – 150 views</li>
              <li>🧑 Bongani T. – 142 views</li>
              <li>🧑 Leo P. – 138 views</li>
            </ul>
          </div>
          <div class="tab-pane fade" id="eventsTab">
            <ul>
              <li>🎟️ Book Festival CT – 98 views</li>
              <li>🎟️ KZN Poetry Slam – 85 views</li>
              <li>🎟️ Authors Unite Joburg – 72 views</li>
              <li>🎟️ Online Writing Retreat – 68 views</li>
              <li>🎟️ LitVibes Soweto – 59 views</li>
            </ul>
          </div>
          <div class="tab-pane fade" id="servicesTab">
            <ul>
              <li>🛠️ Cover Designer: Thabo M. – 123 views</li>
              <li>🛠️ Proofreader: Naledi S. – 115 views</li>
              <li>🛠️ Editor: Sipho L. – 101 views</li>
              <li>🛠️ Illustrator: Mbali D. – 98 views</li>
              <li>🛠️ PR Agent: Kyle B. – 87 views</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <div class="text-end">
      <button class="btn btn-outline-primary">📥 Download CSV</button>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Doughnut
    new Chart(document.getElementById("viewerDoughnut"), {
      type: "doughnut",
      data: {
        labels: ["Books", "Profiles", "Events", "Services"],
        datasets: [{
          data: [60, 20, 10, 10],
          backgroundColor: ["#0d6efd", "#198754", "#ffc107", "#dc3545"]
        }]
      },
      options: { responsive: true }
    });

    // Bar
    new Chart(document.getElementById("citiesBar"), {
      type: "bar",
      data: {
        labels: ["Cape Town", "Johannesburg", "Durban", "Pretoria", "Gqeberha"],
        datasets: [{
          label: "Views",
          backgroundColor: "#0dcaf0",
          data: [324, 298, 215, 203, 156]
        }]
      },
      options: { responsive: true }
    });
  </script>
</body>
</html>
