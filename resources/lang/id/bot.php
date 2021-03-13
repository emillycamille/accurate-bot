<?php

return [
    // Emoji Unicode


    // Common words
    'price' => 'Harga',
    'stock' => 'Stok',
    'yes' => 'Ya',

    // Available Functions
    'available_functions' => 'Untuk saat ini, Emily bisa:',

    'abilities' => [
        'can_manage_items' => 'Cek stok dan harga barang pada Accurate ("Item <nama item>")',
        'can_show_purchases' => 'Menampilkan histori pembelian ("Pembelian"/"Purchase")',
        'can_show_sales' => 'Menampilkan histori penjualan ("Penjualan"/"Sales")',
        'can_switch_db' => 'Mengganti database yang sedang aktif ("Ganti db")',
        'can_do_math' => 'Menjalankan operasi matematika',
        'can_tell_time' => 'Menampilkan waktu saat ini ("Jam"/"Hari"/"Hari jam")',
        'can_tell_weather' => 'Menampilkan kondisi cuaca ("Cuaca di <nama kota>")',
    ],

    // Greet User
    'greet_user' => 'Hai, salam kenal, :name! 🤗 Emily seneng deh bisa ngobrol bareng kamu ☺️',

    // Weather
    'city_not_found' => 'Cuaca tidak ditemukan.',
    'weather_reply' => 'Cuaca di kota :cityName adalah :weatherDescription dengan suhu :temp ºC',

    // Accurate Related Replies
    // Basic
    'choose_db' => 'Pilih database dulu ya..',
    'db_opened' => 'Database berhasil dibuka! Silakan lanjut :D',
    'login_successful' => 'Anda berhasil terhubung ke Accurate sebagai user :name',

    // Item
    'multiple_items_match_keyword' => 'Ada beberapa item ditemukan, mana yang anda maksud?',
    'no_items_match_keyword' => 'Tidak ditemukan item dengan keyword ":keyword"',
    'prompt_show_image' => 'Tampilkan gambar?',

    // Purchases
    'ask_next_page' => 'Halaman berikutnya?',
    'show_purchases_title' => 'Berikut %d Transaksi Pembelianmu:',
    'no_purchases' => 'Kakak belum ada pembelian saat ini :)',
    'page' => '(Halaman %d)',

    // Sales
    'show_sales_title' => 'Berikut %d Transaksi Penjualanmu:',
    'no_sales' => 'Kakak belum ada penjualan. Tetap semangat ya kak :)',
];
