document.addEventListener('DOMContentLoaded', function() {
    fetch('modules/')
        .then(response => response.text())
        .then(data => {
            const parser = new DOMParser();
            const html = parser.parseFromString(data, 'text/html');
            const links = Array.from(html.querySelectorAll('a')).map(a => a.href.split('/').pop());
            const toolsList = document.getElementById('tools-list');

            links.forEach(file => {
                if (file.endsWith('.php')) {
                    const toolName = file.replace('.php', '');
                    const toolCard = document.createElement('div');
                    toolCard.className = 'tool-card';
                    toolCard.innerHTML = `<h2>${toolName}</h2><p>点击使用${toolName}工具</p>`;
                    toolCard.addEventListener('click', () => {
                        window.location.href = `modules/${file}`;
                    });
                    toolsList.appendChild(toolCard);
                }
            });
        })
        .catch(error => console.error('Error loading tools:', error));
});