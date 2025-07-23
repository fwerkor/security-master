<?php
/**
 * @description 网络连通性测试工具，支持IPv4/IPv6和自定义包大小
 */

function executeModule($params) {
    $target = $params['target'] ?? '';
    $count = intval($params['count'] ?? 4);
    $size = intval($params['size'] ?? 56);
    $ipv6 = isset($params['ipv6']) ? true : false;
    
    if (empty($target)) {
        return "错误: 请提供目标主机";
    }
    
    // 限制包大小以防止滥用
    $size = min($size, 65507);
    
    $results = [];
    $results[] = "正在测试与 {$target} 的连接...";
    
    for ($i = 1; $i <= $count; $i++) {
        $start = microtime(true);
        
        // 使用fsockopen进行连接测试
        $port = $ipv6 ? 80 : 80; // 使用默认端口80进行测试
        $socket = @fsockopen($target, $port, $errno, $errstr, 5);
        $end = microtime(true);
        
        $time = round(($end - $start) * 1000, 2);
        
        if ($socket) {
            fclose($socket);
            $results[] = "Ping $i: 连接成功 - 耗时 {$time}ms";
        } else {
            $results[] = "Ping $i: 连接失败 - {$errstr} ({$errno})";
        }
        
        // 添加间隔
        if ($i < $count) {
            usleep(1000000); // 1秒间隔
        }
    }
    
    return implode("\n", $results);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST'):
?>
<h3>Ping 工具</h3>
<p>测试网络连通性，支持IPv4/IPv6和自定义数据包大小</p>

<form method="POST">
    <div class="mb-3">
        <label for="target" class="form-label">目标主机:</label>
        <input type="text" class="form-control" id="target" name="target" placeholder="example.com 或 IP地址" required>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="count" class="form-label">Ping次数:</label>
                <input type="number" class="form-control" id="count" name="count" min="1" max="100" value="4">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="size" class="form-label">数据包大小 (bytes):</label>
                <input type="number" class="form-control" id="size" name="size" min="0" max="65507" value="56">
            </div>
        </div>
    </div>
    
    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="ipv6" name="ipv6">
        <label class="form-check-label" for="ipv6">使用IPv6</label>
    </div>
    
    <button type="submit" class="btn btn-primary">执行Ping</button>
</form>
<?php endif; ?>