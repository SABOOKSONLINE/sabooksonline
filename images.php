<?php
$dir = './cms-data/book-covers/';

echo 'love';

function displayImgs($dir, $n=10){
$files = glob($dir.'*.jpg');
shuffle($files);
$files = array_slice($files, 0, $n);
foreach($files as $file) { ?>
<div class="item"><img src="<?php echo $file;?>"></div>
<?php } 
} ?>