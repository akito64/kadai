<?php
if (isset($_POST['color'])) {
    $colorCode = $_POST['color'];
    
    // RGBに変換
    $r = hexdec(substr($colorCode, 1, 2));
    $g = hexdec(substr($colorCode, 3, 2));
    $b = hexdec(substr($colorCode, 5, 2));

    // 画像生成
    header('Content-Type: image/png');
    $img = imagecreatetruecolor(500, 500);
    $color = imagecolorallocate($img, $r, $g, $b);
    imagefilledrectangle($img, 0, 0, 500, 500, $color);
    imagepng($img);
    imagedestroy($img);
    exit;
}
?>

<form method="post">

    <label>好きな色を選んで生成を押してください</label><br>
    <input type="color" name="color">
    <input type="submit" value="生成">
</form>


