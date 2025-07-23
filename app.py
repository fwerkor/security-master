import os
import importlib.util
from flask import Flask, render_template, request, jsonify

app = Flask(__name__, static_folder='static', template_folder='templates')

# 动态加载模块
def load_modules():
    modules = {}
    modules_dir = os.path.join(os.path.dirname(__file__), 'modules')
    
    if not os.path.exists(modules_dir):
        os.makedirs(modules_dir)
        return modules
    
    for filename in os.listdir(modules_dir):
        if filename.endswith('.py') and filename != '__init__.py':
            module_name = filename[:-3]  # 移除 .py 扩展名
            module_path = os.path.join(modules_dir, filename)
            
            spec = importlib.util.spec_from_file_location(module_name, module_path)
            module = importlib.util.module_from_spec(spec)
            spec.loader.exec_module(module)
            
            # 将模块添加到字典中
            modules[module_name] = module
    
    return modules

# 加载所有模块
MODULES = load_modules()

@app.route('/')
def index():
    return render_template('index.html', modules=MODULES)

@app.route('/module/<module_name>')
def module_page(module_name):
    if module_name not in MODULES:
        return "Module not found", 404
    
    module = MODULES[module_name]
    return render_template('module.html', module_name=module_name, 
                          module_form=module.get_form())

@app.route('/run/<module_name>', methods=['POST'])
def run_module(module_name):
    if module_name not in MODULES:
        return jsonify({'error': 'Module not found'}), 404
    
    module = MODULES[module_name]
    try:
        result = module.run(request.json)
        return jsonify({'result': result})
    except Exception as e:
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)