<?= $this->extend('layouts/frontend') ?>

<?= $this->section('title') ?>Daftar Akun<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <main class="relative w-full flex-grow flex items-center justify-center p-md py-16 md:py-24">
        <!-- Ambient Background Layer -->
        <div class="absolute inset-0 z-0 bg-cover bg-center"
            style="background-image: url('<?= base_url('images/school_library.png') ?>')">
            <!-- Frosted Glass Overlay -->
            <div class="absolute inset-0 bg-surface/85 dark:bg-on-surface/85 backdrop-blur-md"></div>
            <!-- Subtle Gradient Accent -->
            <div
                class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-primary-container/10 to-transparent mix-blend-multiply">
            </div>
        </div>
        
        <!-- Registration Content Wrapper (Desktop side-by-side) -->
        <div class="relative z-10 w-full max-w-[1100px] mx-auto grid grid-cols-1 lg:grid-cols-2 items-center justify-center gap-lg px-md">
            
            <!-- Branding / Welcome Side (Visible on Large Desktop) -->
            <div class="hidden lg:flex flex-col justify-center text-left w-full">
                <div class="flex items-center gap-sm mb-md">
                    <span class="material-symbols-outlined text-display-lg text-primary dark:text-white"
                        style="font-size: 48px; font-variation-settings: 'FILL' 1;">school</span>
                    <span class="font-headline-md text-2xl lg:text-3xl xl:text-headline-md text-primary dark:text-white font-bold tracking-tight">SPMB Unggul Bangsa</span>
                </div>
                <h1 class="font-display-lg text-3xl lg:text-4xl xl:text-display-lg text-on-surface dark:text-white mb-sm leading-tight">Mulai Perjalanan Akademik Anda</h1>
                <p class="font-body-lg text-body-lg text-on-surface-variant dark:text-slate-300">
                    Bergabunglah dengan institusi terbaik kami. Buat akun pendaftaran Anda hari ini untuk memulai pengisian data, melacak berkas pendaftaran, dan menyongsong masa depan gemilang.
                </p>
            </div>
            
            <!-- Registration Form Card -->
            <div class="w-full max-w-[480px] justify-self-center lg:justify-self-end bg-surface-container-lowest dark:bg-on-surface border border-outline-variant/50 dark:border-outline/30 flex flex-col p-[32px] md:p-lg rounded-[24px]"
                style="box-shadow: 0 4px 24px -4px rgba(0, 32, 69, 0.08), 0 16px 40px -12px rgba(0, 32, 69, 0.12);">
                
                <!-- Mobile Logo (Visible when Branding side is hidden) -->
                <div class="lg:hidden flex items-center justify-center gap-sm mb-lg">
                    <span class="material-symbols-outlined text-headline-md text-primary dark:text-white animate-bounce"
                        style="font-variation-settings: 'FILL' 1;">school</span>
                    <span class="font-headline-md text-headline-md text-primary dark:text-white font-bold">SPMB Unggul Bangsa</span>
                </div>
                
                <div class="mb-lg text-center md:text-left">
                    <h2 class="font-headline-md text-headline-md text-on-surface dark:text-white mb-xs">Buat Akun Baru</h2>
                    <p class="font-body-sm text-body-sm text-on-surface-variant dark:text-slate-300">Lengkapi data di bawah ini untuk mendaftar.</p>
                </div>

                <!-- Alert Error Dinamis -->
                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="mb-md p-sm bg-red-50 text-red-800 border border-red-200 rounded-xl">
                        <ul class="list-disc pl-5 text-caption leading-relaxed">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="mb-md p-sm bg-red-50 text-red-800 border border-red-200 rounded-xl text-caption">
                        <?= esc(session()->getFlashdata('error')) ?>
                    </div>
                <?php endif; ?>
                
                <form action="<?= base_url('register') ?>" class="flex flex-col gap-md" method="POST">
                    <?= csrf_field() ?>

                    <!-- Full Name -->
                    <div class="flex flex-col gap-xs">
                        <label class="font-label-bold text-label-bold text-on-surface dark:text-slate-200 ml-1" for="fullName">Nama Lengkap</label>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-sm flex items-center pointer-events-none text-outline group-focus-within:text-primary group-focus-within:dark:text-white transition-colors">
                                <span class="material-symbols-outlined" style="font-size: 20px;">person</span>
                            </div>
                            <input
                                class="w-full pl-[40px] pr-sm py-[14px] bg-surface-container-lowest dark:bg-slate-900 border border-outline-variant dark:border-outline/50 rounded-lg font-body-md text-body-md text-on-surface dark:text-white placeholder:text-outline/70 focus:outline-none focus:border-primary focus:dark:border-white focus:ring-1 focus:ring-primary focus:dark:ring-white transition-all duration-200 shadow-sm"
                                id="fullName" name="fullName" placeholder="John Doe" required="" type="text" value="<?= esc(old('fullName')) ?>" />
                        </div>
                    </div>
                    
                    <!-- Email -->
                    <div class="flex flex-col gap-xs">
                        <label class="font-label-bold text-label-bold text-on-surface dark:text-slate-200 ml-1" for="email">Alamat Email</label>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-sm flex items-center pointer-events-none text-outline group-focus-within:text-primary group-focus-within:dark:text-white transition-colors">
                                <span class="material-symbols-outlined" style="font-size: 20px;">mail</span>
                            </div>
                            <input
                                class="w-full pl-[40px] pr-sm py-[14px] bg-surface-container-lowest dark:bg-slate-900 border border-outline-variant dark:border-outline/50 rounded-lg font-body-md text-body-md text-on-surface dark:text-white placeholder:text-outline/70 focus:outline-none focus:border-primary focus:dark:border-white focus:ring-1 focus:ring-primary focus:dark:ring-white transition-all duration-200 shadow-sm"
                                id="email" name="email" placeholder="john@example.com" required="" type="email" value="<?= esc(old('email')) ?>" />
                        </div>
                    </div>
                    
                    <!-- Username -->
                    <div class="flex flex-col gap-xs">
                        <label class="font-label-bold text-label-bold text-on-surface dark:text-slate-200 ml-1" for="username">Nama Pengguna (Username)</label>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-sm flex items-center pointer-events-none text-outline group-focus-within:text-primary group-focus-within:dark:text-white transition-colors">
                                <span class="material-symbols-outlined" style="font-size: 20px;">alternate_email</span>
                            </div>
                            <input
                                class="w-full pl-[40px] pr-sm py-[14px] bg-surface-container-lowest dark:bg-slate-900 border border-outline-variant dark:border-outline/50 rounded-lg font-body-md text-body-md text-on-surface dark:text-white placeholder:text-outline/70 focus:outline-none focus:border-primary focus:dark:border-white focus:ring-1 focus:ring-primary focus:dark:ring-white transition-all duration-200 shadow-sm"
                                id="username" name="username" placeholder="johndoe123" required="" type="text" value="<?= esc(old('username')) ?>" />
                        </div>
                    </div>
                    
                    <!-- Password -->
                    <div class="flex flex-col gap-xs">
                        <label class="font-label-bold text-label-bold text-on-surface dark:text-slate-200 ml-1" for="password">Kata Sandi</label>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-sm flex items-center pointer-events-none text-outline group-focus-within:text-primary group-focus-within:dark:text-white transition-colors">
                                <span class="material-symbols-outlined" style="font-size: 20px;">lock</span>
                            </div>
                            <input
                                class="w-full pl-[40px] pr-[44px] py-[14px] bg-surface-container-lowest dark:bg-slate-900 border border-outline-variant dark:border-outline/50 rounded-lg font-body-md text-body-md text-on-surface dark:text-white placeholder:text-outline/70 focus:outline-none focus:border-primary focus:dark:border-white focus:ring-1 focus:ring-primary focus:dark:ring-white transition-all duration-200 shadow-sm"
                                id="password" name="password" placeholder="••••••••" required="" type="password" />
                            <button
                                class="absolute inset-y-0 right-0 pr-sm flex items-center text-outline hover:text-on-surface dark:hover:text-white transition-colors focus:outline-none"
                                onclick="togglePasswordVisibility()" type="button">
                                <span class="material-symbols-outlined" id="visibility-icon"
                                    style="font-size: 20px;">visibility_off</span>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Terms & Conditions Checkbox -->
                    <div class="flex items-start gap-sm pt-xs">
                        <div class="flex items-center h-5">
                            <input
                                class="w-4 h-4 text-primary bg-surface border-outline-variant rounded focus:ring-primary-fixed focus:ring-2 focus:ring-offset-0 transition-colors cursor-pointer"
                                id="terms" name="terms" required="" type="checkbox" <?= old('terms') ? 'checked' : '' ?> />
                        </div>
                        <div class="text-body-sm font-body-sm text-on-surface-variant dark:text-slate-300">
                            <label class="cursor-pointer" for="terms">Saya menyetujui <a
                                    class="text-primary dark:text-tertiary-fixed-dim font-semibold hover:text-white hover:no-underline transition-colors" href="#">Ketentuan Layanan</a> dan <a
                                    class="text-primary dark:text-tertiary-fixed-dim font-semibold hover:text-white hover:no-underline transition-colors" href="#">Kebijakan Privasi</a>.</label>
                        </div>
                    </div>
                    
                    <!-- Submit Action -->
                    <div class="mt-sm">
                        <button
                            class="w-full flex items-center justify-center gap-sm py-[14px] bg-primary text-on-primary hover:bg-slate-900 hover:text-white dark:bg-white dark:text-primary dark:hover:bg-primary dark:hover:text-white rounded-lg font-label-bold text-label-bold active:bg-primary/95 shadow-md hover:shadow-lg transition-all duration-200 transform active:scale-[0.99]"
                            type="submit">
                            Daftar Akun
                            <span class="material-symbols-outlined" style="font-size: 20px;">person_add</span>
                        </button>
                    </div>
                </form>
                
                <!-- Sign In Footer -->
                <div class="mt-[32px] text-center border-t border-outline-variant/30 dark:border-outline/20 pt-md">
                    <p class="font-body-sm text-body-sm text-on-surface-variant dark:text-slate-400">
                        Sudah memiliki akun?
                        <a class="text-primary dark:text-tertiary-fixed-dim font-label-bold text-label-bold hover:text-white hover:no-underline transition-colors ml-xs"
                            href="<?= base_url('login') ?>">Masuk</a>
                    </p>
                </div>
            </div>
            
        </div>
    </main>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <!-- Interaction Scripts -->
    <?= script_tag('js/register.js') ?>
<?= $this->endSection() ?>
