<?php
/**
 * TCP Ping工具模块
 * 支持指定端口和超时时间
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = $_POST['host'] ?? '';
    $port = $_POST['port'] ?? 80;
    $timeout = $_POST['timeout'] ?? 5;

    if (empty($host)) {
        echo json_encode(['error' => '请输入目标主机']);
        exit;
    }

    $start = microtime(true);
    $fp = @fsockopen($host, $port, $errno, $errstr, $timeout);
    $end = microtime(true);

    if ($fp) {
        fclose($fp);
        $latency = round(($end - $start) * 1000, 2);
        echo json_encode(['output' => "TCP Ping成功: {$latency}ms"]);
    } else {
        echo json_encode(['error' => "TCP Ping失败: $errstr ($errno)"]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TCP Ping工具</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>TCP Ping工具</h1>
        <form id="tcping-form">
            <label for="host">目标主机:</label>
            <input type="text" id="host" name="host" required>

            <label for="port">端口:</label>
            <input type="number" id="port" name="port" min="1" max="65535" value="80">

            <label for="timeout">超时时间 (秒):</label>
            <input type="number" id="timeout" name="timeout" min="1" max="30" value="5">

            <button type="submit">执行TCP Ping</button>
        </form>
        <div id="result"></div>
    </div>
    <script>
        document.getElementById('tcping-form').addEventListener('submit', function(e) {
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