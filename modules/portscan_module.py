import nmap
from flask import jsonify

def portscan(host, ports):
    """
    执行端口扫描测试
    """
    try:
        nm = nmap.PortScanner()
        result = nm.scan(host, ports)
        scan_data = result['scan'][host] if host in result['scan'] else {}
        return {'success': True, 'output': scan_data}
    except Exception as e:
        return {'success': False, 'error': f"端口扫描失败: {str(e)}"}

def register_api(app):
    """
    注册端口扫描模块的API路由
    """
    @app.route('/api/portscan', methods=['POST'])
    def api_portscan():
        data = request.get_json()
        result = portscan(data['host'], data['ports'])
        return jsonify(result)