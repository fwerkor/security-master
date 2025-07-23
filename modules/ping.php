<?php
function executeModule($params) {
    $target = escapeshellarg($params['target'] ?? '');
    $count = intval($params['count'] ?? 4);
    $size = intval($params['size'] ?? 56);
    $ipv6 = isset($params['ipv6']) ? '-6' : '';
    
    if (empty($target)) {
        return "错误: 请提供目标主机";
    }
    
    // 限制包大小以防止滥用
    $size = min($size, 65507);
    
    $command = "ping $ipv6 -c $count -s $size $target 2>&1";
    return shell_exec($command);
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