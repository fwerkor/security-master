"""
API文档模块

提供API接口说明文档
"""
import json
from flask import jsonify

def generate_api_docs():
    """
    生成API接口文档
    """
    docs = {
        "endpoints": [
            {
                "name": "Ping测试",
                "path": "/api/ping",
                "method": "POST",
                "description": "执行网络连通性测试",
                "request": {
                    "host": "目标主机地址"
                },
                "response": {
                    "success": "是否成功",
                    "output": "测试结果输出",
                    "error": "错误信息（当success为False时存在）"
                }
            },
            {
                "name": "TCPing测试",
                "path": "/api/tcping",
                "method": "POST",
                "description": "执行TCP端口可达性测试",
                "request": {
                    "host": "目标主机地址",
                    "port": "目标端口号"
                },
                "response": {
                    "success": "是否成功",
                    "output": "测试结果输出",
                    "error": "错误信息（当success为False时存在）"
                }
            },
            {
                "name": "HTTP测试",
                "path": "/api/http",
                "method": "POST",
                "description": "执行Web服务响应测试",
                "request": {
                    "url": "目标URL地址"
                },
                "response": {
                    "success": "是否成功",
                    "output": "测试结果输出",
                    "error": "错误信息（当success为False时存在）"
                }
            },
            {
                "name": "DDoS测试",
                "path": "/api/ddos",
                "method": "POST",
                "description": "模拟压力测试（仅限测试环境使用）",
                "request": {
                    "url": "目标URL地址",
                    "count": "请求次数"
                },
                "response": {
                    "success": "是否成功",
                    "output": "测试结果输出",
                    "error": "错误信息（当success为False时存在）"
                }
            },
            {
                "name": "端口扫描",
                "path": "/api/portscan",
                "method": "POST",
                "description": "执行端口扫描测试",
                "request": {
                    "host": "目标主机地址",
                    "ports": "端口范围（如 20-100）"
                },
                "response": {
                    "success": "是否成功",
                    "output": "测试结果输出",
                    "error": "错误信息（当success为False时存在）"
                }
            },
            {
                "name": "DNS查询",
                "path": "/api/dns",
                "method": "POST",
                "description": "执行DNS解析查询",
                "request": {
                    "domain": "域名",
                    "query_type": "查询类型（A、AAAA、CNAME、MX、NS、TXT）"
                },
                "response": {
                    "success": "是否成功",
                    "output": "测试结果输出",
                    "error": "错误信息（当success为False时存在）"
                }
            }
        ]
    }
    return docs

def register_api(app):
    """
    注册API文档路由
    """
    @app.route('/api/docs', methods=['GET'])
    def api_docs():
        return jsonify(generate_api_docs())