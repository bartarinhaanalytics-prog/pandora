// Mobile nav toggle
const menuToggle = document.getElementById('menuToggle');
const mainNav = document.getElementById('mainNav');

menuToggle?.addEventListener('click', () => {
  mainNav.classList.toggle('open');
});

document.addEventListener('click', (e) => {
  if (!mainNav.contains(e.target) && !menuToggle.contains(e.target)) {
    mainNav.classList.remove('open');
  }
});

// Horizontal carousels (collections + reviews)
document.querySelectorAll('.collections-row, .reviews-row').forEach((row) => {
  const track = row.querySelector('.collections-track') || row;
  const [prevBtn, nextBtn] = row.querySelectorAll('.carousel-btn');
  const step = 220;

  prevBtn?.addEventListener('click', () => {
    track.scrollBy({ left: step, behavior: 'smooth' });
  });
  nextBtn?.addEventListener('click', () => {
    track.scrollBy({ left: -step, behavior: 'smooth' });
  });
});

// Wishlist toggle
document.querySelectorAll('.wish-btn').forEach((btn) => {
  btn.addEventListener('click', () => {
    btn.classList.toggle('active');
    btn.textContent = btn.classList.contains('active') ? '♥' : '♡';
  });
});
