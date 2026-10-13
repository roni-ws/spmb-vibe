<?= $this->extend('layouts/public') ?>

<?= $this->section('title') ?>Lupa Kata Sandi<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <main class="relative w-full flex-grow flex items-center justify-center p-md py-16 md:py-24">
        <!-- Ambient Background Layer -->
        <div class="absolute inset-0 z-0 bg-cover bg-center"
            style="background-image: url('<?= base_url('images/school-library.png') ?>')">
            <!-- Frosted Glass Overlay -->
            <div class="absolute inset-0 bg-surface/85 dark:bg-on-surface/85 backdrop-blur-md"></div>
            <!-- Subtle Gradient Accent -->
            <div
                class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-primary-container/10 to-transparent mix-blend-multiply">
            </div>
        </div>
        
        <!-- Card Lupa Password -->
        <div class="relative z-10 w-full max-w-[480px] bg-surface-container-lowest dark:bg-on-surface border border-outline-variant/50 dark:border-outline/30 flex flex-col p-[32px] md:p-lg rounded-[24px]"
            style="box-shadow: 0 4px 24px -4px rgba(0, 32, 69, 0.08), 0 16px 40px -12px rgba(0, 32, 69, 0.12);">
            
            <!-- Header Section -->
            <div class="flex flex-col items-center text-center mb-[32px]">
                <div
                    class="w-20 h-20 rounded-full bg-primary-container text-on-primary-container dark:bg-white dark:text-primary flex items-center justify-center mb-sm shadow-sm overflow-hidden <?= (!empty($logoImg) && file_exists(FCPATH . $logoImg)) ? 'p-1' : '' ?>">
                    <?php if (!empty($logoImg) && file_exists(FCPATH . $logoImg)): ?>
                        <img src="<?= base_url($logoImg) ?>" class="w-full h-full object-contain" alt="Logo">
                    <?php else: ?>
                        <span class="material-symbols-outlined"
                            style="font-size: 40px; font-variation-settings: 'FILL' 0;">lock_reset</span>
                    <?php endif; ?>
                </div>
                <p class="font-body-sm text-body-sm text-primary dark:text-tertiary-fixed-dim font-medium mb-xs"><?= esc($logoText) ?></p>
                <h1 class="font-headline-md text-headline-md text-on-surface dark:text-white mb-xs">Lupa Kata Sandi?</h1>
                <p class="font-body-md text-body-md text-on-surface-variant dark:text-slate-300">Masukkan alamat email terdaftar Anda, dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi Anda.</p>
            </div>

            
            <!-- Forgot Password Form -->
            <form class="flex flex-col gap-md" action="<?= base_url('forgot-password') ?>" method="POST" novalidate>
                <?= $this->include('components/shared/alert') ?>
                <?= csrf_field() ?>
                <!-- Input Email -->
                <div class="flex flex-col gap-xs">
                    <label class="font-label-bold text-label-bold text-on-surface dark:text-slate-200 ml-1" for="email">Alamat Email</label>
                    <div class="relative group">
                        <div
                            class="absolute inset-y-0 left-0 pl-sm flex items-center pointer-events-none text-outline group-focus-within:text-primary group-focus-within:dark:text-white transition-colors">
                            <span class="material-symbols-outlined" style="font-size: 20px;">mail</span>
                        </div>
                        <input
                            class="w-full pl-[40px] pr-sm py-[14px] bg-surface-container-lowest dark:bg-slate-900 border border-outline-variant dark:border-outline/50 rounded-lg font-body-md text-body-md text-on-surface dark:text-white placeholder:text-outline/70 focus:outline-none focus:border-primary focus:dark:border-white focus:ring-1 focus:ring-primary focus:dark:ring-white transition-all duration-200 shadow-sm"
                            id="email" name="email" placeholder="Masukkan email terdaftar" type="email" required />
                    </div>
                </div>
                
                <?php if (!empty($recaptchaSiteKey)): ?>
                <div class="g-recaptcha flex justify-center" data-sitekey="<?= esc($recaptchaSiteKey) ?>"></div>
                <?php endif; ?>

                <!-- Submit Action -->
                <div class="mt-sm">
                    <button
                        class="w-full flex items-center justify-center gap-sm py-[14px] bg-primary text-on-primary hover:bg-slate-900 hover:text-white dark:bg-white dark:text-primary dark:hover:bg-primary dark:hover:text-white rounded-lg font-label-bold text-label-bold active:bg-primary/95 shadow-md hover:shadow-lg transition-all duration-200 transform active:scale-[0.99]"
                        type="submit">
                        Kirim Tautan Atur Ulang
                        <span class="material-symbols-outlined" style="font-size: 20px;">send</span>
                    </button>
                </div>
            </form>
            
            <!-- Navigation back to Login -->
            <div class="mt-[32px] text-center border-t border-outline-variant/30 dark:border-outline/20 pt-md">
                <a class="inline-flex items-center justify-center gap-xs font-label-bold text-label-bold text-primary dark:text-tertiary-fixed-dim hover:text-white hover:no-underline transition-colors"
                    href="<?= base_url('login') ?>">
                    <span class="material-symbols-outlined" style="font-size: 18px;">arrow_back</span>
                    Kembali ke Login
                </a>
            </div>
        </div>
    </main>
<?= $this->endSection() ?>
