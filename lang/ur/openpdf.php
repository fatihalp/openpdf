<?php

return array_replace_recursive(require base_path('lang/en/openpdf.php'), [
    'meta' => ['title' => 'مفت PDF کنورٹر ٹولز | اوپن سورس PDF سروس', 'description' => 'PDF، Word، Excel اور JPG فائلیں مفت آن لائن کنورٹ کریں۔ PDF to Word, PDF to Excel, PDF to JPG, Compress PDF, Merge PDF, Word to PDF, Excel to PDF, JPG to PDF.'],
    'hero' => ['title' => 'سب کے لیے مفت PDF کنورژن ٹولز', 'description' => 'لازمی رکنیت نہیں۔ وزیٹر مناسب حدود کے ساتھ تمام ٹولز استعمال کر سکتے ہیں۔ Google سائن اِن سے لا محدود موڈ ملتا ہے۔'],
    'auth' => ['optional' => 'اختیاری Google سائن اِن', 'visitor' => 'وزیٹر', 'logout' => 'لاگ آؤٹ'],
    'workspace' => ['title' => 'کنورژن ورک اسپیس', 'drop_title' => 'فائل یہاں ڈراپ کریں یا اپلوڈ کے لیے کلک کریں', 'settings' => 'کنورژن سیٹنگز', 'convert' => 'کنورٹ کریں'],
    'limits' => ['title' => 'استعمال کی پالیسی', 'visitor_limit_files' => 'فی ٹاسک زیادہ سے زیادہ 100 فائلیں', 'visitor_limit_size' => 'فی ٹاسک زیادہ سے زیادہ 100 MB', 'google_unlimited' => 'فائل تعداد اور سائز لا محدود'],
    'tools' => [
        'pdf_to_word' => ['description' => 'PDF فائل کو قابلِ تدوین Word فائل میں تبدیل کریں۔', 'seo' => 'PDF to Word converter, editable DOCX, online PDF conversion.'],
        'pdf_to_excel' => ['description' => 'PDF فائل کو Excel شیٹ میں تبدیل کریں۔', 'seo' => 'PDF to Excel converter, table extraction, XLSX conversion.'],
        'pdf_to_jpg' => ['description' => 'PDF فائل کو JPG/JPEG تصویر میں تبدیل کریں۔', 'seo' => 'PDF to JPG converter, PDF page to image, JPEG export.'],
        'compress_pdf' => ['description' => 'PDF فائل کا سائز کم کریں۔', 'seo' => 'Compress PDF online, reduce PDF size, optimize PDF.'],
        'merge_pdf' => ['description' => 'متعدد PDF فائلوں کو ایک میں ضم کریں۔', 'seo' => 'Merge PDF online, combine PDF files, free PDF merger.'],
        'word_to_pdf' => ['description' => 'Word دستاویز کو PDF میں تبدیل کریں۔', 'seo' => 'Word to PDF converter, DOCX to PDF online.'],
        'excel_to_pdf' => ['description' => 'Excel شیٹ کو PDF میں تبدیل کریں۔', 'seo' => 'Excel to PDF converter, XLSX to PDF online.'],
        'jpg_to_pdf' => ['description' => 'JPG/JPEG تصویر کو PDF میں تبدیل کریں۔', 'seo' => 'JPG to PDF converter, image to PDF online.'],
    ],
]);
