let index = 1;
const total = 5;
function showSlide(i) {
    document.getElementById("s" + i).checked = true;
    index = i;
}
function nextSlide() {
    index++;
    if (index > total) index = 1;
    showSlide(index);
}
function prevSlide() {
    index--;
    if (index < 1) index = total;
    showSlide(index);
}
setInterval(nextSlide, 3000);