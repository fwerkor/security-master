import requests
import time
from flask import jsonify

def ddos_test(url, count=100):
    """
    简单的DDoS测试（仅限测试环境使用）
    注意：本功能仅用于合法测试，请遵守网络安全法
    """
    results = []
    for i in range(count):
        try:
            response = requests.get(url, timeout=5)
            results.append(f"请求 {i+1}: 状态码 {response.status_code}")
        except Exception as e:
            results.append(f"请求 {i+1} 失败: {str(e)}")
        time.sleep(0.1)
    return {'success': True, 'output': "\n".join(results)}

def register_api(app):
    """
    注册DDoS模块的API路由
    """
    @app.route('/api/ddos', methods=['POST'])
    def api_ddos():
        data = request.get_json()
        result = ddos_test(data['url'], int(data['count']))
        return jsonify(result)