<?php
// 主入口文件
session_start();

// 自动加载模块
function getModules() {
    $modules = [];
    $modulesDir = __DIR__ . '/modules';
    
    if (is_dir($modulesDir)) {
        if ($handle = opendir($modulesDir)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." && pathinfo($entry, PATHINFO_EXTENSION) == 'php') {
                    $moduleName = pathinfo($entry, PATHINFO_FILENAME);
                    $modules[] = $moduleName;
                }
            }
            closedir($handle);
        }
    }
    
    return $modules;
}

// 获取当前请求的模块
$module = isset($_GET['module']) ? $_GET['module'] : null;

if (isset($_GET['module'])) {
    $module = $_GET['module'];
    $function = $module . '_parameters';

    if (function_exists($function)) {
        echo $function();
    } else {
        echo "Parameters function for $module not found.";
    }
    exit;
}

// 如果请求特定模块且模块存在
if ($module && in_array($module, getModules())) {
    // 设置模块文件路径
    $moduleFile = __DIR__ . '/modules/' . $module . '.php';
    
    // 将模块文件路径传递给模板
    include 'templates/module.html.php';
} else {
    // 否则显示主页
    include 'templates/index.html.php';
}

if (isset($_GET['action']) && $_GET['action'] === 'start') {
    header('Content-Type: text/event-stream');
    header('Cache-Control: no-cache');

    $module = $_GET['module'];
    $function = $module . '_execute';

    if (function_exists($function)) {
        $output = $function($_GET, false);
        echo $output;
    } else {
        echo "Execute function for $module not found.";
    }
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'stop') {
    // Handle task termination
    $module = $_GET['module'];
    $function = $module . '_stop';

    if (function_exists($function)) {
        $function($_GET);
    } else {
        echo "Stop function for $module not found.";
    }
    exit;
}

?>