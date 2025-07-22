import dns.resolver
import json
from flask import jsonify

def dns_query(domain, query_type):
    """
    执行DNS查询
    """
    try:
        resolver = dns.resolver.Resolver()
        answers = resolver.resolve(domain, query_type)
        results = [str(rdata) for rdata in answers]
        return {'success': True, 'output': results}
    except Exception as e:
        return {'success': False, 'error': f"DNS查询失败: {str(e)}"}

def register_api(app):
    """
    注册DNS模块的API路由
    """
    @app.route('/api/dns', methods=['POST'])
    def api_dns():
        data = request.get_json()
        result = dns_query(data['domain'], data['query_type'])
        return jsonify(result)