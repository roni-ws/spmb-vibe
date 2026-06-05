<?php
$flashSuccess = session()->getFlashdata('success');
$flashError   = session()->getFlashdata('error');
$flashMessage = session()->getFlashdata('message');
$flashErrors  = session()->getFlashdata('errors');
?>
<?php if ($flashSuccess || $flashError || $flashMessage || $flashErrors): ?>
<script id="flash-toast-data" type="application/json"><?= json_encode([
    'success' => $flashSuccess,
    'error'   => $flashError,
    'message' => $flashMessage,
    'errors'  => is_array($flashErrors) ? $flashErrors : [],
]) ?></script>
<?php endif; ?>

<script src="<?= base_url('js/core/toast.js') ?>"></script>
<script src="<?= base_url('js/mobile/sidebar.js') ?>"></script>

<?= $this->renderSection('scripts') ?>
