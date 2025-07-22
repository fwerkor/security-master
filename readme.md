# 网络安全测试平台

本平台包含多种网络测试功能，采用模块化设计，通过Gradio自动生成Web界面，无需手动编写HTML代码。所有功能仅用于合法授权的网络安全测试。

## 功能列表

1. **Ping测试**：检测网络连通性和延迟
2. **TCPing测试**：检测TCP端口可达性
3. **HTTP测试**：检测Web服务响应情况
4. **DDoS测试**：模拟压力测试（仅限测试环境使用）
5. **端口扫描**：检测目标主机开放端口
6. **DNS查询**：查询域名解析记录
7. **API文档**：提供完整的API接口说明文档

## 系统架构

采用模块化设计，支持自动加载新功能模块：
```
├── app.py                  # Flask Web服务主程序（必需）
├── run_gradio.py           # Gradio前端启动脚本
├── requirements.txt        # 依赖库列表
├── modules/                # 功能模块目录
│   ├── module_loader.py    # 模块自动加载器
│   ├── api_docs_module.py  # API文档模块
│   ├── ping_module.py      # Ping测试模块
│   ├── tcping_module.py    # TCPing测试模块
│   ├── http_module.py      # HTTP测试模块
│   ├── ddos_module.py      # DDoS测试模块
│   ├── portscan_module.py  # 端口扫描模块
│   └── dns_module.py       # DNS查询模块
└── gradio_app.py           # Gradio前端界面
```

## 使用方法

### 安装依赖
```bash
pip install -r requirements.txt
```

### 启动服务
```bash
# 启动Flask后端服务（在第一个终端窗口运行）
python app.py

# 启动Gradio前端界面（在第二个终端窗口运行）
python run_gradio.py
```

服务启动后，访问以下地址：
- Web界面：http://localhost:7860
- API文档：http://localhost:5000/api/docs

### 功能使用
通过Web界面提供的表单填写参数，提交后查看测试结果。各功能参数说明：

- Ping测试：输入域名或IP地址
- TCPing测试：输入域名/IP和端口号
- HTTP测试：输入完整URL（包含http://或https://）
- DDoS测试：输入测试URL和请求次数（仅限测试环境）
- 端口扫描：输入域名/IP和端口范围（如 20-100）
- DNS查询：输入域名和选择查询类型（A、AAAA、CNAME、MX、NS、TXT）

## 添加新功能

要添加新功能模块，请在modules目录下创建新的模块文件（格式参考现有模块），实现以下两个部分：

1. 功能函数：实现具体的安全测试逻辑
2. register_api函数：注册API路由

示例模块结构：
```python
# my_module.py

def my_function(param1, param2):
    # 实现功能逻辑
    return {'success': True, 'output': '测试结果'}

def register_api(app):
    @app.route('/api/myendpoint', methods=['POST'])
    def api_myendpoint():
        data = request.get_json()
        result = my_function(data['param1'], data['param2'])
        return jsonify(result)
```

## 注意事项

1. DDoS测试和端口扫描功能仅限在合法授权的测试环境中使用
2. 请遵守《中华人民共和国网络安全法》等相关法律法规
3. 不得使用本平台进行任何非法网络攻击
4. 禁止对非授权目标进行安全测试