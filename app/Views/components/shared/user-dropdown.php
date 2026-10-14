<div class="relative">
    <button id="user-dropdown-btn"
            class="avatar-hover w-9 h-9 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold text-sm uppercase cursor-pointer select-none"
            aria-label="Menu pengguna">
        <?= mb_substr($user_nama ?? 'S', 0, 1) ?>
    </button>

    <div id="user-dropdown-menu"
         class="absolute right-0 top-full mt-2 w-64 bg-surface border border-outline-variant rounded-2xl shadow-xl z-50 hidden overflow-hidden">
        <div class="px-4 py-3 border-b border-outline-variant flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold text-sm uppercase shrink-0 select-none">
                <?= mb_substr($user_nama ?? 'S', 0, 1) ?>
            </div>
            <div class="min-w-0">
                <p class="font-semibold text-body-sm text-on-surface truncate"><?= esc($user_nama ?? 'Siswa') ?></p>
                <p class="text-caption text-on-surface-variant truncate"><?= esc($user_email ?? '') ?></p>
            </div>
        </div>
        <a href="<?= $profile_url ?? '#' ?>"
           class="dropdown-item flex items-center gap-3 px-4 py-3 text-body-sm text-on-surface hover:bg-surface-container-low transition-colors">
            <span class="material-symbols-outlined text-[18px] text-primary">settings</span>
            Pengaturan Akun
        </a>
        <a href="<?= $logout_url ?? base_url('logout') ?>"
           class="dropdown-item flex items-center gap-3 px-4 py-[11px] text-body-sm text-error hover:bg-error-container/20 transition-colors border-t border-outline-variant/50">
            <span class="material-symbols-outlined text-[18px]">logout</span>
            Keluar
        </a>
    </div>
</div>

<script>
(function () {
    'use strict';

    var btn = document.getElementById('user-dropdown-btn');
    var menu = document.getElementById('user-dropdown-menu');

    if (!btn || !menu) return;

    function closeAllDropdowns() {
        menu.classList.add('hidden');
        var nd = document.getElementById('notif-dropdown');
        if (nd) nd.classList.add('hidden');
    }

    function openDropdown() {
        var nd = document.getElementById('notif-dropdown');
        if (nd) nd.classList.add('hidden');
        menu.classList.remove('hidden');
        menu.classList.remove('dropdown-enter');
        void menu.offsetWidth;
        menu.classList.add('dropdown-enter');
    }

    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        if (menu.classList.contains('hidden')) {
            openDropdown();
        } else {
            menu.classList.add('hidden');
        }
    });

    document.addEventListener('click', function () {
        closeAllDropdowns();
    });

    menu.addEventListener('click', function (e) {
        e.stopPropagation();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeAllDropdowns();
        }
    });
})();
</script>
