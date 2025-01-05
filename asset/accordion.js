document.addEventListener('DOMContentLoaded', function() {
    const toggleButtons = document.querySelectorAll('.toggle-button');
    const panels = document.querySelectorAll('.panel');

    // Tüm panelleri açık durumda tut
    panels.forEach((panel, index) => {
        panel.style.display = 'block';
        toggleButtons[index].textContent = '▲'; // Buton ikonunu değiştir
    });

    toggleButtons.forEach((button, index) => {
        button.addEventListener('click', function() {
            const panel = panels[index];
            if (panel.style.display === 'block') {
                panel.style.display = 'none';
                this.textContent = '▼'; // Buton ikonunu değiştir
            } else {
                panel.style.display = 'block';
                this.textContent = '▲'; // Buton ikonunu değiştir
            }
        });
    });
});
