<?= $this->extend('layouts/public') ?>

<?= $this->section('title') ?>Verifikasi Email<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <main class="relative w-full flex-grow flex items-center justify-center p-md py-16 md:py-24">
        <div class="absolute inset-0 z-0 bg-cover bg-center"
            style="background-image: url('<?= base_url('images/school-library.png') ?>')">
            <div class="absolute inset-0 bg-surface/85 dark:bg-on-surface/85 backdrop-blur-md"></div>
            <div
                class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-primary-container/10 to-transparent mix-blend-multiply">
            </div>
        </div>

        <div class="relative z-10 w-full max-w-[480px] bg-surface-container-lowest dark:bg-on-surface border border-outline-variant/50 dark:border-outline/30 flex flex-col p-[32px] md:p-lg rounded-[24px]"
            style="box-shadow: 0 4px 24px -4px rgba(0, 32, 69, 0.08), 0 16px 40px -12px rgba(0, 32, 69, 0.12);">

            <div class="flex flex-col items-center text-center mb-[32px]">
                <div
                    class="w-20 h-20 rounded-full bg-primary-container text-on-primary-container dark:bg-white dark:text-primary flex items-center justify-center mb-sm shadow-sm overflow-hidden">
                    <span class="material-symbols-outlined"
                        style="font-size: 40px; font-variation-settings: 'FILL' 1;">mail_lock</span>
                </div>
                <h1 class="font-headline-md text-headline-md text-on-surface dark:text-white mb-xs">Verifikasi Email</h1>
                <p class="font-body-md text-body-md text-on-surface-variant dark:text-slate-300">
                    Kami telah mengirimkan kode aktivasi ke email <strong class="text-on-surface dark:text-white"><?= isset($user) ? esc($user->email) : 'terdaftar' ?></strong>.
                </p>
            </div>

            <?php if (session('error')): ?>
            <div class="flex items-start gap-3 p-4 rounded-xl bg-secondary-container/15 border border-secondary/15 mb-md" role="alert">
                <span class="material-symbols-outlined shrink-0 text-secondary" style="font-size:20px">warning</span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-on-secondary-container"><?= esc(session('error')) ?></p>
                </div>
            </div>
            <?php endif; ?>

            <form action="<?= url_to('auth-action-verify') ?>" method="POST" class="flex flex-col gap-md">
                <?= csrf_field() ?>

                <div class="flex flex-col gap-xs">
                    <label class="font-label-bold text-label-bold text-on-surface dark:text-slate-200 ml-1" for="token">Kode Aktivasi</label>
                    <div class="relative group">
                        <div
                            class="absolute inset-y-0 left-0 pl-sm flex items-center pointer-events-none text-outline group-focus-within:text-primary group-focus-within:dark:text-white transition-colors">
                            <span class="material-symbols-outlined" style="font-size: 20px;">pin</span>
                        </div>
                        <input
                            class="w-full pl-[40px] pr-sm py-[14px] bg-surface-container-lowest dark:bg-slate-900 border border-outline-variant dark:border-outline/50 rounded-lg font-body-md text-body-md text-on-surface dark:text-white placeholder:text-outline/70 focus:outline-none focus:border-primary focus:dark:border-white focus:ring-1 focus:ring-primary focus:dark:ring-white transition-all duration-200 shadow-sm tracking-[8px] text-center font-bold text-xl"
                            id="token" name="token" placeholder="000000" inputmode="numeric" pattern="[0-9]*"
                            autocomplete="one-time-code" value="<?= old('token') ?>" required />
                    </div>
                </div>

                <div class="mt-sm">
                    <button
                        class="w-full flex items-center justify-center gap-sm py-[14px] bg-primary text-on-primary hover:bg-slate-900 hover:text-white dark:bg-white dark:text-primary dark:hover:bg-primary dark:hover:text-white rounded-lg font-label-bold text-label-bold active:bg-primary/95 shadow-md hover:shadow-lg transition-all duration-200 transform active:scale-[0.99]"
                        type="submit">
                        Aktivasi Akun
                        <span class="material-symbols-outlined" style="font-size: 20px;">check_circle</span>
                    </button>
                </div>
            </form>

            <div class="mt-[24px] p-4 rounded-xl bg-secondary-container/15 border border-secondary/15 flex items-start gap-3">
                <span class="material-symbols-outlined shrink-0 text-secondary" style="font-size:20px">info</span>
                <div class="text-sm text-on-secondary-container leading-relaxed">
                    <strong>Tidak menerima email?</strong><br>
                    Periksa folder <strong>Spam</strong> atau <strong>Promosi</strong> di kotak masuk email Anda. Pastikan alamat email yang didaftarkan sudah benar.
                </div>
            </div>

            <div class="mt-[24px] text-center border-t border-outline-variant/30 dark:border-outline/20 pt-md">
                <p class="font-body-sm text-body-sm text-on-surface-variant dark:text-slate-400">
                    <a class="text-primary dark:text-tertiary-fixed-dim font-label-bold text-label-bold hover:text-white hover:no-underline transition-colors"
                        href="<?= base_url() ?>">Kembali ke Beranda</a>
                </p>
            </div>
        </div>
    </main>
<?= $this->endSection() ?>
