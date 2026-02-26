<?php

return array_replace_recursive(require base_path('lang/en/openpdf.php'), [
    'meta' => ['title' => 'Kostenlose PDF-Konverter Tools | Open-Source PDF Dienst', 'description' => 'PDF, Word, Excel und JPG kostenlos online konvertieren. PDF to Word, PDF to Excel, PDF to JPG, Compress PDF, Merge PDF, Word to PDF, Excel to PDF, JPG to PDF.'],
    'hero' => ['title' => 'Kostenlose PDF-Konvertierung für alle', 'description' => 'Keine Pflichtmitgliedschaft. Besucher nutzen alle Tools mit fairen Limits. Google-Anmeldung aktiviert unbegrenzten Modus.'],
    'auth' => ['optional' => 'Optionale Google-Anmeldung', 'visitor' => 'Besucher', 'logout' => 'Abmelden'],
    'workspace' => ['title' => 'Konvertierungsbereich', 'drop_title' => 'Dateien ablegen oder klicken zum Hochladen', 'settings' => 'Konvertierungseinstellungen', 'convert' => 'Konvertieren'],
    'limits' => ['title' => 'Nutzungsrichtlinie', 'visitor_limit_files' => 'Maximal 100 Dateien pro Aufgabe', 'visitor_limit_size' => 'Maximal 100 MB pro Aufgabe', 'google_unlimited' => 'Unbegrenzt bei Dateianzahl und Größe'],
    'tools' => [
        'pdf_to_word' => ['description' => 'PDF-Dateien in bearbeitbare Word-Dateien konvertieren.', 'seo' => 'PDF zu Word Konverter, bearbeitbares DOCX, kostenlose Online PDF Konvertierung.'],
        'pdf_to_excel' => ['description' => 'PDF-Dateien in Excel-Tabellen konvertieren.', 'seo' => 'PDF zu Excel Konverter, Tabellenextraktion, XLSX Online Konvertierung.'],
        'pdf_to_jpg' => ['description' => 'PDF-Dateien in JPG/JPEG-Bilder konvertieren.', 'seo' => 'PDF zu JPG Konverter, PDF Seite als Bild, JPEG Export.'],
        'compress_pdf' => ['description' => 'PDF-Dateigröße für schnelleren Versand reduzieren.', 'seo' => 'PDF komprimieren online, PDF Größe reduzieren, PDF optimieren kostenlos.'],
        'merge_pdf' => ['description' => 'Mehrere PDF-Dateien zu einer Datei zusammenführen.', 'seo' => 'PDF zusammenführen online, PDF Dateien kombinieren, kostenloser PDF Merger.'],
        'word_to_pdf' => ['description' => 'Word-Dokumente in PDF konvertieren.', 'seo' => 'Word zu PDF, DOCX zu PDF online, Dokument in PDF exportieren.'],
        'excel_to_pdf' => ['description' => 'Excel-Tabellen in PDF konvertieren.', 'seo' => 'Excel zu PDF, XLSX zu PDF online, Tabelle als PDF exportieren.'],
        'jpg_to_pdf' => ['description' => 'JPG/JPEG-Bilder in PDF konvertieren.', 'seo' => 'JPG zu PDF, Bild zu PDF online, JPEG zu PDF umwandeln.'],
    ],
]);
