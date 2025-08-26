<?php
$filename = 'images/syatiku.jpg';

if (file_exists($filename)) {
    echo '<img src="' . $filename . '" width="300"><br>';
    echo '画像のexif情報<br><pre>';
    print_r(exif_read_data($filename, 0, true));
    echo '</pre>';
} else {
    echo "ファイルが見つかりません。";
}
?>


