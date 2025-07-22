import gradio as gr
import requests
import json

def ping_test(host):
    response = requests.post('http://localhost:5000/api/ping', 
                           json={'host': host})
    result = response.json()
    return result['output'] if result['success'] else f"错误: {result['error']}"

def tcping_test(host, port):
    response = requests.post('http://localhost:5000/api/tcping', 
                           json={'host': host, 'port': port})
    result = response.json()
    return result['output'] if result['success'] else f"错误: {result['error']}"

def http_test(url):
    response = requests.post('http://localhost:5000/api/http', 
                           json={'url': url})
    result = response.json()
    return result['output'] if result['success'] else f"错误: {result['error']}"

def ddos_test(url, count):
    response = requests.post('http://localhost:5000/api/ddos', 
                           json={'url': url, 'count': count})
    result = response.json()
    return result['output'] if result['success'] else f"错误: {result['error']}"

def portscan_test(host, ports):
    response = requests.post('http://localhost:5000/api/portscan', 
                           json={'host': host, 'ports': ports})
    result = response.json()
    return result['output'] if result['success'] else f"错误: {result['error']}"

def dns_test(domain, query_type):
    response = requests.post('http://localhost:5000/api/dns', 
                           json={'domain': domain, 'query_type': query_type})
    result = response.json()
    return result['output'] if result['success'] else f"错误: {result['error']}"

def create_gradio_app():
    with gr.Blocks(title="网络安全测试平台", theme="default") as demo:
        gr.Markdown("## 网络安全测试平台")
        
        with gr.Tab("Ping测试"):
            with gr.Row():
                host_input = gr.Textbox(label="目标主机")
                ping_btn = gr.Button("执行测试")
            ping_output = gr.Textbox(label="测试结果")
            ping_btn.click(fn=ping_test, inputs=host_input, outputs=ping_output)
        
        with gr.Tab("TCPing测试"):
            with gr.Row():
                tcp_host_input = gr.Textbox(label="目标主机")
                tcp_port_input = gr.Number(label="端口号", precision=0)
                tcp_btn = gr.Button("执行测试")
            tcp_output = gr.Textbox(label="测试结果")
            tcp_btn.click(fn=tcping_test, inputs=[tcp_host_input, tcp_port_input], outputs=tcp_output)
        
        with gr.Tab("HTTP测试"):
            with gr.Row():
                http_input = gr.Textbox(label="URL地址")
                http_btn = gr.Button("执行测试")
            http_output = gr.Textbox(label="测试结果")
            http_btn.click(fn=http_test, inputs=http_input, outputs=http_output)
        
        with gr.Tab("DDoS测试"):
            with gr.Row():
                ddos_input = gr.Textbox(label="目标URL")
                ddos_count_input = gr.Number(label="请求次数", value=100, precision=0)
                ddos_btn = gr.Button("执行测试")
            ddos_output = gr.Textbox(label="测试结果")
            ddos_btn.click(fn=ddos_test, inputs=[ddos_input, ddos_count_input], outputs=ddos_output)
        
        with gr.Tab("端口扫描"):
            with gr.Row():
                port_host_input = gr.Textbox(label="目标主机")
                port_input = gr.Textbox(label="端口范围", value="20-100")
                port_btn = gr.Button("执行测试")
            port_output = gr.Textbox(label="测试结果")
            port_btn.click(fn=portscan_test, inputs=[port_host_input, port_input], outputs=port_output)
        
        with gr.Tab("DNS查询"):
            with gr.Row():
                dns_input = gr.Textbox(label="域名")
                dns_type = gr.Dropdown(choices=["A", "AAAA", "CNAME", "MX", "NS", "TXT"], label="查询类型")
                dns_btn = gr.Button("执行查询")
            dns_output = gr.Textbox(label="查询结果")
            dns_btn.click(fn=dns_test, inputs=[dns_input, dns_type], outputs=dns_output)
        
        gr.Markdown("注意：本工具仅限在合法授权的测试环境中使用，请遵守网络安全相关法律法规")
    
    return demo