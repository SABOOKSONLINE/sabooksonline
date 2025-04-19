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
  <title>📘 Book Reader</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
  <style>
    :root {
      --bg-light: #f4f6f9;
      --bg-dark: #1c1c1c;
      --text-light: #2c3e50;
      --text-dark: #ecf0f1;
      --accent: #3498db;
      --card-bg: #ffffff;
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
      max-width: 900px;
      margin: 30px auto;
      padding: 20px;
      background: var(--card-bg);
      border-radius: 12px;
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    body.dark-mode .container {
      background: #2b2b2b;
    }

    h1 {
      text-align: center;
      font-size: 2rem;
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

    .chapters {
      margin: 20px 0;
      padding: 12px;
      background: #eaf4fc;
      border-radius: 6px;
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }

    body.dark-mode .chapters {
      background: #34495e;
    }

    .chapters span {
      background: var(--accent);
      color: white;
      padding: 6px 12px;
      border-radius: 4px;
      cursor: pointer;
    }

    .chapters span:hover {
      background: #2980b9;
    }

    #pdf-viewer {
      text-align: center;
    }

    canvas {
      border-radius: 8px;
      margin: 10px 0;
      max-width: 100%;
    }

    .nav-buttons {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 10px;
      align-items: center;
      margin-top: 10px;
    }

    .nav-buttons button,
    .nav-buttons label {
      background: var(--accent);
      color: #fff;
      padding: 8px 12px;
      border-radius: 5px;
      border: none;
      cursor: pointer;
      font-size: 0.9rem;
    }

    .nav-buttons input[type="checkbox"] {
      margin-right: 5px;
      vertical-align: middle;
    }

    body.prevent-select, canvas {
      -webkit-user-select: none;
      user-select: none;
      -webkit-touch-callout: none;
    }

    @media print {
      body {
        display: none;
      }
    }
  </style>
</head>
<body class="prevent-select">
  <div class="container">
    <div class="toggle-mode">
      <button onclick="toggleMode()">🌓 Toggle Theme</button>
    </div>

    <h1>📚 PDF Book Reader</h1>

    <form method="POST" enctype="multipart/form-data">
      <input type="file" name="pdf" accept="application/pdf" required>
      <button type="submit">Upload PDF</button>
    </form>

    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <?php if (!empty($book) && file_exists($filePath)): ?>
      <div class="chapters" id="chapter-links"><strong>Chapters:</strong></div>

      <div class="nav-buttons">
        <button onclick="prevPage()">⬅ Prev</button>
        <button onclick="nextPage()">Next ➡</button>
        <span>Page <span id="page-num">1</span> of <span id="page-count">--</span></span>
        <button onclick="zoomIn()">🔍 Zoom In</button>
        <button onclick="zoomOut()">🔎 Zoom Out</button>
        <label><input type="checkbox" id="fitToggle" onchange="fitScreen()"> Fit to screen</label>
        <label><input type="checkbox" id="scrollToggle" onchange="toggleAutoScroll()"> Auto-scroll</label>
      </div>

      <div id="pdf-viewer"><canvas id="pdf-canvas"></canvas></div>
    <?php endif; ?>
  </div>

<?php if (!empty($book) && file_exists($filePath)): ?>
<script>
  const url = "<?= $filePath ?>";
  let pdfDoc = null,
      pageNum = parseInt(localStorage.getItem(url + '-last-page')) || 1,
      canvas = document.getElementById("pdf-canvas"),
      ctx = canvas.getContext("2d"),
      scale = 1.4,
      fitToScreen = false,
      autoScroll = false;

  pdfjsLib.getDocument(url).promise.then(pdf => {
    pdfDoc = pdf;
    document.getElementById("page-count").textContent = pdf.numPages;
    renderPage(pageNum);
    renderChapters(pdf.numPages);
  });

  function renderPage(num) {
    pdfDoc.getPage(num).then(page => {
      let viewport = page.getViewport({ scale });

      if (fitToScreen) {
        const maxWidth = canvas.parentElement.offsetWidth - 20;
        scale = maxWidth / viewport.width;
        viewport = page.getViewport({ scale });
      }

      canvas.height = viewport.height;
      canvas.width = viewport.width;
      page.render({ canvasContext: ctx, viewport });

      document.getElementById("page-num").textContent = num;
      localStorage.setItem(url + '-last-page', num);

      if (autoScroll && num < pdfDoc.numPages) {
        setTimeout(() => {
          pageNum++;
          renderPage(pageNum);
        }, 3500);
      }
    });
  }

  function prevPage() {
    if (pageNum <= 1) return;
    pageNum--;
    renderPage(pageNum);
  }

  function nextPage() {
    if (pageNum >= pdfDoc.numPages) return;
    pageNum++;
    renderPage(pageNum);
  }

  function zoomIn() {
    scale += 0.2;
    renderPage(pageNum);
  }

  function zoomOut() {
    if (scale > 0.6) {
      scale -= 0.2;
      renderPage(pageNum);
    }
  }

  function fitScreen() {
    fitToScreen = document.getElementById('fitToggle').checked;
    renderPage(pageNum);
  }

  function toggleAutoScroll() {
    autoScroll = document.getElementById('scrollToggle').checked;
    if (autoScroll) renderPage(pageNum);
  }

  function renderChapters(totalPages) {
    const chapters = 5;
    const chapterSize = Math.floor(totalPages / chapters);
    const container = document.getElementById('chapter-links');

    for (let i = 0; i < chapters; i++) {
      const startPage = i * chapterSize + 1;
      const chapterEl = document.createElement("span");
      chapterEl.textContent = `Chapter ${i + 1}`;
      chapterEl.onclick = () => {
        pageNum = startPage;
        renderPage(pageNum);
      };
      container.appendChild(chapterEl);
    }
  }

  function toggleMode() {
    document.body.classList.toggle('dark-mode');
    localStorage.setItem('theme', document.body.classList.contains('dark-mode') ? 'dark' : 'light');
  }

  window.onload = () => {
    if (localStorage.getItem('theme') === 'dark') {
      document.body.classList.add('dark-mode');
    }
  }
</script>
<?php endif; ?>
</body>
</html>
