'use strict';

const navToggle = document.querySelector('#nav-toggle');
const mainNav = document.querySelector('#main-nav');

navToggle?.addEventListener('click', () => {
    const open = mainNav.classList.toggle('open');
    navToggle.classList.toggle('open', open);
    navToggle.setAttribute('aria-expanded', String(open));
});

mainNav?.querySelectorAll('a').forEach((link) => {
    link.addEventListener('click', () => {
        mainNav.classList.remove('open');
        navToggle.classList.remove('open');
        navToggle.setAttribute('aria-expanded', 'false');
    });
});
