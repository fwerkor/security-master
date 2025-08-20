<?php
/**
 * Ping工具模块
 * 支持IPv4/IPv6和自定义包大小
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = $_POST['host'] ?? '';
    $packetSize = $_POST['packetSize'] ?? 32;
    $ipVersion = $_POST['ipVersion'] ?? 'ipv4';

    if (empty($host)) {
        echo json_encode(['error' => '请输入目标主机']);
        exit;
    }

    $command = $ipVersion === 'ipv6' ? "ping6" : "ping";
    $output = shell_exec("$command -c 4 -s $packetSize $host");

    echo json_encode(['output' => $output]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ping工具</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Ping工具</h1>
        <form id="ping-form">
            <label for="host">目标主机:</label>
            <input type="text" id="host" name="host" required>

            <label for="packetSize">包大小 (bytes):</label>
            <input type="number" id="packetSize" name="packetSize" min="1" max="65500" value="32">

            <label for="ipVersion">IP版本:</label>
            <select id="ipVersion" name="ipVersion">
                <option value="ipv4">IPv4</option>
                <option value="ipv6">IPv6</option>
            </select>

            <button type="submit">执行Ping</button>
        </form>
        <div id="result"></div>
    </div>
    <script>
        document.getElementById('ping-form').addEventListener('submit', function(e) {
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