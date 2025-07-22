from flask import Flask, render_template, request, jsonify
from modules.module_loader import load_modules

"""
Flask Web服务主程序

本文件是平台的核心后端服务，负责：
1. 初始化Flask应用
2. 自动加载所有功能模块
3. 提供RESTful API接口
4. 作为Gradio前端的后端API服务器

"""

app = Flask(__name__, template_folder='templates')

# 自动加载所有模块
app = load_modules(app)

if __name__ == '__main__':
    app.run(debug=True, port=5000)
