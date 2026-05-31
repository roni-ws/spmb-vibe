/**
 * dashboard.js
 * Fallback / global dashboard utilities.
 * ApexCharts & DataTables initialization is handled inline in each view's <script> block.
 */

document.addEventListener('DOMContentLoaded', () => {
    // Animate stat card numbers on load
    document.querySelectorAll('[id^="stat-"]').forEach(el => {
        const target = parseInt(el.textContent.replace(/,/g, ''), 10);
        if (isNaN(target) || target === 0) return;
        let current = 0;
        const step  = Math.max(1, Math.ceil(target / 40));
        const timer = setInterval(() => {
            current += step;
            if (current >= target) { current = target; clearInterval(timer); }
            el.textContent = current.toLocaleString('id-ID');
        }, 30);
    });
});
