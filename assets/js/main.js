// assets/js/main.js

// ── Navbar scroll effect ──────────────────────────────
window.addEventListener('scroll', () => {
    const nav = document.getElementById('mainNavbar');
    if (nav) {
        nav.style.boxShadow = window.scrollY > 10
            ? '0 4px 30px rgba(0,0,0,0.4)'
            : '0 2px 20px rgba(0,0,0,0.3)';
    }
});

// ── Password strength meter ───────────────────────────
const pwInput = document.getElementById('password');
const pwStrength = document.getElementById('passwordStrength');
const pwStrengthText = document.getElementById('passwordStrengthText');

if (pwInput && pwStrength) {
    pwInput.addEventListener('input', () => {
        const val = pwInput.value;
        let score = 0;
        if (val.length >= 8)  score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const colors = ['#ef4444', '#f59e0b', '#10b981', '#4f46e5'];
        const labels = ['Weak', 'Fair', 'Good', 'Strong'];
        const widths = ['25%', '50%', '75%', '100%'];

        if (val.length === 0) {
            pwStrength.style.width = '0';
            pwStrengthText.textContent = '';
        } else {
            const idx = Math.min(score - 1, 3);
            pwStrength.style.width    = widths[idx];
            pwStrength.style.background = colors[idx];
            pwStrengthText.textContent  = labels[idx];
            pwStrengthText.style.color  = colors[idx];
        }
    });
}

// ── Password visibility toggle ────────────────────────
document.querySelectorAll('.toggle-password').forEach(btn => {
    btn.addEventListener('click', () => {
        const target = document.getElementById(btn.dataset.target);
        if (!target) return;
        const isText = target.type === 'text';
        target.type = isText ? 'password' : 'text';
        btn.querySelector('i').className = isText
            ? 'bi bi-eye'
            : 'bi bi-eye-slash';
    });
});

// ── Auto-dismiss flash alerts ─────────────────────────
document.querySelectorAll('.alert-banner').forEach(el => {
    setTimeout(() => {
        el.style.transition = 'opacity 0.5s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    }, 4000);
});

// ── Confirm delete ────────────────────────────────────
document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', (e) => {
        if (!confirm(el.dataset.confirm)) e.preventDefault();
    });
});

// ── Table search filter ───────────────────────────────
const tableSearch = document.getElementById('tableSearch');
if (tableSearch) {
    tableSearch.addEventListener('input', () => {
        const q = tableSearch.value.toLowerCase();
        document.querySelectorAll('#usersTable tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
        });
    });
}
