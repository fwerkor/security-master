<?php
// 设置SSE头部
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

$module = $_GET['module'] ?? '';

if (empty($module)) {
    echo "data: 错误: 未指定模块\n\n";
    flush();
    exit;
}

$moduleFile = __DIR__ . '/modules/' . $module . '.php';

if (!file_exists($moduleFile)) {
    echo "data: 错误: 模块文件不存在\n\n";
    flush();
    exit;
}

// 包含模块文件
include $moduleFile;

// 检查是否提供了POST数据
if (isset($_POST['target'])) {
    // 模拟流式处理过程
    $params = $_POST;
    
    // 使用模块中的executeModule函数
    if (function_exists('executeModule')) {
        // 获取执行结果
        $result = executeModule($params);
        
        // 将结果按行发送
        $lines = explode("\n", $result);
        foreach ($lines as $line) {
            echo "data: " . $line . "\n\n";
            flush();
            
            // 添加小延迟以模拟流式效果
            usleep(100000); // 0.1秒
        }
    } else {
        echo "data: 错误: 模块中未定义executeModule函数\n\n";
        flush();
    }
} else {
    echo "data: 错误: 无效的请求参数\n\n";
    flush();
}

exit;
?>