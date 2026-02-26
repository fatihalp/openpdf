<?php

return array_replace_recursive(require base_path('lang/en/openpdf.php'), [
    'meta' => ['title' => 'फ्री PDF कन्वर्टर टूल्स | ओपन सोर्स PDF सेवा', 'description' => 'PDF, Word, Excel और JPG फाइलों को मुफ्त ऑनलाइन कन्वर्ट करें। PDF to Word, PDF to Excel, PDF to JPG, Compress PDF, Merge PDF, Word to PDF, Excel to PDF, JPG to PDF.'],
    'hero' => ['title' => 'सबके लिए मुफ्त PDF कन्वर्ज़न टूल्स', 'description' => 'अनिवार्य सदस्यता नहीं है। विज़िटर सभी टूल्स को उचित सीमाओं के साथ उपयोग कर सकते हैं। Google साइन-इन पर अनलिमिटेड मोड मिलता है।'],
    'auth' => ['optional' => 'वैकल्पिक Google साइन-इन', 'visitor' => 'विज़िटर', 'logout' => 'लॉगआउट'],
    'workspace' => ['title' => 'कन्वर्ज़न वर्कस्पेस', 'drop_title' => 'फाइल छोड़ें या अपलोड के लिए क्लिक करें', 'settings' => 'कन्वर्ज़न सेटिंग्स', 'convert' => 'कन्वर्ट करें'],
    'limits' => ['title' => 'उपयोग नीति', 'visitor_limit_files' => 'प्रति कार्य अधिकतम 100 फाइलें', 'visitor_limit_size' => 'प्रति कार्य अधिकतम 100 MB', 'google_unlimited' => 'फाइल संख्या और आकार अनलिमिटेड'],
    'tools' => [
        'pdf_to_word' => ['description' => 'PDF फाइलों को एडिटेबल Word फाइलों में बदलें।', 'seo' => 'PDF to Word कन्वर्टर, एडिटेबल DOCX, ऑनलाइन PDF कन्वर्ज़न फ्री।'],
        'pdf_to_excel' => ['description' => 'PDF फाइलों को Excel शीट में बदलें।', 'seo' => 'PDF to Excel कन्वर्टर, टेबल एक्सट्रैक्शन, XLSX ऑनलाइन कन्वर्ट।'],
        'pdf_to_jpg' => ['description' => 'PDF फाइलों को JPG/JPEG इमेज में बदलें।', 'seo' => 'PDF to JPG कन्वर्टर, PDF पेज से इमेज, JPEG एक्सपोर्ट।'],
        'compress_pdf' => ['description' => 'PDF का साइज कम करें।', 'seo' => 'Compress PDF online, PDF size reduce, optimize PDF free.'],
        'merge_pdf' => ['description' => 'कई PDF फाइलों को एक में मिलाएं।', 'seo' => 'Merge PDF online, combine PDF files, free PDF merger.'],
        'word_to_pdf' => ['description' => 'Word दस्तावेज़ को PDF में बदलें।', 'seo' => 'Word to PDF converter, DOCX to PDF online, document to PDF.'],
        'excel_to_pdf' => ['description' => 'Excel शीट को PDF में बदलें।', 'seo' => 'Excel to PDF converter, XLSX to PDF online, spreadsheet to PDF.'],
        'jpg_to_pdf' => ['description' => 'JPG/JPEG इमेज को PDF में बदलें।', 'seo' => 'JPG to PDF converter, image to PDF online, JPEG to PDF.'],
    ],
]);
