<?php
$flashError   = session()->getFlashdata('error');
$flashErrors  = session()->getFlashdata('errors');
$flashMessage = session()->getFlashdata('message');
$alertMessages = [];
$alertType = 'error';
if ($flashErrors && is_array($flashErrors)) {
    $alertMessages = array_values($flashErrors);
} elseif ($flashError) {
    $alertMessages = [$flashError];
} elseif ($flashMessage) {
    $alertMessages = [$flashMessage];
    $alertType = 'success';
}
if (!empty($alertMessages)):
    $bgClass = $alertType === 'success'
        ? 'bg-tertiary-container/15 border border-tertiary/15'
        : 'bg-secondary-container/15 border border-secondary/15';
    $iconClass = $alertType === 'success'
        ? 'text-tertiary-fixed-dim'
        : 'text-secondary';
    $listClass = $alertType === 'success'
        ? 'text-on-tertiary-container'
        : 'text-on-secondary-container';
    $headerText = $alertType === 'success' ? 'Berhasil' : 'Terdapat kesalahan';
?>
<div class="flex items-start gap-3 p-4 rounded-xl <?= $bgClass ?>" role="alert">
    <span class="material-symbols-outlined shrink-0 <?= $iconClass ?>" style="font-size:20px">
        <?= $alertType === 'success' ? 'check_circle' : 'warning' ?>
    </span>
    <div class="flex-1 min-w-0">
        <p class="font-semibold text-sm <?= $iconClass ?> mb-1"><?= $headerText ?></p>
        <ul class="text-sm <?= $listClass ?> space-y-1 list-disc list-inside">
            <?php foreach ($alertMessages as $msg): ?>
            <li><?= esc($msg) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
<?php endif; ?>
