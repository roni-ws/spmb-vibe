(function () {
    'use strict';

    var BASE = window.BASE_URL || '/';

    function getNotifUrl() {
        if (window.location.pathname.indexOf('/admin') !== -1) {
            return BASE + 'admin/notifications';
        }
        return BASE + 'siswa/notifications';
    }

    function getMarkUrl() {
        if (window.location.pathname.indexOf('/admin') !== -1) {
            return BASE + 'admin/notifications/mark-as-read';
        }
        return BASE + 'siswa/notifications/mark-as-read';
    }

    function csrfToken() {
        return window.CSRF_HASH || '';
    }

    function csrfName() {
        return window.CSRF_TOKEN_NAME || 'csrf_test_name';
    }

    function fetchNotifications() {
        fetch(getNotifUrl(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            updateBadge(data.unread || 0);
            renderList(data.notifications || []);
        })
        .catch(function () {});
    }

    function updateBadge(count) {
        var badge = document.getElementById('notif-badge');
        if (!badge) return;
        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.classList.remove('hidden');
            badge.classList.add('badge-pulse');
        } else {
            badge.classList.add('hidden');
            badge.classList.remove('badge-pulse');
        }
    }

    function renderList(notifs) {
        var list = document.getElementById('notif-list');
        if (!list) return;

        if (!notifs.length) {
            list.innerHTML = '<div class="p-4 text-center text-caption text-on-surface-variant">Tidak ada notifikasi</div>';
            return;
        }

        var html = '';
        notifs.forEach(function (n) {
            var icon = n.type === 'welcome' ? 'celebration'
                     : n.type === 'status_change' ? 'fact_check'
                     : n.type === 'pengumuman' ? 'campaign'
                     : n.type === 'siswa_baru' || n.type === 'siswa_finalized' ? 'person_add'
                     : 'notifications';
            html += '<div class="px-4 py-3 border-b border-outline-variant/50 hover:bg-surface-container-low transition-colors';
            if (!n.is_read) html += ' bg-primary-container/10';
            html += '">';
            html += '<div class="flex items-start gap-3">';
            html += '<span class="material-symbols-outlined text-[18px] text-primary shrink-0 mt-0.5">' + icon + '</span>';
            html += '<div class="min-w-0">';
            html += '<p class="text-body-sm font-medium text-on-surface truncate">' + escapeHtml(n.title || '') + '</p>';
            if (n.konten) {
                html += '<p class="text-caption text-on-surface-variant line-clamp-2 mt-0.5">' + escapeHtml(n.konten) + '</p>';
            }
            html += '<p class="text-[10px] text-outline mt-1">' + formatTime(n.created_at) + '</p>';
            html += '</div></div></div>';
        });
        list.innerHTML = html;
    }

    function markAsRead() {
        var data = {};
        data[csrfName()] = csrfToken();

        fetch(getMarkUrl(), {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken(),
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            },
            body: new URLSearchParams(data)
        })
        .then(function (res) {
            if (res.ok) {
                updateBadge(0);
                fetchNotifications();
            } else {
                console.warn('markAsRead failed:', res.status, res.statusText);
            }
        })
        .catch(function (err) {
            console.error('markAsRead error:', err);
        });
    }

    function escapeHtml(str) {
        if (!str) return '';
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    function formatTime(dateStr) {
        if (!dateStr) return '';
        try {
            var d = new Date(dateStr);
            var now = new Date();
            var diff = Math.floor((now - d) / 1000);
            if (diff < 60) return 'baru saja';
            if (diff < 3600) return Math.floor(diff / 60) + ' menit lalu';
            if (diff < 86400) return Math.floor(diff / 3600) + ' jam lalu';
            return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
        } catch (e) {
            return dateStr;
        }
    }

    function closeAllDropdowns() {
        var nd = document.getElementById('notif-dropdown');
        if (nd) nd.classList.add('hidden');
        var ud = document.getElementById('user-dropdown-menu');
        if (ud) ud.classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', function () {
        var bellBtn = document.getElementById('notif-bell-btn');
        var dropdown = document.getElementById('notif-dropdown');
        var markBtn = document.getElementById('notif-mark-read');

        if (!bellBtn || !dropdown) return;

        function openDropdown() {
            var ud = document.getElementById('user-dropdown-menu');
            if (ud) ud.classList.add('hidden');
            dropdown.classList.remove('hidden');
            dropdown.classList.remove('dropdown-enter');
            void dropdown.offsetWidth;
            dropdown.classList.add('dropdown-enter');
            fetchNotifications();
        }

        bellBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            if (dropdown.classList.contains('hidden')) {
                openDropdown();
            } else {
                dropdown.classList.add('hidden');
            }
        });

        document.addEventListener('click', function () {
            closeAllDropdowns();
        });

        dropdown.addEventListener('click', function (e) {
            e.stopPropagation();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeAllDropdowns();
            }
        });

        if (markBtn) {
            markBtn.addEventListener('click', function (e) {
                e.preventDefault();
                markAsRead();
            });
        }

        fetchNotifications();
        setInterval(fetchNotifications, 30000);
    });
})();
