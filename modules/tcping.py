"""
TCPing网络诊断工具
"""
import socket
import time
import gradio as gr

name = "TCPing"
description = "TCP端口连通性检测工具，可用于检测服务是否在线"

def get_ui():
    """创建TCPing工具的UI界面"""
    with gr.Row():
        host = gr.Textbox(label="目标地址", placeholder="输入IP地址或域名")
        port = gr.Number(label="端口号", value=80, minimum=1, maximum=65535)
        timeout = gr.Number(label="超时时间 (秒)", value=3, minimum=1, maximum=30)
    return gr.Group([host, port, timeout])


def run(host, port, timeout):
    """执行TCPing操作"""
    if not host:
        return "错误：必须输入目标地址"
    
    try:
        # 创建socket连接
        start_time = time.time()
        sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        sock.settimeout(timeout)
        
        # 连接目标
        sock.connect((host, int(port)))
        end_time = time.time()
        
        # 关闭连接
        sock.close()
        
        # 计算延迟
        latency = (end_time - start_time) * 1000  # 转换为毫秒
        
        return f"成功连接到 {host}:{port}\n连接时间：{latency:.2f} ms"
        
    except socket.timeout:
        return f"错误：连接超时（超过{timeout}秒）"
    except socket.gaierror:
        return "错误：无法解析域名"
    except ConnectionRefusedError:
        return f"错误：连接被拒绝（端口 {port} 可能未开放）"
    except Exception as e:
        return f"执行错误：{str(e)}"