<?php
/**
 * @description TCP端口连通性测试工具
 */

function executeModule($params) {
    $target = $params['target'] ?? '';
    $port = intval($params['port'] ?? 80);
    $count = intval($params['count'] ?? 4);
    $timeout = intval($params['timeout'] ?? 3);
    
    if (empty($target)) {
        return "错误: 请提供目标主机";
    }
    
    if ($port <= 0 || $port > 65535) {
        return "错误: 端口号必须在1-65535之间";
    }
    
    $results = [];
    $results[] = "正在测试 {$target}:{$port} 的TCP连接...";
    
    for ($i = 1; $i <= $count; $i++) {
        $start = microtime(true);
        
        // 使用fsockopen进行TCP连接测试
        $socket = @fsockopen($target, $port, $errno, $errstr, $timeout);
        $end = microtime(true);
        
        $time = round(($end - $start) * 1000, 2);
        
        if ($socket) {
            fclose($socket);
            $results[] = "TCPing $i: 连接成功 - 耗时 {$time}ms";
        } else {
            $results[] = "TCPing $i: 连接失败 - {$errstr} ({$errno})";
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
<h3>TCPing 工具</h3>
<p>测试指定主机的TCP端口连通性</p>

<form method="POST">
    <div class="row">
        <div class="col-md-8">
            <div class="mb-3">
                <label for="target" class="form-label">目标主机:</label>
                <input type="text" class="form-control" id="target" name="target" placeholder="example.com 或 IP地址" required>
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3">
                <label for="port" class="form-label">端口号:</label>
                <input type="number" class="form-control" id="port" name="port" min="1" max="65535" value="80" required>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="count" class="form-label">测试次数:</label>
                <input type="number" class="form-control" id="count" name="count" min="1" max="100" value="4">
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="timeout" class="form-label">超时时间 (秒):</label>
                <input type="number" class="form-control" id="timeout" name="timeout" min="1" max="30" value="3">
            </div>
        </div>
    </div>
    
    <button type="submit" class="btn btn-primary">执行TCPing</button>
</form>
<?php endif; ?>