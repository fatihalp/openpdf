<?php

return array_replace_recursive(require base_path('lang/en/openpdf.php'), [
    'meta' => ['title' => '免费 PDF 转换工具 | 开源 PDF 服务', 'description' => '免费在线转换 PDF、Word、Excel 和 JPG。PDF to Word, PDF to Excel, PDF to JPG, Compress PDF, Merge PDF, Word to PDF, Excel to PDF, JPG to PDF.'],
    'hero' => ['title' => '面向所有人的免费 PDF 转换工具', 'description' => '无需强制注册。访客可在公平限制下使用全部工具。Google 登录用户可使用无限模式。'],
    'auth' => ['optional' => '可选 Google 登录', 'visitor' => '访客', 'logout' => '退出'],
    'workspace' => ['title' => '转换工作区', 'drop_title' => '拖放文件或点击上传', 'settings' => '转换设置', 'convert' => '转换'],
    'limits' => ['title' => '使用政策', 'visitor_limit_files' => '每次任务最多 100 个文件', 'visitor_limit_size' => '每次任务最多 100 MB', 'google_unlimited' => '文件数量与大小不限'],
    'tools' => [
        'pdf_to_word' => ['description' => '将 PDF 转换为可编辑的 Word 文件。', 'seo' => 'PDF 转 Word 转换器，可编辑 DOCX，在线 PDF 转换，免费 PDF 工具。'],
        'pdf_to_excel' => ['description' => '将 PDF 转换为 Excel 表格。', 'seo' => 'PDF 转 Excel 转换器，表格提取，XLSX 在线转换。'],
        'pdf_to_jpg' => ['description' => '将 PDF 转换为 JPG/JPEG 图片。', 'seo' => 'PDF 转 JPG 转换器，PDF 页面转图片，JPEG 导出。'],
        'compress_pdf' => ['description' => '减小 PDF 文件大小，便于分享。', 'seo' => '在线压缩 PDF，减少 PDF 大小，优化 PDF，免费。'],
        'merge_pdf' => ['description' => '将多个 PDF 合并为一个文件。', 'seo' => '在线合并 PDF，组合 PDF 文件，免费 PDF 合并工具。'],
        'word_to_pdf' => ['description' => '将 Word 文档转换为 PDF。', 'seo' => 'Word 转 PDF，DOCX 转 PDF 在线，文档导出 PDF。'],
        'excel_to_pdf' => ['description' => '将 Excel 表格转换为 PDF。', 'seo' => 'Excel 转 PDF，XLSX 转 PDF 在线，表格导出 PDF。'],
        'jpg_to_pdf' => ['description' => '将 JPG/JPEG 图片转换为 PDF。', 'seo' => 'JPG 转 PDF，图片转 PDF 在线，JPEG 转 PDF。'],
    ],
]);
