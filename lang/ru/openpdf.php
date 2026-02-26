<?php

return array_replace_recursive(require base_path('lang/en/openpdf.php'), [
    'meta' => ['title' => 'Бесплатные PDF-инструменты | Open Source PDF сервис', 'description' => 'Конвертируйте PDF, Word, Excel и JPG бесплатно онлайн. PDF to Word, PDF to Excel, PDF to JPG, Compress PDF, Merge PDF, Word to PDF, Excel to PDF, JPG to PDF.'],
    'hero' => ['title' => 'Бесплатные инструменты PDF-конвертации для всех', 'description' => 'Регистрация не обязательна. Гости используют все инструменты с честными лимитами. Вход через Google включает безлимитный режим.'],
    'auth' => ['optional' => 'Необязательный вход через Google', 'visitor' => 'Гость', 'logout' => 'Выйти'],
    'workspace' => ['title' => 'Рабочая зона конвертации', 'drop_title' => 'Перетащите файлы или нажмите для загрузки', 'settings' => 'Настройки конвертации', 'convert' => 'Конвертировать'],
    'limits' => ['title' => 'Политика использования', 'visitor_limit_files' => 'Максимум 100 файлов за задачу', 'visitor_limit_size' => 'Максимум 100 MB за задачу', 'google_unlimited' => 'Без ограничений по размеру и количеству'],
    'tools' => [
        'pdf_to_word' => ['description' => 'Преобразуйте PDF в редактируемые файлы Word.', 'seo' => 'PDF в Word конвертер, редактируемый DOCX, онлайн конвертация PDF бесплатно.'],
        'pdf_to_excel' => ['description' => 'Преобразуйте PDF в таблицы Excel.', 'seo' => 'PDF в Excel конвертер, извлечение таблиц, XLSX онлайн.'],
        'pdf_to_jpg' => ['description' => 'Преобразуйте PDF в изображения JPG/JPEG.', 'seo' => 'PDF в JPG конвертер, PDF страница в изображение, JPEG экспорт.'],
        'compress_pdf' => ['description' => 'Уменьшите размер PDF для быстрой отправки.', 'seo' => 'сжать PDF онлайн, уменьшить размер PDF, оптимизация PDF бесплатно.'],
        'merge_pdf' => ['description' => 'Объедините несколько PDF в один документ.', 'seo' => 'объединить PDF онлайн, склеить PDF файлы, бесплатный PDF merger.'],
        'word_to_pdf' => ['description' => 'Преобразуйте документы Word в PDF.', 'seo' => 'Word в PDF, DOCX в PDF онлайн, экспорт документа в PDF.'],
        'excel_to_pdf' => ['description' => 'Преобразуйте таблицы Excel в PDF.', 'seo' => 'Excel в PDF, XLSX в PDF онлайн, экспорт таблицы в PDF.'],
        'jpg_to_pdf' => ['description' => 'Преобразуйте изображения JPG/JPEG в PDF.', 'seo' => 'JPG в PDF, изображение в PDF онлайн, JPEG в PDF конвертер.'],
    ],
]);
