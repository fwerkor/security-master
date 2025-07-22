from gradio_app import create_gradio_app

if __name__ == '__main__':
    # 创建并运行Gradio应用
    app = create_gradio_app()
    app.launch()