<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= ucfirst($module) ?> - Web安全工具箱</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }
        .result-container {
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            padding: 1.5rem;
            margin-top: 1rem;
            max-height: 400px;
            overflow-y: auto;
        }
        .form-container {
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            padding: 1.5rem;
        }
        pre {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 0.5rem;
            max-height: 400px;
            overflow: auto;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="d-inline-block"><?= ucfirst($module) ?></h1>
                    <a href="index.php" class="btn btn-light float-end">返回工具箱</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="form-container">
                    <?php
                    // 从modules目录加载模块
                    $moduleFile = __DIR__ . '/../modules/' . $module . '.php';
                    
                    if (file_exists($moduleFile)) {
                        include $moduleFile;
                    } else {
                        echo '<div class="alert alert-danger">模块文件不存在</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="result-container">
                    <h3>执行结果</h3>
                    <pre id="stream-output"></pre>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0">&copy; <?= date('Y') ?> Web安全工具箱. 所有工具仅供合法安全测试使用.</p>
                </div>
            </div>
        </div>
    </footer>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <script>
        // 收集表单数据并创建查询字符串
        const form = document.querySelector('form');
        const formData = new FormData(form);
        let params = new URLSearchParams();
        
        // 添加所有表单字段到参数
        for (let [key, value] of formData.entries()) {
            params.append(key, value);
        }
        
        // 使用EventSource实现实时流式输出
        const source = new EventSource("stream.php?module=<?= urlencode($module) ?>&" + params.toString());
        
        source.onmessage = function(event) {
            const output = document.getElementById('stream-output');
            output.textContent += event.data + "\n";
            output.scrollTop = output.scrollHeight;
        };
    </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>