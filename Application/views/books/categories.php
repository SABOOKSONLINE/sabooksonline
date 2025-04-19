<?php

foreach ($categories as $category) {
    echo '
    <a href="?category=' . $category['CATEGORY'] . '" class="category-link">' . $category['CATEGORY'] . '</a>
    ';
}
