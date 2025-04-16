<?php
// echo $_SERVER["REQUEST_URI"];
// foreach ($books as $book) {
//     echo '
//         <div class="library-book-card col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4 gap-3">
//             <a href="/library/book/' . strtolower($book['CONTENTID']) . '">
//                 <img src="https://sabooksonline.co.za/cms-data/book-covers/' . $book['COVER'] . '" alt="' . strtolower($book['TITLE']) . '">
//             </a>
//             <div class="w-100">
//                 <a class="book-card-little text-capitalize" href="/library/book/' . strtolower($book['CONTENTID']) . '">
//                     ' . (strlen($book['TITLE']) > 30 ? substr($book['TITLE'], 0, 30) . '...' : $book['TITLE']) . '
//                 </a>
//                 <p>' . (strlen($book['DESCRIPTION']) > 125 ? substr($book['DESCRIPTION'], 0, 125) . '...' : $book['DESCRIPTION']) . '</p>
//                 <span class="book-card-pub">
//                     Published by: <a class="text-muted" href="/creators/creator/' . strtolower($book['USERID']) . '">' . ucwords($book['PUBLISHER']) . '</a>
//                 </span>
//             </div>
//         </div>
//     ';
// };

foreach ($books as $book) {
    $title = htmlspecialchars($book['TITLE']);
    $desc = htmlspecialchars($book['DESCRIPTION']);
    $cover = 'https://sabooksonline.co.za/cms-data/book-covers/' . $book['COVER'];
    $link = '/library/book/' . strtolower($book['CONTENTID']);

    echo '
        <div class="library-book-card col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-4 gap-3"
             data-title="' . $title . '"
             data-desc="' . $desc . '"
             data-cover="' . $cover . '"
             data-link="' . $link . '">
             
            <a href="' . $link . '">
                <img src="' . $cover . '" alt="' . strtolower($title) . '">
            </a>
            <div class="w-100">
                <a class="book-card-little text-capitalize" href="' . $link . '">
                    ' . (strlen($title) > 30 ? substr($title, 0, 30) . '...' : $title) . '
                </a>
                <p>' . (strlen($desc) > 125 ? substr($desc, 0, 125) . '...' : $desc) . '</p>
                <span class="book-card-pub">
                    Published by: <a class="text-muted" href="/creators/creator/' . strtolower($book['USERID']) . '">' . ucwords($book['PUBLISHER']) . '</a>
                </span>
            </div>
        </div>
    ';
};
