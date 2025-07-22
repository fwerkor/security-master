import os
import importlib
from flask import Flask

def load_modules(app: Flask):
    """
    自动加载modules目录下的所有模块
    
    每个模块需要实现register_api函数来注册API路由
    """
    module_dir = os.path.dirname(os.path.abspath(__file__))
    
    for filename in os.listdir(module_dir):
        if filename.endswith('_module.py') and not filename.startswith('__'):
            module_name = f'modules.{filename[:-3]}'
            try:
                module = importlib.import_module(module_name)
                if hasattr(module, 'register_api'):
                    module.register_api(app)
                    print(f'成功加载模块: {module_name}')
            except Exception as e:
                print(f'加载模块 {module_name} 失败: {str(e)}')
    return app