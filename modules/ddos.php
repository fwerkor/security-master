<?php
/**
 * DDOS测试工具模块
 * 支持指定请求次数和并发数
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $target = $_POST['target'] ?? '';
    $requests = $_POST['requests'] ?? 100;
    $concurrency = $_POST['concurrency'] ?? 10;

    if (empty($target)) {
        echo json_encode(['error' => '请输入目标URL']);
        exit;
    }

    $results = [];
    for ($i = 0; $i < $requests; $i++) {
        $ch = curl_init($target);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $results[] = "请求 #$i: HTTP $httpCode";
    }

    echo json_encode(['output' => implode("\n", $results)]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DDOS测试工具</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>DDOS测试工具</h1>
        <form id="ddos-form">
            <label for="target">目标URL:</label>
            <input type="text" id="target" name="target" required>

            <label for="requests">请求次数:</label>
            <input type="number" id="requests" name="requests" min="1" max="1000" value="100">

            <label for="concurrency">并发数:</label>
            <input type="number" id="concurrency" name="concurrency" min="1" max="100" value="10">

            <button type="submit">开始测试</button>
        </form>
        <div id="result"></div>
    </div>
    <script>
        document.getElementById('ddos-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('result').innerHTML = `<pre>${data.output || data.error}</pre>`;
            });
        });
    </script>
</body>
</html>