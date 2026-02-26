<?php

return array_replace_recursive(require base_path('lang/en/openpdf.php'), [
    'meta' => ['title' => 'Công cụ chuyển đổi PDF miễn phí | Dịch vụ PDF mã nguồn mở', 'description' => 'Chuyển đổi PDF, Word, Excel và JPG miễn phí trực tuyến. PDF to Word, PDF to Excel, PDF to JPG, Compress PDF, Merge PDF, Word to PDF, Excel to PDF, JPG to PDF.'],
    'hero' => ['title' => 'Công cụ chuyển đổi PDF miễn phí cho mọi người', 'description' => 'Không bắt buộc đăng ký thành viên. Khách truy cập dùng tất cả công cụ với giới hạn hợp lý. Đăng nhập Google sẽ mở chế độ không giới hạn.'],
    'auth' => ['optional' => 'Đăng nhập Google (tùy chọn)', 'visitor' => 'Khách', 'logout' => 'Đăng xuất'],
    'workspace' => ['title' => 'Không gian chuyển đổi', 'drop_title' => 'Kéo thả tệp hoặc bấm để tải lên', 'settings' => 'Cài đặt chuyển đổi', 'convert' => 'Chuyển đổi'],
    'limits' => ['title' => 'Chính sách sử dụng', 'visitor_limit_files' => 'Tối đa 100 tệp mỗi tác vụ', 'visitor_limit_size' => 'Tối đa 100 MB mỗi tác vụ', 'google_unlimited' => 'Không giới hạn số lượng và dung lượng tệp'],
    'tools' => [
        'pdf_to_word' => ['description' => 'Chuyển PDF sang file Word có thể chỉnh sửa.', 'seo' => 'PDF to Word converter, editable DOCX, chuyển đổi PDF trực tuyến miễn phí.'],
        'pdf_to_excel' => ['description' => 'Chuyển PDF sang bảng tính Excel.', 'seo' => 'PDF to Excel converter, trích xuất bảng, XLSX conversion online.'],
        'pdf_to_jpg' => ['description' => 'Chuyển PDF sang ảnh JPG/JPEG.', 'seo' => 'PDF to JPG converter, PDF page to image, JPEG export.'],
        'compress_pdf' => ['description' => 'Giảm dung lượng tệp PDF.', 'seo' => 'Compress PDF online, giảm kích thước PDF, optimize PDF free.'],
        'merge_pdf' => ['description' => 'Gộp nhiều tệp PDF thành một.', 'seo' => 'Merge PDF online, combine PDF files, free PDF merger.'],
        'word_to_pdf' => ['description' => 'Chuyển tài liệu Word sang PDF.', 'seo' => 'Word to PDF converter, DOCX to PDF online.'],
        'excel_to_pdf' => ['description' => 'Chuyển bảng Excel sang PDF.', 'seo' => 'Excel to PDF converter, XLSX to PDF online.'],
        'jpg_to_pdf' => ['description' => 'Chuyển ảnh JPG/JPEG sang PDF.', 'seo' => 'JPG to PDF converter, image to PDF online.'],
    ],
]);
