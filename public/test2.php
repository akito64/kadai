<?php
if (function_exists('gd_info')) {
    echo "GDライブラリは有効です！<br>";
    print_r(gd_info());
} else {
    echo "GDライブラリは使えません。";
}
?>


