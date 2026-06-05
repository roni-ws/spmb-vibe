<!-- TOP APP BAR -->
<header class="bg-surface border-b border-outline-variant shadow-sm flex justify-between items-center
               px-md md:px-lg py-3 w-full sticky top-0 z-30">
    <?php
    $containerClass = 'flex items-center gap-sm min-w-0';
    $imgWrapClass   = 'w-9 h-9 rounded-lg overflow-hidden bg-slate-50 flex items-center justify-center shrink-0 shadow-sm border border-outline-variant/40';
    $imgClass       = 'w-full h-full object-contain p-0.5';
    $iconClass      = 'material-symbols-outlined text-primary text-2xl shrink-0';
    $textClass      = 'font-headline-sm text-headline-sm font-bold text-primary truncate leading-tight';
    $useEmpty       = true;
    ?>
    <?php include __DIR__ . '/../components/shared/logo-display.php'; ?>
    <!-- Right actions -->
    <div class="flex items-center gap-sm relative">
        <?php include __DIR__ . '/../components/shared/notification-bell.php'; ?>
        <?php
        $user_nama    = $admin_username ?? 'Admin';
        $user_email   = $user_email ?? '';
        $profile_url  = base_url('admin/profile-settings');
        $logout_url   = base_url('logout');
        include __DIR__ . '/../components/shared/user-dropdown.php';
        unset($user_nama, $user_email, $profile_url, $logout_url);
        ?>
    </div>
</header>
