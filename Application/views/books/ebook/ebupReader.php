<?php
require_once __DIR__ . "/../../includes/header.php";
?>

<!-- epub.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/epub.js/0.3.88/epub.min.js"></script>

<style>
  body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background-color: #f4f4f4;
    color: #333;
  }

  .dark-mode {
    background-color: #121212;
    color: #eee;
  }

  .container {
    display: flex;
    max-width: 100%;
    height: 100vh;
    margin: 0;
  }

  /* Sidebar chapters */
  #chapterNav {
    width: 250px;
    height: 100vh;
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

  /* Reader area */
  #viewer {
    flex: 1;
    height: 100vh;
    overflow: auto;
    background: #fafafa;
  }

  .dark-mode #viewer {
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

  <!-- EPUB Viewer -->
  <div id="viewer"></div>
</div>

<script>
  const url = "<?= htmlspecialchars($pdfUrl, ENT_QUOTES, 'UTF-8') ?>";

  // Dark mode preference
  if (localStorage.getItem("theme") === "dark") {
    document.body.classList.add("dark-mode");
  }

  // Load EPUB
  const book = ePub(url);
  const rendition = book.renderTo("viewer", {
    width: "100%",
    height: "100%",
  });

  rendition.display();

  // Navigation (chapters)
  book.ready.then(() => {
    return book.loaded.navigation;
  }).then(nav => {
    const chapterList = document.getElementById("chapters-list");
    nav.toc.forEach(chap => {
      const li = document.createElement("li");
      li.textContent = chap.label;
      li.onclick = () => rendition.display(chap.href);
      chapterList.appendChild(li);
    });
  });
</script>

</body>
</html>
