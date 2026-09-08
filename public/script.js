const menuButton = document.querySelector('.menu-toggle');
const navigation = document.querySelector('.site-nav');
const toast = document.querySelector('.toast');

menuButton?.addEventListener('click', () => {
  const open = menuButton.getAttribute('aria-expanded') === 'true';
  menuButton.setAttribute('aria-expanded', String(!open));
  navigation.classList.toggle('open', !open);
});

navigation?.addEventListener('click', (event) => {
  if (event.target.matches('a')) {
    navigation.classList.remove('open');
    menuButton?.setAttribute('aria-expanded', 'false');
  }
});

document.querySelectorAll('.map-button').forEach((button) => {
  button.addEventListener('click', () => {
    toast.textContent = `${button.dataset.location}: replace this button with the official Google Maps URL.`;
    toast.classList.add('show');
    window.clearTimeout(window.armourToastTimer);
    window.armourToastTimer = window.setTimeout(() => toast.classList.remove('show'), 3600);
  });
});

document.querySelector('#year').textContent = new Date().getFullYear();

const revealObserver = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.classList.add('visible');
      revealObserver.unobserve(entry.target);
    }
  });
}, { threshold: 0.12 });

document.querySelectorAll('.reveal').forEach((element) => revealObserver.observe(element));
