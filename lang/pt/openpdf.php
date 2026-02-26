<?php

return array_replace_recursive(require base_path('lang/en/openpdf.php'), [
    'meta' => ['title' => 'Ferramentas gratuitas de conversão PDF | Serviço PDF open source', 'description' => 'Converta PDF, Word, Excel e JPG online gratuitamente. PDF to Word, PDF to Excel, PDF to JPG, Compress PDF, Merge PDF, Word to PDF, Excel to PDF, JPG to PDF.'],
    'hero' => ['title' => 'Ferramentas gratuitas de conversão PDF para todos', 'description' => 'Sem associação obrigatória. Visitantes usam todas as ferramentas com limites justos. Login Google ativa modo ilimitado.'],
    'auth' => ['optional' => 'Login Google opcional', 'visitor' => 'Visitante', 'logout' => 'Sair'],
    'workspace' => ['title' => 'Área de conversão', 'drop_title' => 'Solte arquivos ou clique para enviar', 'settings' => 'Configurações de conversão', 'convert' => 'Converter'],
    'limits' => ['title' => 'Política de uso', 'visitor_limit_files' => 'Máximo de 100 arquivos por tarefa', 'visitor_limit_size' => 'Máximo de 100 MB por tarefa', 'google_unlimited' => 'Sem limite de quantidade e tamanho'],
    'tools' => [
        'pdf_to_word' => ['description' => 'Converta arquivos PDF para Word editável.', 'seo' => 'conversor PDF para Word, DOCX editável, conversão PDF online grátis.'],
        'pdf_to_excel' => ['description' => 'Converta arquivos PDF para planilhas Excel.', 'seo' => 'conversor PDF para Excel, extração de tabelas, conversão XLSX online.'],
        'pdf_to_jpg' => ['description' => 'Converta arquivos PDF para imagens JPG/JPEG.', 'seo' => 'conversor PDF para JPG, PDF para imagem, exportação JPEG.'],
        'compress_pdf' => ['description' => 'Reduza o tamanho de arquivos PDF.', 'seo' => 'comprimir PDF online, reduzir tamanho PDF, otimizar PDF grátis.'],
        'merge_pdf' => ['description' => 'Combine vários PDFs em um único arquivo.', 'seo' => 'juntar PDF online, combinar arquivos PDF, merge PDF grátis.'],
        'word_to_pdf' => ['description' => 'Converta documentos Word para PDF.', 'seo' => 'Word para PDF, DOCX para PDF online, converter documento em PDF.'],
        'excel_to_pdf' => ['description' => 'Converta planilhas Excel para PDF.', 'seo' => 'Excel para PDF, XLSX para PDF online, exportar planilha para PDF.'],
        'jpg_to_pdf' => ['description' => 'Converta imagens JPG/JPEG para PDF.', 'seo' => 'JPG para PDF, imagem para PDF online, converter JPEG para PDF.'],
    ],
]);
