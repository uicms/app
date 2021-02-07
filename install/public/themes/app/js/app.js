// External links
document.querySelectorAll('a.external').forEach(function(link) {
    link.setAttribute('target', '_blank');
});

// Menu button
document.querySelector('#menu_button').addEventListener('click', function(e) {
    e.stopPropagation();
    document.querySelector('body').classList.toggle('menu_expanded');
});
document.querySelector('body').addEventListener('click', function(e) {
    this.classList.remove('menu_expanded');
});