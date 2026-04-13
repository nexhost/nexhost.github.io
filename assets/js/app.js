document.addEventListener('DOMContentLoaded', () => {
  const now = new Date();
  const yearNode = document.querySelector('[data-current-year]');

  if (yearNode) {
    yearNode.textContent = String(now.getFullYear());
  }
});
