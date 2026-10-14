<?php
$logoText ??= 'SPMB Unggul Bangsa';
$logoImg ??= '';
$can_cetak_bukti = $can_cetak_bukti ?? false;

$currentUri = service('uri')->getPath();
$isDashboard = ($currentUri === 'siswa/dashboard' || $currentUri === 'siswa');
$isCetakFormulir = str_contains($currentUri, 'cetak-formulir');

$headTitleSuffix = 'Calon Siswa | ' . esc($logoText);
?>
<!DOCTYPE html>
<html lang="id" class="light">
<head>
    <?php include __DIR__ . '/../partials/head-content.php'; ?>

    <!-- Admin/Dashboard CSS -->
    <?= link_tag('css/admin.css') ?>

    <?= $this->renderSection('css') ?>
</head>

<body class="bg-background text-on-background flex overflow-hidden min-h-screen font-body-md text-body-md">

    <?php include __DIR__ . '/../partials/web-sidebar-siswa.php'; ?>

    <!-- MAIN CONTENT WRAPPER -->
    <div class="flex-grow md:ml-60 flex flex-col h-screen overflow-y-auto w-full">

        <?php include __DIR__ . '/../partials/web-navbar-siswa.php'; ?>

        <!-- PAGE CONTENT -->
        <main class="p-4 md:p-lg flex-grow w-full">
            <?= $this->renderSection('content') ?>
        </main>

        <!-- Mobile bottom padding -->
        <div class="h-20 md:hidden w-full"></div>
    </div>

    <?php include __DIR__ . '/../partials/m-bottom-nav-siswa.php'; ?>

    <!-- jQuery (Local) -->
    <script src="<?= base_url('vendor/jquery/jquery.min.js') ?>"></script>

    <?php include __DIR__ . '/../partials/scripts.php'; ?>
</body>
</html>
