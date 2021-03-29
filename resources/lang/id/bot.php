<?php

return [
    // Emoji Unicode

    // Common words
    'outstanding' => 'Piutang',
    'balance' => 'Saldo',
    'city' => 'Kota',
    'price' => 'Harga',
    'registered_since' => 'Terdaftar sejak',
    'stock' => 'Stok',
    'yes' => 'Ya',

    // Available Functions
    'available_functions' => 'Untuk saat ini, Naya bisa:',

    'abilities' => [
        'can_manage_items' => 'Cek stok dan harga barang pada Accurate ("Item <nama item>")',
        'can_manage_customers' => 'Cek saldo customer ("Customer <nama customer>")',
        'can_show_purchases_history' => 'Menampilkan histori pembelian ("Histori pembelian"/"Purchase history")',
        'can_show_purchases_today' => 'Menampilkan total pembelian hari ini ("Pembelian"/"Purchase")',
        'can_show_purchases' => 'Menampilkan pembelian pada tanggal tertentu ("Pembelian DD/MM/YYYY")',
        'can_show_sales_history' => 'Menampilkan histori penjualan ("Histori penjualan"/"Sales history")',
        'can_show_sales_today' => 'Menampilkan total penjualan hari ini ("Penjualan"/"Sales")',
        'can_show_sales' => 'Menampilkan penjualan pada tanggal tertentu ("Penjualan DD/MM/YYYY")',
        'can_switch_db' => 'Mengganti database yang sedang aktif ("Ganti db")',
        'can_do_math' => 'Menjalankan operasi matematika',
        'can_tell_time' => 'Menampilkan waktu saat ini ("Jam"/"Hari"/"Hari jam")',
        'can_tell_weather' => 'Menampilkan kondisi cuaca ("Cuaca di <nama kota>")',
    ],

    // Greet User
    'get_started_message' => 'Salam kenal, :name! 👋🏻 Aku Naya, kalau kamu butuh bantuan, bisa ketik "help"',
    'greet_user' => 'Hai, :name! 🤗 Apa kabar? Have a nice day ya ☺️. Kalau kamu butuh bantuan, bisa ketik "help"',

    // Weather
    'city_not_found' => 'Waduhh, Naya gak bisa ketemu kota yang kamu cari nih.. Coba isi kota lain 😉.',
    'weather_reply' => 'Cuaca di kota :city adalah :description dengan suhu :temp ºC.',

    // Accurate Related Replies
    // Basic
    'choose_db' => 'Pilih database dulu ya..',
    'db_opened' => 'Database berhasil dibuka! Silakan lanjut 😊',
    'login_successful' => 'Anda berhasil terhubung ke Accurate sebagai user :name!',
    'no_db' => 'Tidak ada database yang ditemukan untuk akun Accurate ini. Coba ganti akun?',

    // Customer
    'multiple_customers_match_keyword' => 'Ada beberapa customer ditemukan, siapa yang anda maksud?',
    'no_customers_match_keyword' => 'Tidak ditemukan customer dengan keyword ":keyword"',
    'unknown_customer' => 'Untuk mencari customer, bisa dengan ketik "customer <keyword customer>"',

    // Item
    'multiple_items_match_keyword' => 'Ada beberapa item ditemukan, mana yang anda maksud?',
    'no_items_match_keyword' => 'Tidak ditemukan item dengan keyword ":keyword"',
    'prompt_show_image' => 'Tampilkan gambar?',
    'unknown_item' => 'Untuk mencari item, bisa dengan ketik "item <keyword item>"',

    // Purchases
    'ask_next_page' => 'Halaman berikutnya?',
    'no_purchases' => 'Kakak belum ada pembelian saat ini :)',
    'no_purchases_date' => 'Tidak ada pembelian pada tanggal :date',
    'page' => '--- Halaman :page ---',
    'show_purchases_title' => 'Berikut %d Histori Transaksi Pembelian terakhirmu:',
    'total_purchase_at' => 'Total pembelian tanggal :date sebesar :amount',

    // Sales
    'no_sales' => 'Kakak belum ada penjualan. Tetap semangat ya kak 🤗',
    'no_sales_at' => 'Tidak ada penjualan pada tanggal :date',
    'sales_date_title' => 'Penjualan tanggal :date sebesar ',
    'show_sales_title' => 'Berikut %d Histori Transaksi Penjualan terakhirmu:',
    'total_sales_at' => 'Total penjualan tanggal :date sebesar :amount',
];
