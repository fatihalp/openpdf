<?php

return array_replace_recursive(require base_path('lang/en/openpdf.php'), [
    'meta' => ['title' => 'ఉచిత PDF కన్వర్టర్ టూల్స్ | ఓపెన్ సోర్స్ PDF సేవ', 'description' => 'PDF, Word, Excel, JPG ఫైళ్లను ఉచితంగా ఆన్‌లైన్‌లో మార్పిడి చేయండి. PDF to Word, PDF to Excel, PDF to JPG, Compress PDF, Merge PDF, Word to PDF, Excel to PDF, JPG to PDF.'],
    'hero' => ['title' => 'అందరికీ ఉచిత PDF మార్పిడి సాధనాలు', 'description' => 'తప్పనిసరి సభ్యత్వం లేదు. సందర్శకులు న్యాయమైన పరిమితులతో అన్ని టూల్స్ వాడవచ్చు. Google సైన్-ఇన్‌తో అన్‌లిమిటెడ్ మోడ్ లభిస్తుంది.'],
    'auth' => ['optional' => 'ఐచ్ఛిక Google సైన్-ఇన్', 'visitor' => 'సందర్శకుడు', 'logout' => 'లాగౌట్'],
    'workspace' => ['title' => 'మార్పిడి వర్క్‌స్పేస్', 'drop_title' => 'ఫైళ్లను ఇక్కడ డ్రాప్ చేయండి లేదా అప్‌లోడ్ కోసం క్లిక్ చేయండి', 'settings' => 'మార్పిడి సెట్టింగ్స్', 'convert' => 'మార్పిడి చేయండి'],
    'limits' => ['title' => 'వినియోగ విధానం', 'visitor_limit_files' => 'ఒక పనికి గరిష్టం 100 ఫైళ్లు', 'visitor_limit_size' => 'ఒక పనికి గరిష్టం 100 MB', 'google_unlimited' => 'ఫైల్ సంఖ్య/పరిమాణం పరిమితి లేదు'],
    'tools' => [
        'pdf_to_word' => ['description' => 'PDF ను సవరించగల Word ఫైల్‌గా మార్చండి.', 'seo' => 'PDF to Word converter, editable DOCX, online PDF conversion free.'],
        'pdf_to_excel' => ['description' => 'PDF ను Excel పట్టికగా మార్చండి.', 'seo' => 'PDF to Excel converter, table extraction, XLSX conversion online.'],
        'pdf_to_jpg' => ['description' => 'PDF ను JPG/JPEG చిత్రాలుగా మార్చండి.', 'seo' => 'PDF to JPG converter, PDF page to image, JPEG export.'],
        'compress_pdf' => ['description' => 'PDF ఫైల్ పరిమాణం తగ్గించండి.', 'seo' => 'Compress PDF online, reduce PDF size, optimize PDF free.'],
        'merge_pdf' => ['description' => 'అనేక PDF ఫైళ్లను ఒకటిగా కలపండి.', 'seo' => 'Merge PDF online, combine PDF files, free PDF merger.'],
        'word_to_pdf' => ['description' => 'Word పత్రాన్ని PDF గా మార్చండి.', 'seo' => 'Word to PDF converter, DOCX to PDF online.'],
        'excel_to_pdf' => ['description' => 'Excel షీటును PDF గా మార్చండి.', 'seo' => 'Excel to PDF converter, XLSX to PDF online.'],
        'jpg_to_pdf' => ['description' => 'JPG/JPEG చిత్రాలను PDF గా మార్చండి.', 'seo' => 'JPG to PDF converter, image to PDF online.'],
    ],
]);
