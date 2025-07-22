import subprocess
import os
from flask import jsonify

def ping_host(host):
    """
    执行Ping测试
    """
    param_count = '-n' if os.name == 'nt' else '-c'
    try:
        output = subprocess.run(['ping', param_count, '4', host], 
                              capture_output=True, text=True, timeout=10)
        return {'success': True, 'output': output.stdout}
    except Exception as e:
        return {'success': False, 'error': f"Ping测试失败: {str(e)}"}

def register_api(app):
    """
    注册Ping模块的API路由
    """
    @app.route('/api/ping', methods=['POST'])
    def api_ping():
        data = request.get_json()
        result = ping_host(data['host'])
        return jsonify(result)