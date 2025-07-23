<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web安全工具箱</title>
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
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 1rem 2rem rgba(0, 0, 0, 0.2);
        }
        .tool-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h1>Web安全工具箱</h1>
                    <p class="lead">一个功能强大的网络安全工具集合</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2>可用工具</h2>
                <p>以下是我们提供的网络安全工具，每个工具都有丰富的选项供您使用。</p>
            </div>
        </div>

        <div class="row">
            <?php 
            // 从modules目录读取模块信息
            $modulesDir = __DIR__ . '/../modules';
            $moduleFiles = glob($modulesDir . '/*.php');
            
            if ($moduleFiles) {
                foreach ($moduleFiles as $moduleFile) {
                    $moduleName = pathinfo($moduleFile, PATHINFO_FILENAME);
                    $moduleDescription = '';
                    
                    // 读取模块文件中的描述信息
                    $moduleContent = file_get_contents($moduleFile);
                    preg_match('/@description\s+(.*)/', $moduleContent, $matches);
                    if (isset($matches[1])) {
                        $moduleDescription = $matches[1];
                    }
            ?>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <div class="tool-icon">
                            <?php 
                            // 根据工具名称显示不同图标
                            switch($moduleName) {
                                case 'ping':
                                    echo '📡';
                                    break;
                                case 'tcping':
                                    echo '🔌';
                                    break;
                                case 'ddos':
                                    echo '🔥';
                                    break;
                                default:
                                    echo '🛠️';
                            }
                            ?>
                        </div>
                        <h5 class="card-title"><?= ucfirst($moduleName) ?></h5>
                        <p class="card-text">
                            <?= $moduleDescription ?: '网络安全测试工具' ?>
                        </p>
                        <a href="?module=<?= $moduleName ?>" class="btn btn-primary">使用工具</a>
                    </div>
                </div>
            </div>
            <?php 
                }
            } else {
            ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <h4>暂无工具</h4>
                    <p>请在 <code>modules</code> 目录下添加工具模块</p>
                </div>
            </div>
            <?php } ?>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>