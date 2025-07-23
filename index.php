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

// 如果请求特定模块且模块存在
if ($module && in_array($module, getModules())) {
    include 'templates/module.html.php';
} else {
    // 否则显示主页
    include 'templates/index.html.php';
}
?>