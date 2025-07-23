<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>安全测试平台</title>
    <script src="//code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>安全测试平台</h1>
    
    <?php if (!isset($_GET['module'])): ?>
    <ul>
        <li><a href="?module=ping">网络连通性测试</a></li>
        <li><a href="?module=ddos">DDoS测试</a></li>
        <li><a href="?module=tcping">TCP连接测试</a></li>
    </ul>
    <?php else: ?>
    
    <a href=""><< 返回首页</a>
    <h2><?= htmlspecialchars($_GET['module']) ?> 模块</h2>
    
    <!-- 参数表单 -->
    <form id="module-form">
        <?php require_once "module.{$_GET['module']}.php"; ?>
        <button type="submit">执行</button>
        <button type="button" id="stop-btn" style="display: none;">终止任务</button>
    </form>
    
    <!-- 实时输出区域 -->
    <div id="output" style="margin-top: 20px; padding: 10px; border: 1px solid #ccc; white-space: pre-wrap;"></div>
    
    <script>
    let taskId = null;
    let polling = null;
    
    $(document).ready(function() {
        $('#module-form').on('submit', function(e) {
            e.preventDefault();
            
            // 重置界面
            $('#output').empty();
            $('#stop-btn').hide();
            taskId = null;
            
            // 收集表单数据
            const formData = {};
            $(this).find('input, select, textarea').each(function() {
                const name = $(this).attr('name');
                const value = $(this).val();
                formData[name] = value;
            });
            
            // 启动任务
            $.post('?task=start&module=<?= $_GET['module'] ?>', {
                params: formData
            }, function(response) {
                if (response.error) {
                    alert(response.error);
                    return;
                }
                
                taskId = response.task_id;
                $('#stop-btn').show();
                
                // 开始轮询进度
                polling = setInterval(() => {
                    $.get('?task=progress&module=<?= $_GET['module'] ?>&task_id=' + taskId, function(progress) {
                        if (progress.running || progress.progress.length > 0) {
                            // 更新输出
                            $('#output').empty();
                            progress.progress.forEach(line => {
                                $('#output').append(line + '\n');
                            });
                            
                            // 自动滚动到底部
n                            $('#output').scrollTop($('#output')[0].scrollHeight);
                            
                            // 如果任务完成，停止轮询
                            if (!progress.running && taskId) {
                                clearInterval(polling);
                                $('#stop-btn').hide();
                                taskId = null;
                            }
                        }
                    });
                }, 1000);
            });
        });
        
        $('#stop-btn').on('click', function() {
            if (taskId) {
                $.get('?task=stop&module=<?= $_GET['module'] ?>&task_id=' + taskId, function() {
                    clearInterval(polling);
                    $('#stop-btn').hide();
                    taskId = null;
                });
            }
        });
    });
    </script>
    
    <?php endif; ?>
</body>
</html>