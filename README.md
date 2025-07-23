# 网络安全工具箱

一个基于Web界面的Python网络安全工具箱，具有模块化设计和美观的用户界面。

## 功能特点

- 模块化设计：通过增删 `modules` 目录下的文件即可调整工具列表
- 动态加载：程序运行时会自动从 `modules` 文件夹内读取工具列表
- 美观界面：使用Bootstrap框架构建响应式Web界面
- 易于扩展：每个工具对应一个独立的Python文件，包含参数定义和功能实现

## 安装和运行

1. 安装依赖：
```bash
pip install -r requirements.txt
```

2. 运行应用：
```bash
python app.py
```

3. 访问应用：
在浏览器中打开 `http://localhost:5000`

## 工具列表

- **Ping工具**: 测试主机连通性，支持IPv4/IPv6、自定义包大小等选项
- **TCPing工具**: 测试TCP端口连通性
- **DDoS模拟工具**: DDoS攻击模拟测试（仅用于教育目的）

## 添加新工具

只需在 `modules` 目录下创建新的Python文件，包含以下函数：

1. `title()` - 返回工具标题
2. `get_form()` - 返回HTML表单代码，用于参数输入
3. `run(args)` - 实现工具功能，接收参数字典，返回执行结果

## 注意事项

- 本工具箱仅供合法的网络安全测试使用
- 请确保在使用任何工具前已获得目标系统的授权
- DDoS工具仅为模拟功能，不执行真实的网络攻击

## 项目结构

```
security-master/
├── app.py              # 主应用文件
├── requirements.txt    # 依赖列表
├── README.md           # 说明文档
├── modules/            # 工具模块目录
│   ├── __init__.py
│   ├── ping.py         # Ping工具
│   ├── tcping.py       # TCPing工具
│   └── ddos.py         # DDoS模拟工具
├── templates/          # HTML模板目录
│   ├── index.html      # 主页模板
│   └── module.html     # 工具页面模板
└── static/             # 静态文件目录
```