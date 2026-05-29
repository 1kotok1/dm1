const images = ['images/1.jpg', 'images/2.jpg', 'images/3.jpg', 'images/4.jpg'];
let current = 0;
const img = document.getElementById('slide');

function next() {
    current = (current + 1) % images.length;
    img.style.opacity = '0';
    setTimeout(() => {
        img.src = images[current];
        img.style.opacity = '1';
    }, 300);
}

function prev() {
    current = (current - 1 + images.length) % images.length;
    img.style.opacity = '0';
    setTimeout(() => {
        img.src = images[current];
        img.style.opacity = '1';
    }, 300);
}

if (img) {
    document.getElementById('next').onclick = next;
    document.getElementById('prev').onclick = prev;
    setInterval(next, 3000);
}