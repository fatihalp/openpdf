<?php

return array_replace_recursive(require base_path('lang/en/openpdf.php'), [
    'meta' => ['title' => 'Outils gratuits de conversion PDF | Service PDF open source', 'description' => 'Convertissez PDF, Word, Excel et JPG gratuitement en ligne. PDF to Word, PDF to Excel, PDF to JPG, Compress PDF, Merge PDF, Word to PDF, Excel to PDF, JPG to PDF.'],
    'hero' => ['title' => 'Outils de conversion PDF gratuits pour tous', 'description' => 'Aucune inscription obligatoire. Les visiteurs utilisent tous les outils avec des limites équitables. Google Sign-In active le mode illimité.'],
    'auth' => ['optional' => 'Connexion Google optionnelle', 'visitor' => 'Visiteur', 'logout' => 'Déconnexion'],
    'workspace' => ['title' => 'Espace de conversion', 'drop_title' => 'Déposez les fichiers ou cliquez pour téléverser', 'settings' => 'Paramètres de conversion', 'convert' => 'Convertir'],
    'limits' => ['title' => 'Politique d’utilisation', 'visitor_limit_files' => 'Maximum 100 fichiers par tâche', 'visitor_limit_size' => 'Maximum 100 MB par tâche', 'google_unlimited' => 'Illimité pour quantité et taille'],
    'tools' => [
        'pdf_to_word' => ['description' => 'Convertissez des PDF en fichiers Word éditables.', 'seo' => 'convertisseur PDF vers Word, DOCX modifiable, conversion PDF en ligne gratuite.'],
        'pdf_to_excel' => ['description' => 'Convertissez des PDF en feuilles Excel.', 'seo' => 'convertisseur PDF vers Excel, extraction de tableaux, XLSX en ligne.'],
        'pdf_to_jpg' => ['description' => 'Convertissez des PDF en images JPG/JPEG.', 'seo' => 'convertisseur PDF vers JPG, PDF vers image, export JPEG.'],
        'compress_pdf' => ['description' => 'Réduisez la taille des fichiers PDF.', 'seo' => 'compresser PDF en ligne, réduire taille PDF, optimiser PDF gratuit.'],
        'merge_pdf' => ['description' => 'Fusionnez plusieurs fichiers PDF en un seul.', 'seo' => 'fusionner PDF en ligne, combiner PDF, merger PDF gratuit.'],
        'word_to_pdf' => ['description' => 'Convertissez des documents Word en PDF.', 'seo' => 'Word vers PDF, DOCX vers PDF en ligne, conversion document PDF.'],
        'excel_to_pdf' => ['description' => 'Convertissez des feuilles Excel en PDF.', 'seo' => 'Excel vers PDF, XLSX vers PDF en ligne, export tableau PDF.'],
        'jpg_to_pdf' => ['description' => 'Convertissez des images JPG/JPEG en PDF.', 'seo' => 'JPG vers PDF, image vers PDF en ligne, convertir JPEG en PDF.'],
    ],
]);
