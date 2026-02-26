<?php

return array_replace_recursive(require base_path('lang/en/openpdf.php'), [
    'meta' => ['title' => 'Herramientas gratuitas de conversión PDF | Servicio PDF de código abierto', 'description' => 'Convierte PDF, Word, Excel y JPG gratis en línea. PDF to Word, PDF to Excel, PDF to JPG, Compress PDF, Merge PDF, Word to PDF, Excel to PDF, JPG to PDF.'],
    'hero' => ['title' => 'Herramientas gratuitas de conversión PDF para todos', 'description' => 'Sin membresía obligatoria. Los visitantes usan todas las herramientas con límites justos. Con Google Sign-In, uso ilimitado.'],
    'auth' => ['optional' => 'Inicio de sesión opcional con Google', 'visitor' => 'Visitante', 'logout' => 'Salir'],
    'workspace' => ['title' => 'Espacio de conversión', 'drop_title' => 'Suelta archivos o haz clic para subir', 'settings' => 'Configuración de conversión', 'convert' => 'Convertir'],
    'limits' => ['title' => 'Política de uso', 'visitor_limit_files' => 'Máximo 100 archivos por tarea', 'visitor_limit_size' => 'Máximo 100 MB por tarea', 'google_unlimited' => 'Sin límite de tamaño ni cantidad'],
    'tools' => [
        'pdf_to_word' => ['description' => 'Convierte archivos PDF a Word editable.', 'seo' => 'convertidor PDF a Word, DOCX editable, conversión PDF online gratis.'],
        'pdf_to_excel' => ['description' => 'Convierte archivos PDF a hojas de Excel.', 'seo' => 'convertidor PDF a Excel, extracción de tablas, XLSX online.'],
        'pdf_to_jpg' => ['description' => 'Convierte archivos PDF a imágenes JPG/JPEG.', 'seo' => 'convertidor PDF a JPG, PDF a imagen, exportación JPEG.'],
        'compress_pdf' => ['description' => 'Reduce el tamaño de archivos PDF.', 'seo' => 'comprimir PDF online, reducir tamaño PDF, optimizar PDF gratis.'],
        'merge_pdf' => ['description' => 'Une varios archivos PDF en uno.', 'seo' => 'unir PDF online, combinar archivos PDF, fusionar PDF gratis.'],
        'word_to_pdf' => ['description' => 'Convierte documentos Word a PDF.', 'seo' => 'Word a PDF, DOCX a PDF online, convertir documentos a PDF.'],
        'excel_to_pdf' => ['description' => 'Convierte hojas de Excel a PDF.', 'seo' => 'Excel a PDF, XLSX a PDF online, exportar hoja a PDF.'],
        'jpg_to_pdf' => ['description' => 'Convierte imágenes JPG/JPEG a PDF.', 'seo' => 'JPG a PDF, imagen a PDF online, convertir JPEG a PDF.'],
    ],
]);
