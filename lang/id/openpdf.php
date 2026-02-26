<?php

return array_replace_recursive(require base_path('lang/en/openpdf.php'), [
    'meta' => ['title' => 'Alat Konversi PDF Gratis | Layanan PDF Open Source', 'description' => 'Konversi file PDF, Word, Excel, dan JPG gratis secara online. PDF to Word, PDF to Excel, PDF to JPG, Compress PDF, Merge PDF, Word to PDF, Excel to PDF, JPG to PDF.'],
    'hero' => ['title' => 'Alat konversi PDF gratis untuk semua', 'description' => 'Tanpa keanggotaan wajib. Pengunjung dapat memakai semua alat dengan batas wajar. Login Google membuka mode tanpa batas.'],
    'auth' => ['optional' => 'Login Google opsional', 'visitor' => 'Pengunjung', 'logout' => 'Keluar'],
    'workspace' => ['title' => 'Ruang Konversi', 'drop_title' => 'Tarik file ke sini atau klik untuk unggah', 'settings' => 'Pengaturan Konversi', 'convert' => 'Konversi'],
    'limits' => ['title' => 'Kebijakan Penggunaan', 'visitor_limit_files' => 'Maksimum 100 file per tugas', 'visitor_limit_size' => 'Maksimum 100 MB per tugas', 'google_unlimited' => 'Tanpa batas ukuran dan jumlah file'],
    'tools' => [
        'pdf_to_word' => ['description' => 'Ubah PDF menjadi file Word yang dapat diedit.', 'seo' => 'PDF to Word converter, editable DOCX, konversi PDF online gratis.'],
        'pdf_to_excel' => ['description' => 'Ubah PDF menjadi spreadsheet Excel.', 'seo' => 'PDF to Excel converter, ekstraksi tabel, konversi XLSX online.'],
        'pdf_to_jpg' => ['description' => 'Ubah PDF menjadi gambar JPG/JPEG.', 'seo' => 'PDF to JPG converter, PDF ke gambar, ekspor JPEG.'],
        'compress_pdf' => ['description' => 'Kecilkan ukuran file PDF.', 'seo' => 'Compress PDF online, reduce PDF size, optimize PDF free.'],
        'merge_pdf' => ['description' => 'Gabungkan beberapa PDF menjadi satu.', 'seo' => 'Merge PDF online, combine PDF files, free PDF merger.'],
        'word_to_pdf' => ['description' => 'Ubah dokumen Word menjadi PDF.', 'seo' => 'Word to PDF converter, DOCX to PDF online.'],
        'excel_to_pdf' => ['description' => 'Ubah spreadsheet Excel menjadi PDF.', 'seo' => 'Excel to PDF converter, XLSX to PDF online.'],
        'jpg_to_pdf' => ['description' => 'Ubah gambar JPG/JPEG menjadi PDF.', 'seo' => 'JPG to PDF converter, image to PDF online.'],
    ],
]);
