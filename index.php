<?php
/**
 * @description 安全测试平台入口文件
 */

define('APP_DIR', __DIR__);

// 任务管理路由
if (isset($_GET['task'])) {
    $taskId = $_GET['task_id'] ?? uniqid();
    
    if ($_GET['task'] == 'start') {
        $module = $_POST['module'] ?? '';
        $params = $_POST['params'] ?? [];
        
        if (empty($module) || !file_exists("modules/{$module}.php")) {
            http_response_code(400);
            echo json_encode(["error" => "无效的模块"]); exit;
        }
        
        // 异步执行任务
        $pid = pcntl_fork();
        
        if ($pid == -1) {
            http_response_code(500);
            echo json_encode(["error" => "无法创建进程"]); exit;
        } elseif ($pid == 0) {
            // 子进程
            require_once "modules/{$module}.php";
            executeModule($params, $taskId);
            exit;
        } else {
            // 父进程
            echo json_encode(["task_id" => $taskId]); exit;
        }
    }
    
    elseif ($_GET['task'] == 'progress') {
        $module = $_GET['module'] ?? '';
        if (!file_exists("modules/{$module}.php")) {
            http_response_code(400);
            echo json_encode(["error" => "无效的模块"]); exit;
        }
        
        require_once "modules/{$module}.php";
        $progress = getProgress($taskId);
        $running = isTaskRunning($taskId);
        
        echo json_encode([
            "running" => $running,
            "progress" => $progress
        ]);
        exit;
    }
    
    elseif ($_GET['task'] == 'stop') {
        $module = $_GET['module'] ?? '';
        if (!file_exists("modules/{$module}.php")) {
            http_response_code(400);
            echo json_encode(["error" => "无效的模块"]); exit;
        }
        
        require_once "modules/{$module}.php";
        stopTask($taskId);
        echo json_encode(["success" => true]);
        exit;
    }
}

// 常规请求处理
$template = "templates/index.html.php";
if (isset($_GET['module'])) {
    $module = $_GET['module'];
    if (file_exists("templates/module.{$module}.php")) {
        $template = "templates/module.{$module}.php";
    }
}

require_once $template;