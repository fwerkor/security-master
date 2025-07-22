import gradio as gr
import os
from modules.module_loader import load_modules

# 修改模块导入路径
from modules.module_registry import get_loaded_modules  # 使用模块注册表

def create_gradio_app():
    """创建并配置Gradio Web界面"""
    # 创建一个新的Gradio应用
    with gr.Blocks() as demo:
        gr.Markdown("# 安全工具面板")
        
        # 模块加载状态显示
        module_list = gr.Textbox(
            label="已加载模块",
            value=lambda: "\n".join(get_loaded_modules().keys()) or "无模块加载",
            lines=10
        )
        
        # 自动刷新按钮
        refresh_btn = gr.Button("刷新模块状态")
        refresh_btn.click(
            fn=lambda: "\n".join(get_loaded_modules().keys()) or "无模块加载",
            outputs=module_list
        )
    
    # 直接启动Gradio应用
    demo.launch()
    return demo

# 在应用启动时加载模块
if __name__ == '__main__':
    # 创建并运行Gradio应用
    app = create_gradio_app()
    app.launch()