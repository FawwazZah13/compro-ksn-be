<?php

// Buat direktori 'dist' jika belum ada
if (!file_exists('dist')) {
    mkdir('dist');
}

// Tulis konten ke dalam file di dalam direktori 'dist'
file_put_contents('dist/output.txt', 'Hello from my_script.php!');

echo "File output telah dihasilkan di dalam direktori dist.";
