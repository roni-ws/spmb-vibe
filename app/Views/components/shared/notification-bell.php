<div class="relative">
    <button id="notif-bell-btn"
            class="relative flex items-center justify-center w-9 h-9 rounded-full text-on-surface-variant hover:bg-surface-container-low hover:text-primary transition-colors duration-150"
            aria-label="Notifikasi">
        <span class="material-symbols-outlined text-2xl">notifications</span>
        <span id="notif-badge"
              class="absolute -top-0.5 -right-0.5 bg-error text-on-error text-[9px] font-bold px-1.5 py-0.5 rounded-full min-w-[16px] text-center leading-none hidden"></span>
    </button>

    <div id="notif-dropdown"
         class="absolute right-0 top-full mt-2 w-80 bg-surface border border-outline-variant rounded-2xl shadow-xl z-50 hidden overflow-hidden">
        <div class="px-4 py-3 border-b border-outline-variant flex items-center justify-between">
            <h4 class="font-semibold text-body-sm text-on-surface">Notifikasi</h4>
            <button id="notif-mark-read"
                    class="text-caption text-primary hover:underline font-semibold transition-colors">Tandai Dibaca</button>
        </div>
        <div id="notif-list" class="max-h-[320px] overflow-y-auto custom-scrollbar">
            <div class="p-4 text-center text-caption text-on-surface-variant">Memuat...</div>
        </div>
    </div>
</div>

<script src="<?= base_url('js/components/notification-bell.js') ?>"></script>
