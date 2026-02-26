<?php

return array_replace_recursive(require base_path('lang/en/openpdf.php'), [
    'meta' => ['title' => '無料PDF変換ツール | オープンソースPDFサービス', 'description' => 'PDF、Word、Excel、JPGを無料でオンライン変換。PDF to Word, PDF to Excel, PDF to JPG, Compress PDF, Merge PDF, Word to PDF, Excel to PDF, JPG to PDF.'],
    'hero' => ['title' => '誰でも使える無料PDF変換ツール', 'description' => '会員登録は必須ではありません。訪問者は公平な制限内で全ツールを利用できます。Googleログインで無制限モードが使えます。'],
    'auth' => ['optional' => 'Googleログイン（任意）', 'visitor' => 'ゲスト', 'logout' => 'ログアウト'],
    'workspace' => ['title' => '変換ワークスペース', 'drop_title' => 'ファイルをドラッグ＆ドロップ、またはクリックしてアップロード', 'settings' => '変換設定', 'convert' => '変換'],
    'limits' => ['title' => '利用ポリシー', 'visitor_limit_files' => '1回の処理で最大100ファイル', 'visitor_limit_size' => '1回の処理で最大100MB', 'google_unlimited' => 'ファイル数・容量ともに無制限'],
    'tools' => [
        'pdf_to_word' => ['description' => 'PDFを編集可能なWordファイルに変換します。', 'seo' => 'PDF to Word converter, editable DOCX, オンラインPDF変換 無料.'],
        'pdf_to_excel' => ['description' => 'PDFをExcelスプレッドシートに変換します。', 'seo' => 'PDF to Excel converter, 表抽出, XLSX conversion online.'],
        'pdf_to_jpg' => ['description' => 'PDFをJPG/JPEG画像に変換します。', 'seo' => 'PDF to JPG converter, PDFページを画像化, JPEG export.'],
        'compress_pdf' => ['description' => 'PDFファイルのサイズを圧縮します。', 'seo' => 'Compress PDF online, PDFサイズ削減, optimize PDF.'],
        'merge_pdf' => ['description' => '複数のPDFを1つに結合します。', 'seo' => 'Merge PDF online, PDF結合, free PDF merger.'],
        'word_to_pdf' => ['description' => 'Word文書をPDFに変換します。', 'seo' => 'Word to PDF converter, DOCX to PDF online.'],
        'excel_to_pdf' => ['description' => 'ExcelシートをPDFに変換します。', 'seo' => 'Excel to PDF converter, XLSX to PDF online.'],
        'jpg_to_pdf' => ['description' => 'JPG/JPEG画像をPDFに変換します。', 'seo' => 'JPG to PDF converter, image to PDF online.'],
    ],
]);
