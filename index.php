<?php
// 动态加载工具列表
$toolsDir = 'modules/';
$tools = [];
if (is_dir($toolsDir)) {
    if ($dh = opendir($toolsDir)) {
        while (($file = readdir($dh)) !== false) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $tools[] = $file;
            }
        }
        closedir($dh);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>网络安全工具箱</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>网络安全工具箱</h1>
            <p>一个简洁美观的网络安全工具集合</p>
        </header>
        <main>
            <div class="tools-list" id="tools-list">
                <?php foreach ($tools as $tool): ?>
                    <div class="tool-card" onclick="window.location.href='modules/<?= $tool ?>'">
                        <h2><?= pathinfo($tool, PATHINFO_FILENAME) ?></h2>
                        <p>点击使用<?= pathinfo($tool, PATHINFO_FILENAME) ?>工具</p>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
    </div>
</body>
</html>