document.addEventListener('DOMContentLoaded', function() {
    const header = document.querySelector('header');
    
    window.addEventListener('scroll', () => {
        if (window.scrollY > 0) {
            header.classList.add('bg-gray-900');
            header.classList.remove('bg-opacity-90');
        } else {
            header.classList.remove('bg-gray-900');
            header.classList.add('bg-opacity-90');
        }
    });
});