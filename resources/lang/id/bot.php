<?php

return [
    // Emoji Unicode

    // Common words
    'balance' => 'Saldo',
    'price' => 'Harga',
    'registered_since' => 'Terdaftar sejak',
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
    'greet_user' => 'Hai, :name! 🤗 Apa kabar? Have a nice day ya ☺️. Kalau kamu butuh bantuan, bisa ketik "help"',

    // Weather
    'city_not_found' => 'Waduhh, Emily gak bisa ketemu kota yang kamu cari nih.. Coba isi kota lain 😉.',
    'weather_reply' => 'Cuaca di kota :cityName adalah :weatherDescription dengan suhu :temp ºC.',

    // Accurate Related Replies
    // Basic
    'choose_db' => 'Pilih database dulu ya..',
    'db_opened' => 'Database berhasil dibuka! Silakan lanjut 😊',
    'login_successful' => 'Anda berhasil terhubung ke Accurate sebagai user :name',
    'no_db' => 'Tidak ada database yang ditemukan untuk akun Accurate ini. Coba ganti akun?',

    // Customer
    'multiple_customers_match_keyword' => 'Ada beberapa customer ditemukan, siapa yang anda maksud?',
    'no_customers_match_keyword' => 'Tidak ditemukan customer dengan keyword ":keyword"',

    // Item
    'multiple_items_match_keyword' => 'Ada beberapa item ditemukan, mana yang anda maksud?',
    'no_items_match_keyword' => 'Tidak ditemukan item dengan keyword ":keyword"',
    'prompt_show_image' => 'Tampilkan gambar?',

    // Purchases
    'ask_next_page' => 'Halaman berikutnya?',
    'purchases_date_title' => 'Pembelian tanggal :date sebesar ', 
    'show_purchases_title' => 'Berikut %d Transaksi Pembelianmu:',
    'no_purchases' => 'Kakak belum ada pembelian saat ini :)',
    'no_purchases_date' => 'Tidak ada pembelian pada tanggal :date',
    'page' => '(Halaman %d)',

    // Sales
    'show_sales_title' => 'Berikut %d Transaksi Penjualanmu:',
    'no_sales' => 'Kakak belum ada penjualan. Tetap semangat ya kak 🤗',
];
