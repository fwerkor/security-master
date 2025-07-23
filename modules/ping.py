"""
Ping网络诊断工具
"""
import subprocess
import re
import gradio as gr

name = "Ping"
description = "网络连通性检测工具，支持IPv4/IPv6和自定义包大小"

def get_ui():
    """创建Ping工具的UI界面"""
    with gr.Row():
        host = gr.Textbox(label="目标地址", placeholder="输入IP地址或域名")
        size = gr.Number(label="包大小 (bytes)", value=32, minimum=1, maximum=65500)
        ipv6 = gr.Checkbox(label="使用IPv6", value=False)
    return gr.Group([host, size, ipv6])


def run(host, size, ipv6):
    """执行Ping操作"""
    if not host:
        return "错误：必须输入目标地址"
    
    try:
        # 构建ping命令参数
        cmd = ["ping"]
        
        if ipv6:
            cmd.append("-6")
            # 检查是否是IPv6地址格式
            if not re.match(r'\S+:\S+', host):
                return "警告：IPv6地址格式可能不正确"
        else:
            cmd.append("-4")
            # 检查是否是IPv4地址格式
            if not re.match(r'\d+\.\d+\.\d+\.\d+', host):
                try:
                    # 尝试解析域名
                    import socket
                    socket.gethostbyname(host)
                except Exception as e:
                    return f"警告：无法解析域名 {host}"
        
        # 设置包大小
        cmd.extend(["-c", str(size)])
        
        # 执行ping命令
        result = subprocess.run(
            cmd + [host],
            stdout=subprocess.PIPE,
            stderr=subprocess.PIPE,
            text=True,
            timeout=10
        )
        
        # 返回结果
        if result.returncode == 0:
            return result.stdout
        else:
            return f"错误：{result.stderr}"
            
    except Exception as e:
        return f"执行错误：{str(e)}"