import gradio as gr
import importlib
import os
import sys

# 获取模块目录的绝对路径
modules_dir = os.path.join(os.path.dirname(__file__), 'modules')
# 添加模块路径
sys.path.append(modules_dir)

# 加载所有工具模块
tools = {}
tool_files = [f[:-3] for f in os.listdir(modules_dir) if f.endswith('.py') and not f.startswith('__')]

for tool_file in tool_files:
    try:
        module = importlib.import_module(tool_file)
        if hasattr(module, 'get_ui') and hasattr(module, 'run'):
            tools[module.name] = {
                'module': module,
                'ui': module.get_ui(),
                'description': getattr(module, 'description', '')
            }
    except Exception as e:
        print(f"Error loading module {tool_file}: {str(e)}")

# 创建Gradio界面
def create_interface():
    with gr.Blocks(
        theme=gr.themes.Soft(),
        title="网络安全工具箱",
        css=os.path.join(os.path.dirname(__file__), 'static', 'style.css')  # 添加自定义样式
    ) as demo:
        gr.Markdown("# 🛡️ 网络安全工具箱")
        
        with gr.Tabs():
            for tool_name, tool_info in tools.items():
                with gr.TabItem(f"{tool_name}"):
                    gr.Markdown(f"## {tool_info['description']}")
                    output = gr.Textbox(label="执行结果", lines=10)
                    
                    def make_func(tool_run=tool_info['run']):
                        def wrapper(*args):
                            return tool_run(*args)
                        return wrapper
                    
                    tool_info['ui'].submit(fn=make_func(), inputs=tool_info['ui'].components, outputs=output)
                    
        gr.Markdown("---\n© 2025 网络安全工具箱 | 仅用于合法测试")
    
    return demo

if __name__ == "__main__":
    app = create_interface()
    app.launch(server_name="0.0.0.0", server_port=7860)