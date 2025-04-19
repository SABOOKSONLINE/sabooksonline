<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['pdf'])) {
    $file = $_FILES['pdf'];
    $targetDir = __DIR__ . "/books/";
    if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);

    $allowedTypes = ['application/pdf'];
    if ($file['error'] === 0 && in_array($file['type'], $allowedTypes)) {
        $filename = uniqid() . "-" . basename($file['name']);
        $targetPath = $targetDir . $filename;
        move_uploaded_file($file['tmp_name'], $targetPath);
        header("Location: ?file=" . urlencode($filename));
        exit;
    } else {
        $error = "Invalid PDF file.";
    }
}

$book = isset($_GET['file']) ? basename($_GET['file']) : '';
$filePath = "books/" . $book;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>📖 Two-Page PDF Reader</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
  <style>
    :root {
      --bg-light: #f2f2f2;
      --bg-dark: #1c1c1c;
      --text-light: #2c3e50;
      --text-dark: #ecf0f1;
      --accent: #3498db;
      --card-bg-light: #ffffff;
      --card-bg-dark: #2b2b2b;
    }

    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: var(--bg-light);
      color: var(--text-light);
      transition: all 0.3s ease;
    }

    body.dark-mode {
      background: var(--bg-dark);
      color: var(--text-dark);
    }

    .container {
      max-width: 1400px;
      margin: 20px auto;
      padding: 20px;
      background: var(--card-bg-light);
      border-radius: 12px;
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
      transition: all 0.3s ease;
    }

    body.dark-mode .container {
      background: var(--card-bg-dark);
    }

    h1 {
      text-align: center;
      font-size: 2.2rem;
      margin-bottom: 1rem;
    }

    form {
      display: flex;
      gap: 10px;
      margin-bottom: 20px;
    }

    input[type="file"] {
      flex: 1;
      padding: 10px;
    }

    button {
      background: var(--accent);
      color: #fff;
      border: none;
      padding: 10px 16px;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    button:hover {
      background: #2980b9;
    }

    .toggle-mode {
      text-align: right;
      margin-bottom: 15px;
    }

    .nav-buttons {
      display: flex;
      justify-content: center;
      gap: 15px;
      flex-wrap: wrap;
      margin-top: 15px;
    }

    #pdf-viewer {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 20px;
      padding: 20px 0;
    }

    canvas {
      border: 1px solid #ccc;
      border-radius: 10px;
      max-width: 100%;
      transition: transform 0.3s;
    }

    canvas:hover {
      transform: scale(1.01);
    }

    .zoom-buttons {
      text-align: center;
      margin-top: 10px;
    }

    @media (max-width: 768px) {
      #pdf-viewer {
        flex-direction: column;
        align-items: center;
      }
    }
  </style>
</head>
<body class="prevent-select">
  <div class="container">
    <div class="toggle-mode">
      <button onclick="toggleMode()">🌓 Toggle Theme</button>
    </div>

    <h1>📖 Two-Page PDF Book Reader</h1>

    <form method="POST" enctype="multipart/form-data">
      <input type="file" name="pdf" accept="application/pdf" required>
      <button type="submit">Upload PDF</button>
    </form>

    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <?php if (!empty($book) && file_exists($filePath)): ?>
      <div id="pdf-viewer">
        <canvas id="left-canvas"></canvas>
        <canvas id="right-canvas"></canvas>
      </div>

      <div class="nav-buttons">
        <button onclick="prevPage()">⬅ Prev</button>
        <span>Page <span id="page-num">1</span> / <span id="page-count">--</span></span>
        <button onclick="nextPage()">Next ➡</button>
      </div>

      <div class="zoom-buttons">
        <button onclick="zoomOut()">➖ Zoom Out</button>
        <button onclick="zoomIn()">➕ Zoom In</button>
      </div>

      <div class="search-container">
        <input type="text" id="search-input" placeholder="Search Text" oninput="searchText()">
        <button onclick="clearSearch()">Clear Search</button>
      </div>
    <?php endif; ?>
  </div>

