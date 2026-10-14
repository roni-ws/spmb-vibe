<?= $this->extend('layouts/siswa') ?>

<?= $this->section('title') ?>
Dashboard Calon Siswa
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Welcome Section -->
<section class="mb-lg">
    <h1 class="font-display-lg-mobile text-display-lg-mobile md:font-display-lg md:text-display-lg text-primary mb-xs">
        Halo, <?= esc($nama_lengkap) ?>!
    </h1>
    <p class="font-body-lg text-body-lg text-on-surface-variant">
        Berikut adalah ringkasan status pendaftaran Anda di <?= esc($logoText) ?>.
    </p>
</section>

<!-- Bento Grid Layout -->
<div class="grid grid-cols-1 md:grid-cols-12 gap-md">
    <?= $this->include('components/siswa/dashboard/status_card') ?>
    <?= $this->include('components/siswa/dashboard/action_cards') ?>
    <?= $this->include('components/siswa/dashboard/announcements') ?>
</div>
<?= $this->endSection() ?>

