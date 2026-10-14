<?= $this->extend('layouts/public') ?>

<?= $this->section('title') ?>Login<?= $this->endSection() ?>

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
        
        <!-- Login Card -->
        <div id="login-container" data-role="<?= esc(old('role', 'student')) ?>" class="relative z-10 w-full max-w-[480px] bg-surface-container-lowest dark:bg-on-surface border border-outline-variant/50 dark:border-outline/30 flex flex-col p-[32px] md:p-lg rounded-[24px]"
            style="box-shadow: 0 4px 24px -4px rgba(0, 32, 69, 0.08), 0 16px 40px -12px rgba(0, 32, 69, 0.12);">
            <!-- Header Section -->
            <div class="flex flex-col items-center text-center mb-[32px]">
                <div
                    class="w-20 h-20 rounded-full bg-primary-container text-on-primary-container dark:bg-white dark:text-primary flex items-center justify-center mb-sm shadow-sm overflow-hidden <?= (!empty($logoImg) && file_exists(FCPATH . $logoImg)) ? 'p-1' : '' ?>">
                    <?php if (!empty($logoImg) && file_exists(FCPATH . $logoImg)): ?>
                        <img src="<?= base_url($logoImg) ?>" class="w-full h-full object-contain" alt="Logo">
                    <?php else: ?>
                        <span class="material-symbols-outlined"
                            style="font-size: 40px; font-variation-settings: 'FILL' 1;">account_balance</span>
                    <?php endif; ?>
                </div>
                <h1 class="font-headline-md text-headline-md text-on-surface dark:text-white mb-xs">Selamat Datang</h1>
                <p class="font-body-md text-body-md text-on-surface-variant dark:text-slate-300">Masuk ke akun <?= esc($logoText) ?></p>
            </div>
            
            <!-- Segmented Control -->
            <div class="bg-surface-container-low dark:bg-slate-800 rounded-xl p-[4px] flex mb-[32px] shadow-inner">
                <button aria-selected="true"
                    class="flex-1 py-sm px-4 rounded-lg font-label-bold text-label-bold transition-all duration-200 ease-in-out bg-surface-container-lowest dark:bg-slate-700 text-on-surface dark:text-white shadow-sm"
                    id="btn-student" onclick="switchRole('student')">
                    Calon Siswa
                </button>
                <button aria-selected="false"
                    class="flex-1 py-sm px-4 rounded-lg font-label-bold text-label-bold transition-all duration-200 ease-in-out text-on-surface-variant dark:text-slate-400 hover:text-on-surface dark:hover:text-white bg-transparent shadow-none hover:bg-surface-variant/50 hover:dark:bg-slate-700/50"
                    id="btn-admin" onclick="switchRole('admin')">
                    Panitia / Admin
                </button>
            </div>
            
            <!-- Login Form -->
            <form class="flex flex-col gap-md" method="POST" action="<?= base_url('login') ?>" novalidate>
                <?= $this->include('components/shared/alert') ?>
                <!-- Input Identifier (Role-based label changes dynamically) -->
                <div class="flex flex-col gap-xs">
                    <label class="font-label-bold text-label-bold text-on-surface dark:text-slate-200 ml-1" for="identifier" id="label-identifier">Email atau Username</label>
                    <div class="relative group">
                        <div
                            class="absolute inset-y-0 left-0 pl-sm flex items-center pointer-events-none text-outline group-focus-within:text-primary group-focus-within:dark:text-white transition-colors">
                            <span class="material-symbols-outlined" style="font-size: 20px;">person</span>
                        </div>
                        <input
                            class="w-full pl-[40px] pr-sm py-[14px] bg-surface-container-lowest dark:bg-slate-900 border border-outline-variant dark:border-outline/50 rounded-lg font-body-md text-body-md text-on-surface dark:text-white placeholder:text-outline/70 focus:outline-none focus:border-primary focus:dark:border-white focus:ring-1 focus:ring-primary focus:dark:ring-white transition-all duration-200 shadow-sm"
                            id="identifier" name="identifier" placeholder="Masukkan email terdaftar" type="text" value="<?= esc(old('identifier')) ?>" required />
                    </div>
                </div>
                
                <!-- Password Input -->
                <div class="flex flex-col gap-xs">
                    <div class="flex justify-between items-center ml-1">
                        <label class="font-label-bold text-label-bold text-on-surface dark:text-slate-200" for="password">Kata Sandi</label>
                        <a class="font-body-sm text-body-sm text-primary dark:text-tertiary-fixed-dim hover:text-white hover:no-underline transition-colors font-medium"
                            href="<?= base_url('forgot-password') ?>">Lupa Kata Sandi?</a>
                    </div>
                    <div class="relative group">
                        <div
                            class="absolute inset-y-0 left-0 pl-sm flex items-center pointer-events-none text-outline group-focus-within:text-primary group-focus-within:dark:text-white transition-colors">
                            <span class="material-symbols-outlined" style="font-size: 20px;">lock</span>
                        </div>
                        <input
                            class="w-full pl-[40px] pr-[44px] py-[14px] bg-surface-container-lowest dark:bg-slate-900 border border-outline-variant dark:border-outline/50 rounded-lg font-body-md text-body-md text-on-surface dark:text-white placeholder:text-outline/70 focus:outline-none focus:border-primary focus:dark:border-white focus:ring-1 focus:ring-primary focus:dark:ring-white transition-all duration-200 shadow-sm"
                            id="password" name="password" placeholder="Masukkan kata sandi Anda" type="password" required />
                        <button
                            class="absolute inset-y-0 right-0 pr-sm flex items-center text-outline hover:text-on-surface dark:hover:text-white transition-colors focus:outline-none"
                            onclick="togglePasswordVisibility()" type="button">
                            <span class="material-symbols-outlined" id="visibility-icon"
                                style="font-size: 20px;">visibility_off</span>
                        </button>
                    </div>
                </div>
                
                <!-- CSRF Token -->
                <?= csrf_field() ?>

                <!-- Hidden Role input to submit with the form -->
                <input type="hidden" id="role" name="role" value="student" />

                <?php if (!empty($recaptchaSiteKey)): ?>
                <div class="g-recaptcha flex justify-center" data-sitekey="<?= esc($recaptchaSiteKey) ?>"></div>
                <?php endif; ?>
                
                <!-- Submit Action -->
                <div class="mt-sm flex flex-col gap-xs">
                    <!-- Remember Me -->
                    <label class="flex items-center gap-xs text-body-sm text-on-surface-variant cursor-pointer mb-1">
                        <input type="checkbox" name="remember" value="1" class="rounded border-outline-variant text-primary focus:ring-primary" />
                        Ingat Saya
                    </label>
                    <button
                        class="w-full flex items-center justify-center gap-sm py-[14px] bg-primary text-on-primary hover:bg-slate-900 hover:text-white dark:bg-white dark:text-primary dark:hover:bg-primary dark:hover:text-white rounded-lg font-label-bold text-label-bold active:bg-primary/95 shadow-md hover:shadow-lg transition-all duration-200 transform active:scale-[0.99]"
                        type="submit">
                        Masuk
                        <span class="material-symbols-outlined" style="font-size: 20px;">arrow_forward</span>
                    </button>
                </div>
            </form>
            
            <!-- Footer / Sign Up -->
            <div id="signup-footer" class="mt-[32px] text-center border-t border-outline-variant/30 dark:border-outline/20 pt-md">
                <p class="font-body-sm text-body-sm text-on-surface-variant dark:text-slate-400">
                    Belum punya akun?
                    <a class="text-primary dark:text-tertiary-fixed-dim font-label-bold text-label-bold hover:text-white hover:no-underline transition-colors"
                        href="<?= base_url('register') ?>">Daftar di Sini</a>
                </p>
            </div>
        </div>
    </main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <!-- Interaction Scripts -->
    <?= script_tag('js/auth/login.js') ?>
<?= $this->endSection() ?>
