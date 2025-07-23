import subprocess
import platform

def title():
    return "Ping工具"

def get_form():
    return '''
    <div class="form-group">
        <label for="host">目标主机:</label>
        <input type="text" class="form-control" id="host" placeholder="输入IP地址或域名">
    </div>
    <div class="form-group">
        <label for="count">Ping次数:</label>
        <input type="number" class="form-control" id="count" value="4" min="1">
    </div>
    <div class="form-group">
        <label for="size">数据包大小 (bytes):</label>
        <input type="number" class="form-control" id="size" value="56" min="0">
    </div>
    <div class="form-group">
        <label for="ip_version">IP版本:</label>
        <select class="form-control" id="ip_version">
            <option value="4">IPv4</option>
            <option value="6">IPv6</option>
        </select>
    </div>
    '''

def run(args):
    host = args.get('host')
    count = args.get('count', 4)
    size = args.get('size', 56)
    ip_version = args.get('ip_version', '4')
    
    if not host:
        raise ValueError("必须提供主机地址")
    
    # 构建ping命令
    cmd = []
    
    if platform.system().lower() == 'windows':
        cmd = ['ping']
        if ip_version == '6':
            cmd.append('-6')
        cmd.extend(['-n', str(count), '-l', str(size), host])
    else:
        cmd = ['ping']
        if ip_version == '6':
            cmd.append('-6')
        cmd.extend(['-c', str(count), '-s', str(size), host])
    
    try:
        result = subprocess.run(cmd, capture_output=True, text=True, timeout=30)
        return result.stdout + result.stderr
    except subprocess.TimeoutExpired:
        return "Ping命令超时"
    except Exception as e:
        return f"执行错误: {str(e)}"