<?php
/**
 * @description 分布式拒绝服务测试模块
 */

function executeModule($params, $taskId) {
    $target = $params['target'] ?? '';
    $duration = intval($params['duration'] ?? 60);
    $threads = intval($params['threads'] ?? 50);
    
    if (empty($target)) {
        saveProgress($taskId, "错误: 请提供目标主机");
        return;
    }
    
    // 限制最大持续时间
    $duration = min($duration, 3600);
    
    saveProgress($taskId, "正在启动DDoS测试...");
    saveProgress($taskId, "目标: {$target} | 持续时间: {$duration}s | 线程数: {$threads}");
    
    // 创建进程并保存PID
    $pidFile = sys_get_temp_dir() . "/task_{$taskId}.pid";
    
    // 使用ddos工具执行（示例）
    $command = "ddos_tool --target={$target} --duration={$duration} --threads={$threads}";
    
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
        saveProgress($taskId, "无法启动DDoS测试进程");
    }
}

// saveProgress, getProgress, isTaskRunning, stopTask functions (same as ping.php)
