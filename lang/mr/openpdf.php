<?php

return array_replace_recursive(require base_path('lang/en/openpdf.php'), [
    'meta' => ['title' => 'मोफत PDF कन्व्हर्टर साधने | ओपन सोर्स PDF सेवा', 'description' => 'PDF, Word, Excel आणि JPG फायली मोफत ऑनलाइन कन्व्हर्ट करा. PDF to Word, PDF to Excel, PDF to JPG, Compress PDF, Merge PDF, Word to PDF, Excel to PDF, JPG to PDF.'],
    'hero' => ['title' => 'सर्वांसाठी मोफत PDF रूपांतरण साधने', 'description' => 'अनिवार्य सदस्यता नाही. अभ्यागत योग्य मर्यादेत सर्व साधने वापरू शकतात. Google साइन-इन केल्यास अमर्याद मोड मिळतो.'],
    'auth' => ['optional' => 'पर्यायी Google साइन-इन', 'visitor' => 'अभ्यागत', 'logout' => 'लॉगआउट'],
    'workspace' => ['title' => 'रूपांतरण कार्यक्षेत्र', 'drop_title' => 'फाइल ड्रॅग करा किंवा अपलोडसाठी क्लिक करा', 'settings' => 'रूपांतरण सेटिंग्ज', 'convert' => 'रूपांतरित करा'],
    'limits' => ['title' => 'वापर धोरण', 'visitor_limit_files' => 'प्रति कार्य कमाल 100 फाइल्स', 'visitor_limit_size' => 'प्रति कार्य कमाल 100 MB', 'google_unlimited' => 'फाइल संख्या व आकार अमर्याद'],
    'tools' => [
        'pdf_to_word' => ['description' => 'PDF फाइल Word मध्ये संपादनयोग्य रूपात बदला.', 'seo' => 'PDF to Word converter, editable DOCX, online PDF conversion free.'],
        'pdf_to_excel' => ['description' => 'PDF फाइल Excel शीटमध्ये बदला.', 'seo' => 'PDF to Excel converter, table extraction, XLSX conversion online.'],
        'pdf_to_jpg' => ['description' => 'PDF फाइल JPG/JPEG प्रतिमेत बदला.', 'seo' => 'PDF to JPG converter, PDF page to image, JPEG export.'],
        'compress_pdf' => ['description' => 'PDF फाइलचा आकार कमी करा.', 'seo' => 'Compress PDF online, reduce PDF size, optimize PDF free.'],
        'merge_pdf' => ['description' => 'अनेक PDF फाइल्स एकाच फाइलमध्ये एकत्र करा.', 'seo' => 'Merge PDF online, combine PDF files, free PDF merger.'],
        'word_to_pdf' => ['description' => 'Word दस्तऐवज PDF मध्ये बदला.', 'seo' => 'Word to PDF converter, DOCX to PDF online.'],
        'excel_to_pdf' => ['description' => 'Excel शीट PDF मध्ये बदला.', 'seo' => 'Excel to PDF converter, XLSX to PDF online.'],
        'jpg_to_pdf' => ['description' => 'JPG/JPEG प्रतिमा PDF मध्ये बदला.', 'seo' => 'JPG to PDF converter, image to PDF online.'],
    ],
]);
