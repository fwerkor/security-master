from gradio_app import create_gradio_app

if __name__ == '__main__':
    app = create_gradio_app()
    app.launch(server_name="0.0.0.0", server_port=7860)