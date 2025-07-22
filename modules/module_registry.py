"""
模块注册表 - 用于在不同组件间共享已加载模块信息
"""

# 使用模块加载器的_loaded_modules以保证数据一致性
from .module_loader import _loaded_modules

def register_module(module_name, module):
    """注册模块"""
    _loaded_modules[module_name] = module

def get_loaded_modules():
    """获取已加载模块字典"""
    return _loaded_modules

def sync_with_loader():
    """与模块加载器同步模块信息"""
    from .module_loader import get_loaded_modules as loader_getter
    _loaded_modules.update(loader_getter())
