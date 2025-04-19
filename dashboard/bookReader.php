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
      max-width: 1400px;
      margin: 30px auto;
      padding: 30px;
      background: var(--card-bg);
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.2);
      transition: all 0.3s ease;
    }

    body.dark-mode .container {
      background: #2b2b2b;
    }

    h1 {
      text-align: center;
      font-size: 2.5rem;
      margin-bottom: 1.2rem;
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
      transition: transform 0.2s;
    }

    .chapters span:hover {
      transform: scale(1.05);
    }

    #pdf-viewer {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 20px;
      text-align: center;
    }

    canvas {
      border-radius: 10px;
      background: white;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
      max-width: 100%;
    }

    .nav-buttons {
      display: flex;
      justify-content: center;
      gap: 10px;
      align-items: center;
      margin-top: 20px;
    }

    .nav-buttons span {
      font-weight: bold;
    }

    .zoom-controls {
      display: flex;
      justify-content: center;
      gap: 10px;
      margin-top: 15px;
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
      <div class="chapters" id="chapter-links">
        <strong>Chapters:</strong>
      </div>

      <div class="zoom-controls">
        <button onclick="zoomOut()">➖ Zoom Out</button>
        <button onclick="zoomIn()">➕ Zoom In</button>
      </div>

      <div class="nav-buttons">
        <button onclick="prevPage()">⬅ Prev</button>
        <span>Page <span id="page-num">1</span> of <span id="page-count">--</span></span>
        <button onclick="nextPage()">Next ➡</button>
      </div>

      <div id="pdf-viewer">
        <canvas id="pdf-canvas-left"></canvas>
        <canvas id="pdf-canvas-right"></canvas>
      </div>
    <?php endif; ?>
  </div>

<?php if (!empty($book) && file_exists($filePath)): ?>
<script>
  const url = "<?= $filePath ?>";
  let pdfDoc = null,
      pageNum = parseInt(localStorage.getItem(url + '-last-page')) || 1,
      scale = parseFloat(localStorage.getItem(url + '-scale')) || 1.6;

  const canvasLeft = document.getElementById("pdf-canvas-left"),
        canvasRight = document.getElementById("pdf-canvas-right"),
        ctxLeft = canvasLeft.getContext("2d"),
        ctxRight = canvasRight.getContext("2d");

  pdfjsLib.getDocument(url).promise.then(pdf => {
    pdfDoc = pdf;
    document.getElementById("page-count").textContent = pdf.numPages;
    renderPages();
    renderChapters(pdf.numPages);
  });

  function renderPages() {
    if (pageNum > pdfDoc.numPages) return;

    renderPageToCanvas(pageNum, canvasLeft, ctxLeft);

    const nextPage = pageNum + 1;
    if (nextPage <= pdfDoc.numPages) {
      renderPageToCanvas(nextPage, canvasRight, ctxRight);
    } else {
      ctxRight.clearRect(0, 0, canvasRight.width, canvasRight.height);
    }

    document.getElementById("page-num").textContent = pageNum;
    localStorage.setItem(url + '-last-page', pageNum);
  }

  function renderPageToCanvas(num, canvas, ctx) {
    pdfDoc.getPage(num).then(page => {
      let viewport = page.getViewport({ scale: scale });
      canvas.height = viewport.height;
      canvas.width = viewport.width;
      page.render({ canvasContext: ctx, viewport: viewport });
    });
  }

  function prevPage() {
    if (pageNum <= 2) return;
    pageNum -= 2;
    renderPages();
  }

  function nextPage() {
    if (pageNum + 1 >= pdfDoc.numPages) return;
    pageNum += 2;
    renderPages();
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
        pageNum = startPage % 2 === 0 ? startPage - 1 : startPage;
        renderPages();
      };
      container.appendChild(chapterEl);
    }
  }

  function zoomIn() {
    scale += 0.2;
    localStorage.setItem(url + '-scale', scale);
    renderPages();
  }

  function zoomOut() {
    if (scale <= 0.4) return;
    scale -= 0.2;
    localStorage.setItem(url + '-scale', scale);
    renderPages();
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
