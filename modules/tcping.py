import socket
import time

def title():
    return "TCPing工具"

def get_form():
    return '''
    <div class="form-group">
        <label for="host">目标主机:</label>
        <input type="text" class="form-control" id="host" placeholder="输入IP地址或域名">
    </div>
    <div class="form-group">
        <label for="port">端口号:</label>
        <input type="number" class="form-control" id="port" value="80" min="1" max="65535">
    </div>
    <div class="form-group">
        <label for="count">连接次数:</label>
        <input type="number" class="form-control" id="count" value="4" min="1">
    </div>
    <div class="form-group">
        <label for="timeout">超时时间 (秒):</label>
        <input type="number" class="form-control" id="timeout" value="3" min="1" step="0.1">
    </div>
    '''

def run(args):
    host = args.get('host')
    port = int(args.get('port', 80))
    count = int(args.get('count', 4))
    timeout = float(args.get('timeout', 3))
    
    if not host:
        raise ValueError("必须提供主机地址")
    
    results = []
    for i in range(count):
        start_time = time.time()
        try:
            sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
            sock.settimeout(timeout)
            result = sock.connect_ex((host, port))
            end_time = time.time()
            sock.close()
            
            elapsed = (end_time - start_time) * 1000  # 转换为毫秒
            
            if result == 0:
                results.append(f"连接 {i+1}: 成功 - 耗时 {elapsed:.2f} ms")
            else:
                results.append(f"连接 {i+1}: 失败 - 错误码 {result}")
        except Exception as e:
            end_time = time.time()
            elapsed = (end_time - start_time) * 1000
            results.append(f"连接 {i+1}: 错误 - {str(e)} - 耗时 {elapsed:.2f} ms")
    
    return "\n".join(results)