<?php if (!empty($book) && file_exists($filePath)): ?>
<script>
  const url = "<?= $filePath ?>";
  let pdfDoc = null,
      pageNum = parseInt(localStorage.getItem(url + '-last-page')) || 1,
      zoom = parseFloat(localStorage.getItem('zoom')) || 1.5,
      searchTextResults = [],
      currentSearchIndex = -1,
      leftCanvas = document.getElementById("left-canvas"),
      rightCanvas = document.getElementById("right-canvas"),
      leftCtx = leftCanvas.getContext("2d"),
      rightCtx = rightCanvas.getContext("2d");

  pdfjsLib.getDocument(url).promise.then(pdf => {
    pdfDoc = pdf;
    document.getElementById("page-count").textContent = pdf.numPages;
    renderPages();
  });

  function renderPages() {
    if (pageNum < 1) pageNum = 1;
    if (pageNum > pdfDoc.numPages) pageNum = pdfDoc.numPages;

    // Left Page
    pdfDoc.getPage(pageNum).then(page => {
      let vp = page.getViewport({ scale: zoom });
      leftCanvas.height = vp.height;
      leftCanvas.width = vp.width;
      page.render({ canvasContext: leftCtx, viewport: vp });
    });

    // Right Page (next one)
    if (pageNum + 1 <= pdfDoc.numPages) {
      pdfDoc.getPage(pageNum + 1).then(page => {
        let vp = page.getViewport({ scale: zoom });
        rightCanvas.height = vp.height;
        rightCanvas.width = vp.width;
        page.render({ canvasContext: rightCtx, viewport: vp });
      });
    } else {
      rightCtx.clearRect(0, 0, rightCanvas.width, rightCanvas.height);
    }

    document.getElementById("page-num").textContent = pageNum;
    localStorage.setItem(url + '-last-page', pageNum);
  }

  function prevPage() {
    if (pageNum - 2 >= 1) {
      pageNum -= 2;
      renderPages();
    }
  }

  function nextPage() {
    if (pageNum + 2 <= pdfDoc.numPages) {
      pageNum += 2;
      renderPages();
    }
  }

  function zoomIn() {
    zoom += 0.2;
    localStorage.setItem('zoom', zoom);
    renderPages();
  }

  function zoomOut() {
    zoom = Math.max(0.5, zoom - 0.2);
    localStorage.setItem('zoom', zoom);
    renderPages();
  }

  function toggleMode() {
    document.body.classList.toggle('dark-mode');
    localStorage.setItem('theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
  }

  // Restore theme
  window.onload = () => {
    if (localStorage.getItem('theme') === 'dark') {
      document.body.classList.add('dark-mode');
    }
  }

  // Keyboard Navigation
  document.addEventListener('keydown', (e) => {
    if (e.key === "ArrowLeft" || e.key === "j") {
      prevPage();
    } else if (e.key === "ArrowRight" || e.key === "k") {
      nextPage();
    }
  });

  // Swipe Navigation
  let startX = 0;
  let endX = 0;

  document.addEventListener('touchstart', (e) => {
    startX = e.touches[0].pageX;
  });

  document.addEventListener('touchend', (e) => {
    endX = e.changedTouches[0].pageX;
    if (endX - startX > 50) {
      prevPage();
    } else if (startX - endX > 50) {
      nextPage();
    }
  });

  // Text Search
  function searchText() {
    let query = document.getElementById("search-input").value;
    if (query.trim() === "") {
      searchTextResults = [];
      currentSearchIndex = -1;
      renderPages();
      return;
    }

    pdfDoc.getPage(pageNum).then(page => {
      page.getTextContent().then(textContent => {
        let regex = new RegExp(query, "gi");
        searchTextResults = [];
        textContent.items.forEach(item => {
          let match = item.str.match(regex);
          if (match) {
            searchTextResults.push({
              text: item.str,
              x: item.transform[4],
              y: item.transform[5],
              width: item.width,
              height: item.height
            });
          }
        });
        highlightSearchResults();
      });
    });
  }

  function highlightSearchResults() {
    // Redraw the page with highlights
    renderPages();

    if (searchTextResults.length > 0 && currentSearchIndex >= 0) {
      let result = searchTextResults[currentSearchIndex];
      leftCtx.fillStyle = "rgba(255, 0, 0, 0.3)";
      leftCtx.fillRect(result.x, result.y - result.height, result.width, result.height);

      if (pageNum + 1 <= pdfDoc.numPages) {
        pdfDoc.getPage(pageNum + 1).then(page => {
          page.getTextContent().then(textContent => {
            let result = searchTextResults[currentSearchIndex];
            rightCtx.fillStyle = "rgba(255, 0, 0, 0.3)";
            rightCtx.fillRect(result.x, result.y - result.height, result.width, result.height);
          });
        });
      }
    }
  }

  function clearSearch() {
    document.getElementById("search-input").value = "";
    searchTextResults = [];
    currentSearchIndex = -1;
    renderPages();
  }
</script>
<?php endif; ?>
</body>
</html>
