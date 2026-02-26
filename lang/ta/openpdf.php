<?php

return array_replace_recursive(require base_path('lang/en/openpdf.php'), [
    'meta' => ['title' => 'இலவச PDF மாற்று கருவிகள் | திறந்த மூல PDF சேவை', 'description' => 'PDF, Word, Excel மற்றும் JPG கோப்புகளை இலவசமாக ஆன்லைனில் மாற்றுங்கள். PDF to Word, PDF to Excel, PDF to JPG, Compress PDF, Merge PDF, Word to PDF, Excel to PDF, JPG to PDF.'],
    'hero' => ['title' => 'அனைவருக்கும் இலவச PDF மாற்று கருவிகள்', 'description' => 'கட்டாய உறுப்பினர் பதிவு இல்லை. பார்வையாளர்கள் நியாயமான வரம்புகளுடன் அனைத்து கருவிகளையும் பயன்படுத்தலாம். Google உள்நுழைவு பயனர்களுக்கு வரம்பில்லா முறை கிடைக்கும்.'],
    'auth' => ['optional' => 'விருப்பமான Google உள்நுழைவு', 'visitor' => 'பார்வையாளர்', 'logout' => 'வெளியேறு'],
    'workspace' => ['title' => 'மாற்று பணிப்பகம்', 'drop_title' => 'கோப்புகளை இங்கே விடுங்கள் அல்லது பதிவேற்ற கிளிக் செய்யுங்கள்', 'settings' => 'மாற்று அமைப்புகள்', 'convert' => 'மாற்று'],
    'limits' => ['title' => 'பயன்பாட்டு கொள்கை', 'visitor_limit_files' => 'ஒரு பணிக்கு அதிகபட்சம் 100 கோப்புகள்', 'visitor_limit_size' => 'ஒரு பணிக்கு அதிகபட்சம் 100 MB', 'google_unlimited' => 'கோப்பு எண்ணிக்கை மற்றும் அளவு வரம்பில்லை'],
    'tools' => [
        'pdf_to_word' => ['description' => 'PDF கோப்புகளை திருத்தக்கூடிய Word கோப்புகளாக மாற்றுங்கள்.', 'seo' => 'PDF to Word converter, editable DOCX, online PDF conversion free.'],
        'pdf_to_excel' => ['description' => 'PDF கோப்புகளை Excel அட்டவணைகளாக மாற்றுங்கள்.', 'seo' => 'PDF to Excel converter, table extraction, XLSX conversion online.'],
        'pdf_to_jpg' => ['description' => 'PDF கோப்புகளை JPG/JPEG படங்களாக மாற்றுங்கள்.', 'seo' => 'PDF to JPG converter, PDF page to image, JPEG export.'],
        'compress_pdf' => ['description' => 'PDF கோப்பு அளவை குறைக்கவும்.', 'seo' => 'Compress PDF online, reduce PDF size, optimize PDF free.'],
        'merge_pdf' => ['description' => 'பல PDF கோப்புகளை ஒன்றாக இணைக்கவும்.', 'seo' => 'Merge PDF online, combine PDF files, free PDF merger.'],
        'word_to_pdf' => ['description' => 'Word ஆவணங்களை PDF ஆக மாற்றுங்கள்.', 'seo' => 'Word to PDF converter, DOCX to PDF online.'],
        'excel_to_pdf' => ['description' => 'Excel தாள்களை PDF ஆக மாற்றுங்கள்.', 'seo' => 'Excel to PDF converter, XLSX to PDF online.'],
        'jpg_to_pdf' => ['description' => 'JPG/JPEG படங்களை PDF ஆக மாற்றுங்கள்.', 'seo' => 'JPG to PDF converter, image to PDF online.'],
    ],
]);
