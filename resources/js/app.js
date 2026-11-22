const fmt = (n) => n.toFixed(1).replace('.', ',');

// muncul saat di-scroll
const io = new IntersectionObserver((entries) => {
    entries.forEach((e) => {
        if (!e.isIntersecting) return;
        e.target.classList.add('in');
        io.unobserve(e.target);
    });
}, { threshold: 0.12 });
document.querySelectorAll('[data-reveal]').forEach((el) => io.observe(el));

// angka naik pelan-pelan
document.querySelectorAll('[data-count]').forEach((el) => {
    const to = parseFloat(el.dataset.count);
    const t0 = performance.now();
    const step = (t) => {
        const k = Math.min((t - t0) / 1200, 1);
        el.textContent = fmt(to * (1 - Math.pow(1 - k, 3))) + '%';
        if (k < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
});

// bar persentase terisi halus
setTimeout(() => {
    document.querySelectorAll('.fill[data-w]').forEach((el) => { el.style.width = el.dataset.w + '%'; });
}, 350);

// formulir diagnosis: hitung gejala terpilih dan tandai kartu aktif
const form = document.getElementById('form-diagnosa');
if (form) {
    const cnt = document.getElementById('cnt');
    const sync = () => {
        let n = 0;
        form.querySelectorAll('[data-card]').forEach((card) => {
            const on = card.querySelector('input:checked')?.value !== '0';
            card.classList.toggle('is-active', on);
            if (on) n++;
        });
        cnt.textContent = n + ' gejala dipilih';
    };
    form.addEventListener('change', sync);
    form.addEventListener('reset', () => setTimeout(sync));
}
