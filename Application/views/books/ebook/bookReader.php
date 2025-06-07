<?php
require_once __DIR__ . "/../../includes/header.php";
?>

 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f4f4;
      color: #333;
      transition: background-color 0.3s, color 0.3s;
    }

    .dark-mode {
      background-color: #121212;
      color: #eee;
    }

    .container {
      max-width: 960px;
      margin: auto;
      padding: 1rem;
    }

    h2 {
      text-align: center;
      margin-bottom: 1rem;
    }

    .toolbar {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 1rem;
    }

    button {
      padding: 10px 16px;
      font-size: 14px;
      border: none;
      border-radius: 5px;
      background-color: #007bff;
      color: #fff;
      cursor: pointer;
      transition: background-color 0.2s ease;
    }

    button:hover {
      background-color: #0056b3;
    }

    .toggle-mode {
      position: fixed;
      top: 10px;
      right: 10px;
      z-index: 1000;
    }

    #pdf-pages {
      display: flex;
      flex-direction: column;
      gap: 20px;
      align-items: center;
    }

    canvas {
      border: 1px solid #ccc;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      background: white;
      max-width: 100%;
      height: auto;
    }

    .dark-mode canvas {
      background-color: #1e1e1e;
    }

    #chapterNav {
      max-height: 300px;
      overflow-y: auto;
      background: #fff;
      padding: 10px;
      margin: 1rem auto;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .dark-mode #chapterNav {
      background-color: #1e1e1e;
    }

    #chapters-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    #chapters-list li {
      padding: 6px 10px;
      border-bottom: 1px solid #ccc;
      cursor: pointer;
      transition: background 0.2s;
    }

    #chapters-list li:hover {
      background-color: #e2e2e2;
    }

    .dark-mode #chapters-list li:hover {
      background-color: #333;
    }
  </style>
</head>
<body>
    <?php require_once __DIR__ . "/../../includes/nav.php"; ?>   

  <div class="container">
    <h2><?= htmlspecialchars($book['TITLE']) ?></h2>

    <div id="pdf-pages"></div>
  </div>

  <script>
    const url = "<?= htmlspecialchars($book['PDFURL'], ENT_QUOTES, 'UTF-8') ?>";
    let pdfDoc = null,
        zoom = parseFloat(localStorage.getItem('zoom')) || 1.5,
        pageRendered = 0,
        chapterTitles = [];

    const pdfPagesContainer = document.getElementById("pdf-pages");

    pdfjsLib.getDocument(url).promise.then(pdf => {
      pdfDoc = pdf;
      renderAllPages();
      extractTextFromPDF();
    });

    function renderAllPages() {
      for (let pageNum = 1; pageNum <= pdfDoc.numPages; pageNum++) {
        pdfDoc.getPage(pageNum).then(page => {
          const canvas = document.createElement("canvas");
          const context = canvas.getContext("2d");
          const viewport = page.getViewport({ scale: zoom });

          canvas.width = viewport.width;
          canvas.height = viewport.height;
          page.render({ canvasContext: context, viewport: viewport });

          pdfPagesContainer.appendChild(canvas);
        });
      }
    }

    function zoomIn() {
      zoom += 0.2;
      localStorage.setItem('zoom', zoom);
      refreshPages();
    }

    function zoomOut() {
      zoom = Math.max(0.5, zoom - 0.2);
      localStorage.setItem('zoom', zoom);
      refreshPages();
    }

    function refreshPages() {
      pdfPagesContainer.innerHTML = "";
      renderAllPages();
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
        li.onclick = () => scrollToPage(chap.page);
        chapterList.appendChild(li);
      });
    }

    function scrollToPage(pageNumber) {
      const canvasList = document.querySelectorAll("#pdf-pages canvas");
      if (canvasList[pageNumber - 1]) {
        canvasList[pageNumber - 1].scrollIntoView({ behavior: "smooth" });
      }
    }
  </script>
</body>
</html>
