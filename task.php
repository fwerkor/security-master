<?php

class TaskManager {
    private $taskDir = 'tasks/';
    
    public function startTask($module, $params) {
        $taskId = uniqid('task_');
        $outputFile = $this->taskDir . $taskId . '.log';
        $pidFile = $this->taskDir . $taskId . '.pid';
        
        // 创建任务目录
        if(!file_exists($this->taskDir)) {
            mkdir($this->taskDir, 0777, true);
        }
        
        // 写入参数文件
        file_put_contents($this->taskDir . $taskId . '.params', serialize($params));
        
        // 启动后台进程
        $cmd = "php modules/{$module}.php " . escapeshellarg($taskId) . " > {$outputFile} 2>&1 & echo $!";
        $pid = shell_exec($cmd);
        
        // 保存进程ID
        file_put_contents($pidFile, $pid);
        
        return $taskId;
    }
    
    public function getTaskStatus($taskId) {
        $outputFile = $this->taskDir . $taskId . '.log';
        $pidFile = $this->taskDir . $taskId . '.pid';
        
        if(!file_exists($pidFile)) {
            return ['running' => false, 'output' => '任务不存在'];
        }
        
        $pid = file_get_contents($pidFile);
        $running = $this->isProcessRunning($pid);
        
        $output = '';
        if(file_exists($outputFile)) {
            $output = file_get_contents($outputFile);
        }
        
        return [
            'running' => $running,
            'output' => $output,
            'pid' => $pid
        ];
    }
    
    public function stopTask($taskId) {
        $pidFile = $this->taskDir . $taskId . '.pid';
        
        if(!file_exists($pidFile)) {
            return false;
        }
        
        $pid = file_get_contents($pidFile);
        exec("kill -9 " . $pid);
        unlink($pidFile);
        
        // 创建终止标记文件
        touch($this->taskDir . $taskId . '.stop');
        
        return true;
    }
    
    private function isProcessRunning($pid) {
        exec("ps -p " . $pid, $output, $return);
        return $return === 0;
    }
    
    public function isTaskStopped($taskId) {
        return file_exists($this->taskDir . $taskId . '.stop');
    }
}