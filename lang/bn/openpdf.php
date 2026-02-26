<?php

return array_replace_recursive(require base_path('lang/en/openpdf.php'), [
    'meta' => ['title' => 'ফ্রি PDF কনভার্টার টুলস | ওপেন সোর্স PDF সার্ভিস', 'description' => 'PDF, Word, Excel এবং JPG ফাইল অনলাইনে ফ্রি কনভার্ট করুন। PDF to Word, PDF to Excel, PDF to JPG, Compress PDF, Merge PDF, Word to PDF, Excel to PDF, JPG to PDF.'],
    'hero' => ['title' => 'সবার জন্য ফ্রি PDF কনভার্সন টুলস', 'description' => 'বাধ্যতামূলক সদস্যতা নেই। ভিজিটররা ন্যায্য সীমার মধ্যে সব টুল ব্যবহার করতে পারে। Google সাইন-ইন করলে আনলিমিটেড মোড পাওয়া যায়।'],
    'auth' => ['optional' => 'ঐচ্ছিক Google সাইন-ইন', 'visitor' => 'ভিজিটর', 'logout' => 'লগআউট'],
    'workspace' => ['title' => 'কনভার্সন ওয়ার্কস্পেস', 'drop_title' => 'ফাইল ড্রপ করুন বা আপলোডে ক্লিক করুন', 'settings' => 'কনভার্সন সেটিংস', 'convert' => 'কনভার্ট করুন'],
    'limits' => ['title' => 'ব্যবহার নীতি', 'visitor_limit_files' => 'প্রতি টাস্কে সর্বোচ্চ ১০০ ফাইল', 'visitor_limit_size' => 'প্রতি টাস্কে সর্বোচ্চ ১০০ MB', 'google_unlimited' => 'ফাইল সংখ্যা ও সাইজ সীমাহীন'],
    'tools' => [
        'pdf_to_word' => ['description' => 'PDF ফাইলকে এডিটেবল Word ফাইলে রূপান্তর করুন।', 'seo' => 'PDF to Word কনভার্টার, editable DOCX, free online PDF conversion.'],
        'pdf_to_excel' => ['description' => 'PDF ফাইলকে Excel শিটে রূপান্তর করুন।', 'seo' => 'PDF to Excel converter, table extraction, XLSX conversion online.'],
        'pdf_to_jpg' => ['description' => 'PDF ফাইলকে JPG/JPEG ছবিতে রূপান্তর করুন।', 'seo' => 'PDF to JPG converter, PDF page to image, JPEG export.'],
        'compress_pdf' => ['description' => 'PDF ফাইলের সাইজ কমান।', 'seo' => 'Compress PDF online, reduce PDF size, optimize PDF free.'],
        'merge_pdf' => ['description' => 'একাধিক PDF ফাইল একটিতে একত্র করুন।', 'seo' => 'Merge PDF online, combine PDF files, free PDF merger.'],
        'word_to_pdf' => ['description' => 'Word ডকুমেন্টকে PDF এ রূপান্তর করুন।', 'seo' => 'Word to PDF converter, DOCX to PDF online.'],
        'excel_to_pdf' => ['description' => 'Excel শিটকে PDF এ রূপান্তর করুন।', 'seo' => 'Excel to PDF converter, XLSX to PDF online.'],
        'jpg_to_pdf' => ['description' => 'JPG/JPEG ছবিকে PDF এ রূপান্তর করুন।', 'seo' => 'JPG to PDF converter, image to PDF online.'],
    ],
]);
