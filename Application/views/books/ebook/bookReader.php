<?php
// Don't include header.php as it has its own DOCTYPE/html tags
// We'll add necessary meta tags and scripts here
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reading - <?= htmlspecialchars($content['title'] ?? $content['TITLE'] ?? 'Book') ?></title>
  
  <!-- Favicons-->
  <link rel="shortcut icon" href="/public/images/sabo_favicon (144x144).svg" type="image/svg+xml">
  <link rel="apple-touch-icon" sizes="72x72" href="/public/images/sabo_favicon (72x72).png">
  <link rel="apple-touch-icon" sizes="114x114" href="/public/images/sabo_favicon (114x114).png">
  <link rel="apple-touch-icon" sizes="144x144" href="/public/images/sabo_favicon (144x144).png">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fira+Mono:wght@400;500;700&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

  <!-- Bootstrap -->
  <link href="/public/css/bootstrap.min.css" rel="stylesheet">

  <!-- Icons -->
  <link href="/public/css/icons.css" rel="stylesheet" />
  <link href="/public/css/all.min.css" rel="stylesheet" />

  <!-- Custom Css -->
  <link href="/public/css/custom/style.css" rel="stylesheet" />
  <link href="/public/css/custom/typoComponent.css" rel="stylesheet" />
  <link href="/public/css/custom/section.css" rel="stylesheet" />
  <link href="/public/css/custom/bkComponent.css" rel="stylesheet" />
  <link href="/public/css/custom/banners.css" rel="stylesheet" />
  <link href="/public/css/custom/responsive.css" rel="stylesheet" />
  <link href="/public/css/custom/audioBook.css" rel="stylesheet" />
  <link href="/public/css/custom/mobile-home.css" rel="stylesheet" />

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
    display: flex;
    max-width: 100%;
    margin: 0;
  }

  /* Sidebar (chapters) */
  #chapterNav {
    width: 250px;
    height: 100vh;
    position: sticky;
    top: 0;
    left: 0;
    overflow-y: auto;
    background: #fff;
    border-right: 1px solid #ddd;
    padding: 1rem;
    box-shadow: 2px 0 6px rgba(0,0,0,0.05);
  }

  .dark-mode #chapterNav {
    background-color: #1e1e1e;
    border-right: 1px solid #333;
  }

  #chapters-list {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  #chapters-list li {
    padding: 10px 12px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
  }

  #chapters-list li:hover {
    background-color: #e2e2e2;
  }

  .dark-mode #chapters-list li:hover {
    background-color: #333;
  }

  /* Main PDF area */
  .pdf-container {
    flex: 1;
    padding: 1rem;
    overflow-x: hidden;
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

  .watermark {
    position: fixed;
    bottom: 10px;
    right: 10px;
    color: rgba(255,255,255,0.15);
    font-size: 18px;
    pointer-events: none;
    z-index: 999;
  }

  .loading {
    text-align: center;
    padding: 60px 20px;
    color: #666;
    font-size: 18px;
  }

  .error {
    text-align: center;
    padding: 60px 20px;
    color: #dc2626;
    font-size: 18px;
    background: #fee;
    border-radius: 8px;
    margin: 20px;
  }

  .error-details {
    font-size: 14px;
    color: #666;
    margin-top: 12px;
    word-break: break-all;
  }
</style>
</head>
<body>

<?php require_once __DIR__ . "/../../includes/nav.php"; ?>   

<div class="watermark">Protected by SABooksOnline</div>

<div class="container">
  <!-- Sidebar chapters -->
  <div id="chapterNav">
    <h3>Chapters</h3>
    <ul id="chapters-list"></ul>
  </div>

  <!-- Main PDF pages -->
  <div class="pdf-container">
    <div id="pdf-pages"></div>
  </div>
</div>

<script>
  // Ensure pdfUrl is set
  <?php if (!isset($pdfUrl) || empty($pdfUrl)): ?>
    console.error('PDF URL is not set!');
    document.getElementById("pdf-pages").innerHTML = '<div class="error">Error: PDF URL is missing. Please contact support.</div>';
  <?php else: ?>
    const url = "<?= htmlspecialchars($pdfUrl, ENT_QUOTES, 'UTF-8') ?>";
    console.log('PDF URL:', url);
  <?php endif; ?>
  
  let pdfDoc = null,
      zoom = 1.5,
      chapterTitles = [];

  const pdfPagesContainer = document.getElementById("pdf-pages");

  // Disable right click & drag on canvas
  document.addEventListener("contextmenu", e => {
    if (e.target.nodeName === "CANVAS") e.preventDefault();
  });

  document.addEventListener("dragstart", e => {
    if (e.target.nodeName === "CANVAS") e.preventDefault();
  });

  // Dark mode preference
  if (localStorage.getItem("theme") === "dark") {
    document.body.classList.add("dark-mode");
  }

  function scrollToPage(pageNumber) {
    const canvasList = document.querySelectorAll("#pdf-pages canvas");
    if (canvasList[pageNumber - 1]) {
      canvasList[pageNumber - 1].scrollIntoView({ behavior: "smooth" });
    }
  }

  function renderPageLazy(pageNum) {
    pdfDoc.getPage(pageNum).then(page => {
      const canvas = document.createElement("canvas");
      const context = canvas.getContext("2d");
      const viewport = page.getViewport({ scale: zoom });

      canvas.width = viewport.width;
      canvas.height = viewport.height;

      const container = document.createElement("div");
      container.style.position = "relative";
      container.dataset.page = pageNum;

      const blocker = document.createElement("div");
      blocker.style.position = "absolute";
      blocker.style.top = 0;
      blocker.style.left = 0;
      blocker.style.width = "100%";
      blocker.style.height = "100%";
      blocker.style.zIndex = 10;
      blocker.oncontextmenu = e => e.preventDefault();

      container.appendChild(canvas);
      container.appendChild(blocker);
      pdfPagesContainer.replaceChild(container, document.querySelector(`.page-placeholder[data-page='${pageNum}']`));

      page.render({ canvasContext: context, viewport: viewport });
    });
  }

  function observePages() {
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const pageNum = parseInt(entry.target.dataset.page);
          renderPageLazy(pageNum);
          observer.unobserve(entry.target);
        }
      });
    }, { rootMargin: "500px" });

    for (let i = 1; i <= pdfDoc.numPages; i++) {
      const placeholder = document.createElement("div");
      placeholder.className = "page-placeholder";
      placeholder.dataset.page = i;
      placeholder.style.height = "1200px";
      pdfPagesContainer.appendChild(placeholder);
      observer.observe(placeholder);
    }
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
        let matches = textContent.match(/(Chapter\s+\d+|CHAPTER\s+[A-Z]+|\d+\.\s+[A-Z][^.]*)/gi);
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

  <?php if (isset($pdfUrl) && !empty($pdfUrl)): ?>
  // Show loading state
  pdfPagesContainer.innerHTML = '<div class="loading"><i class="fas fa-spinner fa-spin"></i> Loading PDF...</div>';

  // Load the PDF with error handling and CORS support
  pdfjsLib.getDocument({
    url: url,
    withCredentials: false,
    httpHeaders: {},
    // Enable CORS for cross-origin requests
    cMapUrl: 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/cmaps/',
    cMapPacked: true,
    // Use worker from CDN to avoid CORS issues
    workerSrc: 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js'
  }).promise.then(pdf => {
    pdfDoc = pdf;
    console.log('PDF loaded successfully. Total pages:', pdfDoc.numPages);
    observePages();
    extractTextFromPDF();
  }).catch(error => {
    console.error('Error loading PDF:', error);
    let errorMsg = 'Error loading PDF. ';
    if (error.message) {
      errorMsg += error.message;
    }
    
    // More detailed error information
    let errorDetails = '<div class="error-details">URL: ' + url + '</div>';
    
    // Check if it's a network/CORS error
    if (error.name === 'InvalidPDFException' || error.message.includes('Failed to fetch')) {
      errorDetails += '<div class="error-details">Possible causes:<br>';
      errorDetails += '1. The PDF file may not exist at this location<br>';
      errorDetails += '2. CORS headers may not be configured on the server<br>';
      errorDetails += '3. The file path may be incorrect</div>';
    }
    
    errorDetails += '<div class="error-details">If the PDF exists, this might be a CORS issue. Please contact support.</div>';
    
    pdfPagesContainer.innerHTML = '<div class="error">' + errorMsg + errorDetails + '</div>';
  });
  <?php endif; ?>
</script>

<!-- Navigation Scripts -->
<script src="/public/js/bootstrap.bundle.min.js"></script>
<script src="/public/js/main-script.js"></script>
<script src="/public/js/jquery-3.6.0.min.js"></script>

</body>
</html>
