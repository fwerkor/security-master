import socket
import time
from flask import jsonify

def tcping_host(host, port):
    """
    执行TCPing测试
    """
    try:
        start_time = time.time()
        sock = socket.create_connection((host, port), timeout=5)
        end_time = time.time()
        sock.close()
        return {'success': True, 'output': f"TCP连接成功，响应时间: {(end_time - start_time)*1000:.2f}ms"}
    except Exception as e:
        return {'success': False, 'error': f"TCP连接失败: {str(e)}"}

def register_api(app):
    """
    注册TCPing模块的API路由
    """
    @app.route('/api/tcping', methods=['POST'])
    def api_tcping():
        data = request.get_json()
        result = tcping_host(data['host'], int(data['port']))
        return jsonify(result)