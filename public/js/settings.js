/**
 * SPMB Dashboard - Settings Page Interactive Live Preview & Tab Navigation
 */

// Tab switching logic
window.switchTab = function (tabId) {
    // Hide all tab contents
    const contents = document.querySelectorAll(".tab-content");
    contents.forEach(content => {
        content.classList.add("hidden");
    });

    // Remove active style from all buttons
    const buttons = document.querySelectorAll("[id^='btn-tab-']");
    buttons.forEach(button => {
        button.classList.remove("text-primary", "border-b-2", "border-primary");
        button.classList.add("text-on-surface-variant");
    });

    // Show selected tab content
    document.getElementById(tabId).classList.remove("hidden");

    // Add active style to selected button
    const activeButton = document.getElementById("btn-" + tabId);
    if (activeButton) {
        activeButton.classList.remove("text-on-surface-variant");
        activeButton.classList.add("text-primary", "border-b-2", "border-primary");
    }
};

document.addEventListener("DOMContentLoaded", function () {
    const inputTahunAjaran = document.getElementById("tahun_ajaran");
    const inputTitle = document.getElementById("frontend_title");
    const inputSubtitle = document.getElementById("frontend_subtitle");
    const radioOpen = document.getElementById("is_open_1");
    const radioClosed = document.getElementById("is_open_0");
    const inputLogoText = document.getElementById("logo_text");
    
    // File inputs & Preview placeholders
    const logoFile = document.getElementById("logo_file");
    const heroFile = document.getElementById("hero_file");
    
    // Live Preview elements
    const previewTahunAjaran = document.getElementById("preview-tahun-ajaran");
    const previewTitle = document.getElementById("preview-title");
    const previewSubtitle = document.getElementById("preview-subtitle");
    const previewBadgeOpen = document.getElementById("preview-badge-open");
    const previewBadgeClosed = document.getElementById("preview-badge-closed");
    const previewBtnOpen = document.getElementById("preview-btn-open");
    const previewBtnClosed = document.getElementById("preview-btn-closed");
    const previewLogoText = document.getElementById("preview-logo-text");
    const previewLogoImgWrapper = document.getElementById("preview-logo-img-wrapper");
    const previewLogoImg = document.getElementById("preview-logo-img");
    const previewLogoIcon = document.getElementById("preview-logo-icon");
    const previewHeroBg = document.getElementById("preview-hero-bg");

    // Step 1 Preview elements
    const step1Title = document.getElementById("step1_title");
    const step1Desc = document.getElementById("step1_desc");
    const step1Icon = document.getElementById("step1_icon");
    const previewStep1Title = document.getElementById("preview-step1-title");
    const previewStep1Desc = document.getElementById("preview-step1-desc");
    const previewStep1Icon = document.getElementById("preview-step1-icon");

    // Req 1 Preview elements
    const req1Title = document.getElementById("req1_title");
    const req1Desc = document.getElementById("req1_desc");
    const req1Icon = document.getElementById("req1_icon");
    const previewReq1Title = document.getElementById("preview-req1-title");
    const previewReq1Desc = document.getElementById("preview-req1-desc");
    const previewReq1Icon = document.getElementById("preview-req1-icon");

    // Sync input to preview
    function syncTahunAjaran() {
        previewTahunAjaran.textContent = inputTahunAjaran.value || "2026/2027";
    }

    function syncTitle() {
        previewTitle.textContent = inputTitle.value || "Raih Masa Depan Gemilang di SMA Unggul Bangsa";
    }

    function syncSubtitle() {
        previewSubtitle.textContent = inputSubtitle.value || "Bergabunglah dengan komunitas pembelajar yang dinamis dan berprestasi.";
    }

    function syncLogoText() {
        previewLogoText.textContent = inputLogoText.value || "SPMB Unggul Bangsa";
    }

    function syncStatus() {
        if (radioOpen.checked) {
            previewBadgeOpen.classList.remove("hidden");
            previewBadgeClosed.classList.add("hidden");
            previewBtnOpen.classList.remove("hidden");
            previewBtnClosed.classList.add("hidden");
        } else {
            previewBadgeOpen.classList.add("hidden");
            previewBadgeClosed.classList.remove("hidden");
            previewBtnOpen.classList.add("hidden");
            previewBtnClosed.classList.remove("hidden");
        }
    }

    // Step 1 sync
    function syncStep1() {
        previewStep1Title.textContent = step1Title.value || "1. Registrasi Akun";
        previewStep1Desc.textContent = step1Desc.value || "Buat akun pendaftar secara online...";
        previewStep1Icon.textContent = step1Icon.value || "app_registration";
    }

    // Req 1 sync
    function syncReq1() {
        previewReq1Title.textContent = req1Title.value || "Pas Foto Terbaru";
        previewReq1Desc.textContent = req1Desc.value || "Pas foto berwarna ukuran 3x4 cm...";
        previewReq1Icon.textContent = req1Icon.value || "portrait";
    }

    // Logo image instant preview
    if (logoFile) {
        logoFile.addEventListener("change", function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    // Update form thumbnail
                    const formThumb = document.getElementById("logo-preview-image");
                    const formPlaceholder = document.getElementById("logo-preview-placeholder");
                    if (formThumb) {
                        formThumb.src = e.target.result;
                        formThumb.classList.remove("hidden");
                    }
                    if (formPlaceholder) {
                        formPlaceholder.classList.add("hidden");
                    }

                    // Update mockup preview logo
                    if (previewLogoImg) {
                        previewLogoImg.src = e.target.result;
                        previewLogoImgWrapper.classList.remove("hidden");
                        previewLogoIcon.classList.add("hidden");
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Hero background instant preview
    if (heroFile) {
        heroFile.addEventListener("change", function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    // Update form thumbnail
                    const formThumb = document.getElementById("hero-preview-image");
                    if (formThumb) {
                        formThumb.src = e.target.result;
                    }

                    // Update mockup preview background
                    if (previewHeroBg) {
                        previewHeroBg.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Attach Event Listeners
    if (inputTahunAjaran) inputTahunAjaran.addEventListener("input", syncTahunAjaran);
    if (inputTitle) inputTitle.addEventListener("input", syncTitle);
    if (inputSubtitle) inputSubtitle.addEventListener("input", syncSubtitle);
    if (inputLogoText) inputLogoText.addEventListener("input", syncLogoText);
    
    if (radioOpen) radioOpen.addEventListener("change", syncStatus);
    if (radioClosed) radioClosed.addEventListener("change", syncStatus);

    if (step1Title) step1Title.addEventListener("input", syncStep1);
    if (step1Desc) step1Desc.addEventListener("input", syncStep1);
    if (step1Icon) step1Icon.addEventListener("change", syncStep1);

    if (req1Title) req1Title.addEventListener("input", syncReq1);
    if (req1Desc) req1Desc.addEventListener("input", syncReq1);
    if (req1Icon) req1Icon.addEventListener("change", syncReq1);

    // Initial Sync
    if (inputTahunAjaran) {
        syncTahunAjaran();
        syncTitle();
        syncSubtitle();
        syncLogoText();
        syncStatus();
        syncStep1();
        syncReq1();
    }
});
