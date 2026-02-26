<?php

return array_replace_recursive(require base_path('lang/en/openpdf.php'), [
    'meta' => ['title' => 'أدوات تحويل PDF المجانية | خدمة PDF مفتوحة المصدر', 'description' => 'حوّل ملفات PDF وWord وExcel وJPG مجاناً عبر الإنترنت. PDF to Word, PDF to Excel, PDF to JPG, Compress PDF, Merge PDF, Word to PDF, Excel to PDF, JPG to PDF.'],
    'hero' => ['title' => 'أدوات تحويل PDF مجانية للجميع', 'description' => 'لا توجد عضوية إلزامية. يمكن للزوار استخدام جميع الأدوات بحدود عادلة. تسجيل الدخول عبر Google يفعّل الوضع غير المحدود.'],
    'auth' => ['optional' => 'تسجيل دخول Google اختياري', 'visitor' => 'زائر', 'logout' => 'تسجيل خروج'],
    'workspace' => ['title' => 'مساحة التحويل', 'drop_title' => 'اسحب الملفات هنا أو انقر للرفع', 'settings' => 'إعدادات التحويل', 'convert' => 'تحويل'],
    'limits' => ['title' => 'سياسة الاستخدام', 'visitor_limit_files' => 'حد أقصى 100 ملف لكل عملية', 'visitor_limit_size' => 'حد أقصى 100 ميغابايت لكل عملية', 'google_unlimited' => 'بدون حدود للحجم أو العدد'],
    'tools' => [
        'pdf_to_word' => ['description' => 'حوّل ملفات PDF إلى Word قابل للتعديل.', 'seo' => 'محول PDF إلى Word، DOCX قابل للتحرير، تحويل PDF مجاني عبر الإنترنت.'],
        'pdf_to_excel' => ['description' => 'حوّل ملفات PDF إلى جداول Excel.', 'seo' => 'محول PDF إلى Excel، استخراج الجداول، تحويل XLSX عبر الإنترنت.'],
        'pdf_to_jpg' => ['description' => 'حوّل ملفات PDF إلى صور JPG/JPEG.', 'seo' => 'محول PDF إلى JPG، تحويل صفحات PDF إلى صور، تصدير JPEG.'],
        'compress_pdf' => ['description' => 'قلّل حجم ملف PDF للمشاركة السريعة.', 'seo' => 'ضغط PDF عبر الإنترنت، تقليل حجم PDF، تحسين PDF مجاناً.'],
        'merge_pdf' => ['description' => 'ادمج عدة ملفات PDF في ملف واحد.', 'seo' => 'دمج PDF عبر الإنترنت، جمع ملفات PDF، أداة دمج PDF مجانية.'],
        'word_to_pdf' => ['description' => 'حوّل مستندات Word إلى PDF.', 'seo' => 'Word إلى PDF، DOCX إلى PDF عبر الإنترنت، تحويل المستند إلى PDF.'],
        'excel_to_pdf' => ['description' => 'حوّل جداول Excel إلى PDF.', 'seo' => 'Excel إلى PDF، XLSX إلى PDF عبر الإنترنت، تصدير الجدول إلى PDF.'],
        'jpg_to_pdf' => ['description' => 'حوّل صور JPG/JPEG إلى PDF.', 'seo' => 'JPG إلى PDF، صورة إلى PDF عبر الإنترنت، تحويل JPEG إلى PDF.'],
    ],
]);
