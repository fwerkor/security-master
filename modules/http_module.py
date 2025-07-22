import requests
import time
from flask import jsonify

def http_test(url):
    """
    执行HTTP测试
    """
    try:
        start_time = time.time()
        response = requests.get(url, timeout=10)
        end_time = time.time()
        return {'success': True, 'output': f"HTTP响应状态码: {response.status_code}, 响应时间: {(end_time - start_time)*1000:.2f}ms"}
    except Exception as e:
        return {'success': False, 'error': f"HTTP测试失败: {str(e)}"}

def register_api(app):
    """
    注册HTTP模块的API路由
    """
    @app.route('/api/http', methods=['POST'])
    def api_http():
        data = request.get_json()
        result = http_test(data['url'])
        return jsonify(result)