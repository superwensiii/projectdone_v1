// Simple animation when the button is clicked
document.querySelector('.btn').addEventListener('click', function(e) {
    e.preventDefault();
    this.style.transform = 'scale(1.1)';
    setTimeout(() => {
        this.style.transform = 'scale(1)';
    }, 200);
});

// Scroll to form smoothly when clicking a link (optional)
document.querySelectorAll('a').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        if (this.hash !== "") {
            e.preventDefault();
            const hash = this.hash;
            document.querySelector(hash).scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});
