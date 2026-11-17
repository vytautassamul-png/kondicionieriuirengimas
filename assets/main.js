(() => {
  const menu = document.querySelector('.menu-toggle');
  const nav = document.getElementById('nav-links');
  menu?.addEventListener('click', () => {
    const isOpen = menu.getAttribute('aria-expanded') === 'true';
    menu.setAttribute('aria-expanded', String(!isOpen));
    nav?.classList.toggle('open', !isOpen);
  });
  document.querySelectorAll('#nav-links a').forEach(link => link.addEventListener('click', () => {
    nav?.classList.remove('open');
    menu?.setAttribute('aria-expanded', 'false');
  }));
  document.getElementById('year').textContent = new Date().getFullYear();
  document.querySelectorAll('[data-interest]').forEach(link => link.addEventListener('click', () => {
    const option = document.getElementById('interest');
    if (option) option.value = link.dataset.interest;
  }));
  const params = new URLSearchParams(location.search);
  ['utm_source','utm_medium','utm_campaign','gclid','msclkid','fbclid'].forEach(key => {
    const field = document.getElementById(key);
    if (field) field.value = (params.get(key) || '').slice(0, 150);
  });
  const referrer = document.getElementById('referrer');
  if (referrer && document.referrer) {
    try { referrer.value = new URL(document.referrer).hostname.slice(0, 150); } catch (_) {}
  }
  const landing = document.getElementById('landing_page');
  if (landing) landing.value = location.pathname.slice(0, 120);
  const form = document.getElementById('lead-form');
  form?.addEventListener('submit', async event => {
    if (!window.fetch) return;
    event.preventDefault();
    const submit = form.querySelector('button[type="submit"]');
    const status = document.getElementById('form-status');
    if (!form.reportValidity()) return;
    submit.disabled = true;
    submit.textContent = 'Siunčiama…';
    status.textContent = '';
    try {
      const response = await fetch(form.action, {method:'POST',body:new FormData(form),headers:{'Accept':'application/json'}});
      const data = await response.json();
      if (!response.ok || !data.ok) throw new Error(data.message || 'Nepavyko išsiųsti. Bandykite dar kartą.');
      location.assign('aciu.php');
    } catch (err) {
      status.textContent = (location.protocol === 'file:') ? 'Tai vietinė peržiūra – forma veiks įkėlus į PHP palaikantį serverį ir sukonfigūravus „Telegram“ botą.' : (err.message || 'Nepavyko išsiųsti. Bandykite paskambinti.');
      submit.disabled = false;
      submit.innerHTML = 'Siųsti užklausą <span>↗</span>';
    }
  });
})();