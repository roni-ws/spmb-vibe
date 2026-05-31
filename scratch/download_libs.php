<?php
/**
 * SPMB Vibe - Local Library Downloader
 * 
 * This script downloads frontend dependencies from CDNs and saves them locally 
 * under the public/vendor/ directory to allow offline operations.
 */

// Define assets to download (URL => local file path relative to public/vendor/)
$assets = [
    // jQuery
    'https://code.jquery.com/jquery-3.7.1.min.js' => 'jquery/jquery.min.js',
    
    // Tailwind CSS Standalone
    'https://cdn.tailwindcss.com?plugins=forms,container-queries' => 'tailwindcss/tailwind.js',
    
    // DataTables CSS & JS
    'https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css' => 'datatables/css/jquery.dataTables.min.css',
    'https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css' => 'datatables/css/responsive.dataTables.min.css',
    'https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js' => 'datatables/js/jquery.dataTables.min.js',
    'https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js' => 'datatables/js/dataTables.responsive.min.js',
    'https://cdn.datatables.net/plug-ins/1.13.8/i18n/id.json' => 'datatables/i18n/id.json',
    
    // ApexCharts
    'https://cdn.jsdelivr.net/npm/apexcharts@3.49.0/dist/apexcharts.min.js' => 'apexcharts/apexcharts.min.js'
];

$vendorDir = dirname(__DIR__) . '/public/vendor';

echo "=== SPMB VIBE LOCAL LIBRARY DOWNLOADER ===\n";
echo "Vendor Target: " . $vendorDir . "\n\n";

foreach ($assets as $url => $relativePath) {
    $targetPath = $vendorDir . '/' . $relativePath;
    $targetDir = dirname($targetPath);
    
    // Create directory if it doesn't exist
    if (!is_dir($targetDir)) {
        if (!mkdir($targetDir, 0755, true)) {
            echo "[-] FAILED to create directory: $targetDir\n";
            continue;
        }
        echo "[+] Created directory: $targetDir\n";
    }
    
    echo "[*] Downloading $url ...\n";
    
    // Set custom user agent because some CDNs block default PHP user agents
    $options = [
        'http' => [
            'method' => 'GET',
            'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36\r\n"
        ]
    ];
    $context = stream_context_create($options);
    $content = @file_get_contents($url, false, $context);
    
    if ($content === false) {
        echo "[-] FAILED to download from $url\n";
    } else {
        if (file_put_contents($targetPath, $content) !== false) {
            echo "[+] SUCCESS: Saved to $relativePath\n";
        } else {
            echo "[-] FAILED to save to $targetPath\n";
        }
    }
    echo "--------------------------------------------------\n";
}

echo "\nDone!\n";
