<?php
// 日本時間を取得
$dt = new DateTime("now", new DateTimeZone("Asia/Tokyo"));
$hour = (int)$dt->format('G'); // 0〜23
$minute = (int)$dt->format('i');
$second = (int)$dt->format('s');
 
// 各針の角度を計算
$hour_angle = ($hour % 12 + $minute / 60) * 30;        // 360度 / 12時間
$minute_angle = ($minute + $second / 60) * 6;          // 360度 / 60分
$second_angle = $second * 6;                           // 360度 / 60秒
?>
 
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>PHPアナログ時計</title>
<style>
        body {
            text-align: center;
            font-family: sans-serif;
        }
        svg {
            margin-top: 20px;
        }
        .clock-face {
            fill: white;
            stroke: black;
            stroke-width: 4;
        }
        .hand {
            stroke-linecap: round;
        }
        .hour {
            stroke: black;
            stroke-width: 6;
        }
        .minute {
            stroke: black;
            stroke-width: 4;
        }
        .second {
            stroke: red;
            stroke-width: 2;
        }
        .tick {
            stroke: black;
            stroke-width: 1;
        }
</style>
</head>
<body>
<h1>現在の日本時間</h1>
<p><?php echo $dt->format('Y年m月d日 H:i:s'); ?></p>
 
    <svg width="300" height="300" viewBox="0 0 200 200">
<!-- 時計の円盤 -->
<circle cx="100" cy="100" r="95" class="clock-face"/>
 
        <!-- 目盛り -->
<?php for ($i = 0; $i < 60; $i++): 
            $angle = deg2rad($i * 6);
            $inner = ($i % 5 === 0) ? 85 : 90;
            $x1 = 100 + cos($angle) * 95;
            $y1 = 100 + sin($angle) * 95;
            $x2 = 100 + cos($angle) * $inner;
            $y2 = 100 + sin($angle) * $inner;
        ?>
<line x1="<?= $x1 ?>" y1="<?= $y1 ?>" x2="<?= $x2 ?>" y2="<?= $y2 ?>" class="tick"/>
<?php endfor; ?>
 
        <!-- 時針 -->
<line x1="100" y1="100" x2="100" y2="60"
              transform="rotate(<?= $hour_angle ?> 100 100)" class="hand hour"/>
 
        <!-- 分針 -->
<line x1="100" y1="100" x2="100" y2="40"
              transform="rotate(<?= $minute_angle ?> 100 100)" class="hand minute"/>
 
        <!-- 秒針 -->
<line x1="100" y1="100" x2="100" y2="30"
              transform="rotate(<?= $second_angle ?> 100 100)" class="hand second"/>
 
        <!-- 中心点 -->
<circle cx="100" cy="100" r="5" fill="black"/>
</svg>
</body>
</html>

