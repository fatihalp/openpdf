<?php

return array_replace_recursive(require base_path('lang/en/openpdf.php'), [
    'meta' => ['title' => 'Zana za bure za kubadilisha PDF | Huduma ya PDF ya Open Source', 'description' => 'Badilisha PDF, Word, Excel na JPG mtandaoni bila malipo. PDF to Word, PDF to Excel, PDF to JPG, Compress PDF, Merge PDF, Word to PDF, Excel to PDF, JPG to PDF.'],
    'hero' => ['title' => 'Zana za bure za kubadilisha PDF kwa wote', 'description' => 'Hakuna usajili wa lazima. Wageni wanaweza kutumia zana zote kwa mipaka ya haki. Kuingia kwa Google kunatoa matumizi bila kikomo.'],
    'auth' => ['optional' => 'Kuingia kwa Google ni hiari', 'visitor' => 'Mgeni', 'logout' => 'Ondoka'],
    'workspace' => ['title' => 'Eneo la Ubadilishaji', 'drop_title' => 'Buruta faili hapa au bofya kupakia', 'settings' => 'Mipangilio ya Ubadilishaji', 'convert' => 'Badilisha'],
    'limits' => ['title' => 'Sera ya Matumizi', 'visitor_limit_files' => 'Faili 100 kwa kazi moja', 'visitor_limit_size' => '100 MB kwa kazi moja', 'google_unlimited' => 'Bila kikomo cha ukubwa na idadi ya faili'],
    'tools' => [
        'pdf_to_word' => ['description' => 'Badilisha PDF kuwa faili za Word zinazoharirika.', 'seo' => 'PDF to Word converter, editable DOCX, online PDF conversion free.'],
        'pdf_to_excel' => ['description' => 'Badilisha PDF kuwa jedwali la Excel.', 'seo' => 'PDF to Excel converter, table extraction, XLSX conversion online.'],
        'pdf_to_jpg' => ['description' => 'Badilisha PDF kuwa picha za JPG/JPEG.', 'seo' => 'PDF to JPG converter, PDF page to image, JPEG export.'],
        'compress_pdf' => ['description' => 'Punguza ukubwa wa faili za PDF.', 'seo' => 'Compress PDF online, reduce PDF size, optimize PDF free.'],
        'merge_pdf' => ['description' => 'Unganisha faili nyingi za PDF kuwa moja.', 'seo' => 'Merge PDF online, combine PDF files, free PDF merger.'],
        'word_to_pdf' => ['description' => 'Badilisha hati za Word kuwa PDF.', 'seo' => 'Word to PDF converter, DOCX to PDF online.'],
        'excel_to_pdf' => ['description' => 'Badilisha jedwali la Excel kuwa PDF.', 'seo' => 'Excel to PDF converter, XLSX to PDF online.'],
        'jpg_to_pdf' => ['description' => 'Badilisha picha za JPG/JPEG kuwa PDF.', 'seo' => 'JPG to PDF converter, image to PDF online.'],
    ],
]);
