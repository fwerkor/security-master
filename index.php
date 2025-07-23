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
    // 设置模块文件路径
    $moduleFile = __DIR__ . '/modules/' . $module . '.php';
    
    // 将模块文件路径传递给模板
    include 'templates/module.html.php';
} else {
    // 否则显示主页
    include 'templates/index.html.php';
}

// 开启输出缓冲
ob_start();

// 加载模块
$module = isset($_GET['module']) ? $_GET['module'] : 'index';
$moduleFile = "modules/{$module}.php";

if (file_exists($moduleFile)) {
    // 引入模块并执行
    require $moduleFile;
    
    // 实时输出结果
    ob_flush();
    flush();
} else {
    echo "Module not found.";
}

// 渲染模板
$template = "templates/{$module}.html.php";
if (file_exists($template)) {
    require $template;
} else {
    echo "Template not found.";
}

// 最终刷新输出
ob_end_flush();

?>