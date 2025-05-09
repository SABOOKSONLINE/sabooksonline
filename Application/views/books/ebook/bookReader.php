?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($book['TITLE']) ?> - Reader</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      margin: 0;
      background-color: #f4f4f4;
      color: #333;
      user-select: none;
      -webkit-user-select: none;
      -moz-user-select: none;
      -ms-user-select: none;
    }
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
      text-align: center;
    }
    #pdf-viewer {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-wrap: wrap;
      margin-top: 20px;
    }
    canvas {
      border: 1px solid #ccc;
      margin: 5px;
      max-width: 45%;
      height: auto;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      background: white;
    }
    .nav-buttons, .zoom-buttons {
      margin: 15px;
    }
    button {
      background-color: #008cba;
      color: white;
      border: none;
      padding: 10px 20px;
      margin: 5px;
      cursor: pointer;
      border-radius: 4px;
      font-size: 16px;
    }
    button:hover {
      background-color: #005f5f;
    }
    .dark-mode {
      background-color: #121212;
      color: #eee;
    }
    .dark-mode canvas {
      background-color: #2c2c2c;
    }
    #chapterNav {
      margin-top: 20px;
    }
    #chapters-list {
      list-style: none;
      padding: 0;
      max-height: 200px;
      overflow-y: auto;
    }
    #chapters-list li {
      padding: 5px;
      border-bottom: 1px solid #ccc;
      cursor: pointer;
    }
    #chapters-list li:hover {
      background-color: #ddd;
    }
    .toggle-mode {
      position: fixed;
      top: 10px;
      right: 10px;
    }
  </style>
</head>
<body class="prevent-select">
  <div class="toggle-mode">
    <button onclick="toggleMode()">🌓 Toggle Theme</button>
  </div>

  <div class="container">
  <h2><?= htmlspecialchars($book['TITLE']) ?></h2>

    <div id="chapterNav">
      <h3>Chapters</h3>
      <ul id="chapters-list"></ul>
    </div>

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
  </div>

<script>

const url = "<?= htmlspecialchars($book['PDFURL'], ENT_QUOTES, 'UTF-8') ?>";
let pdfDoc = null,
    pageNum = parseInt(localStorage.getItem(url + '-last-page')) || 1,
    zoom = parseFloat(localStorage.getItem('zoom')) || 1.5,
    leftCanvas = document.getElementById("left-canvas"),
    rightCanvas = document.getElementById("right-canvas"),
    leftCtx = leftCanvas.getContext("2d"),
    rightCtx = rightCanvas.getContext("2d"),
    chapterTitles = [];

pdfjsLib.getDocument(url).promise.then(pdf => {
  pdfDoc = pdf;
  document.getElementById("page-count").textContent = pdf.numPages;
  extractTextFromPDF();
  renderPages();
});

function renderPages() {
  if (!pdfDoc) return;

  // Save last page
  localStorage.setItem(url + '-last-page', pageNum);

  // Render left page
  pdfDoc.getPage(pageNum).then(page => {
    let viewport = page.getViewport({ scale: zoom });
    leftCanvas.height = viewport.height;
    leftCanvas.width = viewport.width;
    page.render({ canvasContext: leftCtx, viewport: viewport });
  });

  // Render right page (next page)
  if (pageNum + 1 <= pdfDoc.numPages) {
    pdfDoc.getPage(pageNum + 1).then(page => {
      let viewport = page.getViewport({ scale: zoom });
      rightCanvas.height = viewport.height;
      rightCanvas.width = viewport.width;
      page.render({ canvasContext: rightCtx, viewport: viewport });
    });
  } else {
    rightCtx.clearRect(0, 0, rightCanvas.width, rightCanvas.height);
  }

  document.getElementById("page-num").textContent = pageNum;
}

function prevPage() {
  if (pageNum > 1) {
    pageNum -= 2;
    if (pageNum < 1) pageNum = 1;
    renderPages();
  }
}

function nextPage() {
  if (pageNum + 1 < pdfDoc.numPages) {
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
  document.body.classList.toggle("dark-mode");
}

function extractTextFromPDF() {
  let promises = [];
  for (let i = 1; i <= pdfDoc.numPages; i++) {
    promises.push(pdfDoc.getPage(i).then(page => page.getTextContent()));
  }

  Promise.all(promises).then(pages => {
    chapterTitles = [];
    pages.forEach((pageText, index) => {
      const pageNum = index + 1;
      let textContent = pageText.items.map(item => item.str).join(' ');
      // Detect chapter titles
      let matches = textContent.match(/Chapter\s+\d+[^.]*/gi);
      if (matches) {
        matches.forEach(title => {
          chapterTitles.push({ title, page: pageNum });
        });
      }
    });

    renderChapters();
  });
}

function renderChapters() {
  const chapterList = document.getElementById("chapters-list");
  chapterList.innerHTML = "";
  chapterTitles.forEach(chap => {
    const li = document.createElement("li");
    li.textContent = chap.title + " (p." + chap.page + ")";
    li.onclick = () => {
      pageNum = chap.page;
      renderPages();
    };
    chapterList.appendChild(li);
  });
}
</script>
</body>
</html>
