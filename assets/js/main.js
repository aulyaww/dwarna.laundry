/* =====================================================================
   dWarna Laundry V7 — JAVASCRIPT TAMBAHAN SESUAI FIGMA

   Tidak mengganti markup atau susunan desain high-fidelity.
   Tidak menambah section, form, tombol, maupun tabel baru.
   PHP/MySQL tetap menangani login dan penyimpanan data.

   01. Navigasi HP dan sidebar Admin
   02. Tampilkan/sembunyikan kata sandi
   03. Salin pesan WhatsApp manual
   04. Pencarian tabel Admin
   05. Scroll reveal yang halus TANPA menyembunyikan konten permanen
   06. FAQ buka-tutup
   07. Slideshow di dalam foto hero yang SUDAH ADA (tanpa kontrol baru)
   08. Penekanan progres status yang sudah tersimpan di MySQL
   09. Animasi angka dashboard (nilai akhirnya tetap data PHP)
   ===================================================================== */
'use strict';

document.addEventListener('DOMContentLoaded', () => {
  const reducedMotion = window.matchMedia?.('(prefers-reduced-motion: reduce)').matches ?? false;
  const isPublic = document.body.classList.contains('public-body');

  // 01. Menu navigasi pelanggan HP.
  const toggle = document.querySelector('[data-menu-toggle]');
  const nav = document.getElementById('public-navigation');
  if (toggle && nav) {
    const closeMenu = () => {
      nav.classList.remove('show');
      toggle.setAttribute('aria-expanded', 'false');
    };
    toggle.addEventListener('click', () => {
      const expanded = nav.classList.toggle('show');
      toggle.setAttribute('aria-expanded', String(expanded));
    });
    nav.querySelectorAll('a').forEach(link => link.addEventListener('click', closeMenu));
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeMenu(); });
  }

  // Sidebar Admin layar HP.
  const sideButton = document.querySelector('[data-sidebar-toggle]');
  const shade = document.querySelector('[data-sidebar-shade]');
  const sidebar = document.getElementById('admin-sidebar');
  if (sideButton && sidebar) {
    const setSidebar = open => {
      document.body.classList.toggle('sidebar-open', open);
      sideButton.setAttribute('aria-expanded', String(open));
    };
    sideButton.addEventListener('click', () => setSidebar(!document.body.classList.contains('sidebar-open')));
    shade?.addEventListener('click', () => setSidebar(false));
    document.addEventListener('keydown', e => { if (e.key === 'Escape') setSidebar(false); });
  }

  // 02. Tampilkan/sembunyikan password (tidak mengubah nilai/password).
  document.querySelectorAll('[data-toggle-password]').forEach(button => {
    button.addEventListener('click', () => {
      const input = document.getElementById(button.dataset.togglePassword);
      if (!input) return;
      input.type = input.type === 'password' ? 'text' : 'password';
      button.textContent = input.type === 'password' ? 'Lihat' : 'Tutup';
    });
  });

  // 03. Salin pesan WhatsApp. TIDAK mengirim pesan otomatis.
  document.querySelectorAll('[data-copy-target]').forEach(button => {
    button.addEventListener('click', async () => {
      const field = document.getElementById(button.dataset.copyTarget);
      if (!field) return;
      try {
        await navigator.clipboard.writeText(field.value || field.textContent || '');
        button.textContent = '✓ Tersalin';
      } catch (error) {
        if (typeof field.select === 'function') field.select();
        button.textContent = 'Pilih & salin manual';
      }
    });
  });

  // 04. Pencarian/filter daftar pesanan hanya menyaring baris UI.
  const orders = document.getElementById('demo-orders');
  if (orders) {
    const search = document.querySelector('[data-filter-search]');
    const service = document.querySelector('[data-filter-service]');
    const status = document.querySelector('[data-filter-status]');
    const filterOrders = () => {
      const query = (search?.value || '').trim().toLocaleLowerCase('id-ID');
      const selectedService = service?.value || '';
      const selectedStatus = status?.value || '';
      orders.querySelectorAll('tbody tr').forEach(row => {
        const matchesText = row.textContent.toLocaleLowerCase('id-ID').includes(query);
        const matchesService = !selectedService || row.dataset.service === selectedService;
        const matchesStatus = !selectedStatus || row.dataset.status === selectedStatus;
        row.hidden = !(matchesText && matchesService && matchesStatus);
      });
    };
    [search, service, status].forEach(field => {
      field?.addEventListener('input', filterOrders);
      field?.addEventListener('change', filterOrders);
    });
  }

  document.querySelectorAll('[data-simple-filter]').forEach(input => {
    input.addEventListener('input', () => {
      const table = document.querySelector('.simple-filter-table');
      if (!table) return;
      const query = input.value.toLocaleLowerCase('id-ID').trim();
      table.querySelectorAll('tbody tr').forEach(row => {
        row.hidden = !row.textContent.toLocaleLowerCase('id-ID').includes(query);
      });
    });
  });

  // 05. Animasi pada elemen yang memang ada di Figma, bukan menambah elemen.
  if (isPublic && !reducedMotion && 'IntersectionObserver' in window &&
      typeof Element.prototype.animate === 'function') {
    const targets = document.querySelectorAll(
      '.benefit, .service-card, .home-process > div, .cta-inner > div, ' +
      '.cta-inner > a, .order-step, .faq-card, .contact-item, ' +
      '.tracking-detail, .tracking-stage'
    );
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        observer.unobserve(entry.target);
        entry.target.animate(
          [{ opacity: 0.4, transform: 'translateY(14px)' },
           { opacity: 1, transform: 'translateY(0)' }],
          { duration: 500, easing: 'cubic-bezier(.22,1,.36,1)', fill: 'none' }
        );
      });
    }, { threshold: 0.07, rootMargin: '0px 0px -10px 0px' });
    targets.forEach(el => observer.observe(el));
  }

  // 06. FAQ: membuka satu jawaban menutup jawaban sebelumnya.
  document.querySelectorAll('.faq-card').forEach(group => {
    group.querySelectorAll('details').forEach(detail => {
      detail.addEventListener('toggle', () => {
        if (!detail.open) return;
        group.querySelectorAll('details').forEach(other => {
          if (other !== detail) other.open = false;
        });
      });
    });
  });

  // 07. Slideshow pada LATAR HERO ASLI tanpa mengubah komposisi / tombol Figma.
  // Ganti JPG asli mitra di: assets/images/mitra/{hero,reguler,express}.jpg
  const heroImage = document.querySelector('.home-hero .hero-img');
  if (heroImage && !reducedMotion) {
    const script = document.querySelector('script[src*="assets/js/main.js"]');
    const base = script?.src ? new URL('../images/mitra/', script.src) :
      new URL('assets/images/mitra/', document.baseURI);
    const names = ['hero.jpg'];
    const checkImage = name => new Promise(resolve => {
      const url = new URL(name, base).href;
      const image = new Image();
      image.onload = () => resolve(url);
      image.onerror = () => resolve(null);
      image.src = url;
    });

    Promise.all(names.map(checkImage)).then(results => {
      const images = results.filter(Boolean);
      if (images.length < 2) return; // foto kurang: gunakan gambar CSS semula
      const createLayer = () => {
        const layer = document.createElement('span');
        layer.setAttribute('aria-hidden', 'true');
        Object.assign(layer.style, {
          position: 'absolute', inset: '0', backgroundSize: 'cover',
          backgroundPosition: 'center right', backgroundRepeat: 'no-repeat',
          pointerEvents: 'none', transition: 'opacity 800ms ease', opacity: '0'
        });
        return layer;
      };
      const layers = [createLayer(), createLayer()];
      heroImage.style.backgroundImage = 'none';
      layers[0].style.backgroundImage = `url("${images[0]}")`;
      layers[0].style.opacity = '1';
      heroImage.append(...layers);
      let active = 0, index = 0, busy = false;
      const nextPhoto = () => {
        if (busy || document.hidden) return;
        busy = true;
        index = (index + 1) % images.length;
        const outgoing = layers[active];
        const incoming = layers[1 - active];
        incoming.style.backgroundImage = `url("${images[index]}")`;
        incoming.style.opacity = '0';
        incoming.style.zIndex = '1';
        outgoing.style.zIndex = '0';
        requestAnimationFrame(() => requestAnimationFrame(() => { incoming.style.opacity = '1'; }));
        window.setTimeout(() => {
          outgoing.style.opacity = '0';
          active = 1 - active;
          busy = false;
        }, 850);
      };
      window.setInterval(nextPhoto, 6500);
    });
  }

  // 08. Efek lembut pada tahapan tracking HASIL DATABASE, tanpa mengubah status.
  if (isPublic && !reducedMotion && typeof Element.prototype.animate === 'function') {
    document.querySelectorAll('.tracking-stage .phase.done > span').forEach((step, i) => {
      step.animate([
        { transform: 'scale(.96)' },
        { transform: 'scale(1.05)' },
        { transform: 'scale(1)' }
      ], { duration: 500, delay: i * 110, easing: 'ease-out' });
    });
  }

  // 09. Animasi angka ringkasan Admin. Nilai akhir berasal dari PHP/MySQL.
  if (!reducedMotion && 'IntersectionObserver' in window) {
    const stats = document.querySelectorAll('.admin-body .stat-value');
    const observer = new IntersectionObserver(entries => {
      entries.forEach(({ target, isIntersecting }) => {
        if (!isIntersecting) return;
        observer.unobserve(target);
        const original = target.textContent.trim();
        if (!/^\d{1,6}$/.test(original)) return;
        const finalValue = Number(original);
        if (!finalValue) return;
        const started = performance.now();
        const render = now => {
          const progress = Math.min(1, (now - started) / 700);
          const ease = 1 - Math.pow(1 - progress, 3);
          target.textContent = String(Math.round(finalValue * ease));
          if (progress < 1) requestAnimationFrame(render);
          else target.textContent = original;
        };
        requestAnimationFrame(render);
      });
    }, { threshold: 0.1 });
    stats.forEach(el => observer.observe(el));
  }
});
