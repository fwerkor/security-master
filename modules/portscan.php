<?php
/**
 * 端口扫描工具模块
 * 支持指定端口范围和扫描类型
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = $_POST['host'] ?? '';
    $startPort = $_POST['startPort'] ?? 1;
    $endPort = $_POST['endPort'] ?? 1024;
    $scanType = $_POST['scanType'] ?? 'tcp';

    if (empty($host)) {
        echo json_encode(['error' => '请输入目标主机']);
        exit;
    }

    $results = [];
    for ($port = $startPort; $port <= $endPort; $port++) {
        $fp = @fsockopen($host, $port, $errno, $errstr, 1);
        if ($fp) {
            fclose($fp);
            $results[] = "端口 $port 开放";
        }
    }

    echo json_encode(['output' => implode("\n", $results)]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>端口扫描工具</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>端口扫描工具</h1>
        <form id="portscan-form">
            <label for="host">目标主机:</label>
            <input type="text" id="host" name="host" required>

            <label for="startPort">起始端口:</label>
            <input type="number" id="startPort" name="startPort" min="1" max="65535" value="1">

            <label for="endPort">结束端口:</label>
            <input type="number" id="endPort" name="endPort" min="1" max="65535" value="1024">

            <label for="scanType">扫描类型:</label>
            <select id="scanType" name="scanType">
                <option value="tcp">TCP</option>
                <option value="udp">UDP</option>
            </select>

            <button type="submit">开始扫描</button>
        </form>
        <div id="result"></div>
    </div>
    <script>
        document.getElementById('portscan-form').addEventListener('submit', function(e) {
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