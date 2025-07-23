<?php
/**
 * @description 网络连通性测试工具，支持IPv4/IPv6和自定义包大小
 */

function executeModule($params, $taskId) {
    $target = $params['target'] ?? '';
    $count = intval($params['count'] ?? 4);
    $size = intval($params['size'] ?? 56);
    $ipv6 = isset($params['ipv6']) ? true : false;
    
    if (empty($target)) {
        saveProgress($taskId, "错误: 请提供目标主机");
        return;
    }
    
    // 限制包大小以防止滥用
    $size = min($size, 65507);
    
    saveProgress($taskId, "正在测试与 {$target} 的连接...");
    
    // 创建进程并保存PID
    $pidFile = sys_get_temp_dir() . "/task_{$taskId}.pid";
    
    // 使用proc_open执行命令以获取实时输出
    $command = $ipv6 ? "ping6" : "ping";
    $command .= " -c {$count} -s {$size} {$target}";
    
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
        saveProgress($taskId, "无法启动ping进程");
    }
}

// 保存进度到临时文件
function saveProgress($taskId, $message) {
    $progressFile = sys_get_temp_dir() . "/task_{$taskId}.log";
    file_put_contents($progressFile, $message . "\n", FILE_APPEND);
    
    // 刷新输出缓冲区
    ob_flush();
    flush();
}

// 获取进度
function getProgress($taskId) {
    $progressFile = sys_get_temp_dir() . "/task_{$taskId}.log";
    if (!file_exists($progressFile)) {
        return [];
    }
    
    return explode("\n", trim(file_get_contents($progressFile)));
}

// 检查任务是否运行中
function isTaskRunning($taskId) {
    $pidFile = sys_get_temp_dir() . "/task_{$taskId}.pid";
    if (!file_exists($pidFile)) {
        return false;
    }
    
    $pid = intval(file_get_contents($pidFile));
    if ($pid <= 0) {
        return false;
    }
    
    // 检查进程是否存在
    exec("ps -p {$pid} -o pid=", $output);
    return !empty($output);
}

// 终止任务
function stopTask($taskId) {
    $stopFile = sys_get_temp_dir() . "/task_{$taskId}.stop";
    touch($stopFile);
}