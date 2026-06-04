<?php
$logoText = \App\Models\SettingsModel::getSetting('logo_text', 'SPMB Unggul Bangsa');
$logoImg  = \App\Models\SettingsModel::getSetting('logo_img', '');

$shieldUser = auth()->user();
$admin_username = $shieldUser ? $shieldUser->username : 'Administrator';

$roleLabel = 'Panitia SPMB';
if ($shieldUser) {
    if ($shieldUser->inGroup('admin')) {
        $roleLabel = 'Administrator';
    } elseif ($shieldUser->inGroup('panitia')) {
        $roleLabel = 'Panitia PPDB';
    }
}

$currentUri = service('uri')->getPath();
$isDashboard = ($currentUri === 'admin/dashboard' || $currentUri === 'admin');
$isPendaftar = str_contains($currentUri, 'pendaftar') || str_contains($currentUri, 'admin/verify') || str_contains($currentUri, 'admin/delete');
$isExport    = str_contains($currentUri, 'export');
$isSettings  = str_contains($currentUri, 'settings');
$isUsers     = str_contains($currentUri, 'users');

$headTitleSuffix = 'Admin | ' . esc($logoText);
?>
<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <?php include __DIR__ . '/../partials/head-content.php'; ?>

    <!-- DataTables CSS (Local) -->
    <link rel="stylesheet" href="<?= base_url('vendor/datatables/css/jquery.dataTables.min.css') ?>" />
    <link rel="stylesheet" href="<?= base_url('vendor/datatables/css/responsive.dataTables.min.css') ?>" />

    <!-- Admin CSS -->
    <?= link_tag('css/admin.css') ?>

    <?= $this->renderSection('css') ?>
</head>

<body class="bg-background text-on-background flex overflow-hidden min-h-screen font-body-md text-body-md">

    <?php include __DIR__ . '/../partials/web-sidebar-admin.php'; ?>

    <!-- MAIN CONTENT WRAPPER -->
    <div class="flex-grow md:ml-60 flex flex-col h-screen overflow-y-auto w-full">

        <?php include __DIR__ . '/../partials/web-navbar-admin.php'; ?>

        <!-- PAGE CONTENT -->
        <main class="p-4 md:p-lg flex-grow w-full">
            <?= $this->renderSection('content') ?>
        </main>

        <!-- Mobile bottom padding -->
        <div class="h-20 md:hidden w-full"></div>
    </div>

    <?php include __DIR__ . '/../partials/m-bottom-nav-admin.php'; ?>

    <!-- jQuery + DataTables JS (Local) -->
    <script src="<?= base_url('vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('vendor/datatables/js/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('vendor/datatables/js/dataTables.responsive.min.js') ?>"></script>

    <!-- ApexCharts (Local) -->
    <script src="<?= base_url('vendor/apexcharts/apexcharts.min.js') ?>"></script>

    <?php include __DIR__ . '/../partials/scripts.php'; ?>
</body>
</html>
