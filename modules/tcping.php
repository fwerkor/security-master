<?php
/**
 * @description TCP连接测试模块
 */

function executeModule($params, $taskId) {
    $target = $params['target'] ?? '';
    $port = intval($params['port'] ?? 80);
    $timeout = intval($params['timeout'] ?? 3);
    
    if (empty($target)) {
        saveProgress($taskId, "错误: 请提供目标主机");
        return;
    }
    
    saveProgress($taskId, "正在测试TCP连接: {$target}:{$port}");
    
    // 创建进程并保存PID
    $pidFile = sys_get_temp_dir() . "/task_{$taskId}.pid";
    
    // 使用tcping工具执行（示例）
    $command = "tcping --host={$target} --port={$port} --timeout={$timeout}";
    
    $descriptorspec = [
        0 => ["pipe", "r"],
        1 => ["pipe", "w"],
        2 => ["pipe", "w"]
    ];
    
    $process = proc_open($command, $descriptorspec, $pipes);
    if (is_resource($process)) {
        // 保存进程PID
        file_put_contents($pidFile, proc_get_status($process)['pid']);
        
        // 实时读取输出
        while (!feof($pipes[1])) {
            $line = fgets($pipes[1]);
            if (trim($line)) {
                saveProgress($taskId, $line);
            }
            
            // 检查是否需要终止
            if (file_exists(sys_get_temp_dir() . "/task_{$taskId}.stop")) {
                proc_terminate($process);
                saveProgress($taskId, "任务已手动终止");
                break;
            }
        }
        
        // 关闭进程
        foreach ($pipes as $pipe) {
            fclose($pipe);
        }
        proc_close($process);
        
        // 清理PID文件
        @unlink($pidFile);
    } else {
        saveProgress($taskId, "无法启动TCPing进程");
    }
}

// saveProgress, getProgress, isTaskRunning, stopTask functions (same as ping.php)
