<?php

return array_replace_recursive(require base_path('lang/en/openpdf.php'), [
    'meta' => ['title' => '무료 PDF 변환 도구 | 오픈소스 PDF 서비스', 'description' => 'PDF, Word, Excel, JPG 파일을 무료로 온라인 변환하세요. PDF to Word, PDF to Excel, PDF to JPG, Compress PDF, Merge PDF, Word to PDF, Excel to PDF, JPG to PDF.'],
    'hero' => ['title' => '누구나 사용하는 무료 PDF 변환 도구', 'description' => '의무 회원가입이 없습니다. 방문자는 공정한 제한 내에서 모든 도구를 사용할 수 있습니다. Google 로그인 사용자는 무제한 모드를 이용할 수 있습니다.'],
    'auth' => ['optional' => 'Google 로그인(선택)', 'visitor' => '방문자', 'logout' => '로그아웃'],
    'workspace' => ['title' => '변환 작업 공간', 'drop_title' => '파일을 끌어다 놓거나 클릭하여 업로드', 'settings' => '변환 설정', 'convert' => '변환'],
    'limits' => ['title' => '사용 정책', 'visitor_limit_files' => '작업당 최대 100개 파일', 'visitor_limit_size' => '작업당 최대 100MB', 'google_unlimited' => '파일 수/용량 무제한'],
    'tools' => [
        'pdf_to_word' => ['description' => 'PDF 파일을 편집 가능한 Word 파일로 변환합니다.', 'seo' => 'PDF to Word converter, editable DOCX, 무료 온라인 PDF 변환.'],
        'pdf_to_excel' => ['description' => 'PDF 파일을 Excel 스프레드시트로 변환합니다.', 'seo' => 'PDF to Excel converter, 표 추출, XLSX conversion online.'],
        'pdf_to_jpg' => ['description' => 'PDF 파일을 JPG/JPEG 이미지로 변환합니다.', 'seo' => 'PDF to JPG converter, PDF 페이지 이미지 변환, JPEG export.'],
        'compress_pdf' => ['description' => 'PDF 파일 크기를 줄입니다.', 'seo' => 'Compress PDF online, PDF size reduce, optimize PDF free.'],
        'merge_pdf' => ['description' => '여러 PDF 파일을 하나로 병합합니다.', 'seo' => 'Merge PDF online, combine PDF files, free PDF merger.'],
        'word_to_pdf' => ['description' => 'Word 문서를 PDF로 변환합니다.', 'seo' => 'Word to PDF converter, DOCX to PDF online.'],
        'excel_to_pdf' => ['description' => 'Excel 시트를 PDF로 변환합니다.', 'seo' => 'Excel to PDF converter, XLSX to PDF online.'],
        'jpg_to_pdf' => ['description' => 'JPG/JPEG 이미지를 PDF로 변환합니다.', 'seo' => 'JPG to PDF converter, image to PDF online.'],
    ],
]);
