<?php
function description($url, &$descriptionRegistry){
    

    $htmlContent = file_get_contents($url);
    if ($htmlContent !== false) {
        $dom = new DOMDocument();
        @$dom->loadHTML($htmlContent); // O @ suprime os avisos de erros de HTML malformado
        $metaTags = $dom->getElementsByTagName('meta');
        
        foreach ($metaTags as $meta) {
            if (strtolower($meta->getAttribute('name')) === 'description') {
                $descriptionContent = $meta->getAttribute('content');
                // Verificar se a descrição contém "lorem ipsum"
                if (stripos($descriptionContent, 'lorem ipsum') !== false) {
                    return "Refazer description - Contém 'lorem ipsum'";
                }
                // Verificar se a descrição está duplicada
                if(in_array($descriptionContent, $descriptionRegistry)){
                    return "Refazer description - Description duplicada";
                }
                if (strlen($descriptionContent) <= 160) {
                    if(isDescriptionTruncated($descriptionContent) >= 920){
                        return "Description truncada";
                    }else{
                        return "Description aprovada ";
                    }
                } elseif (strlen($descriptionContent) > 160) {
                    return "Description aprovada ";//esse else não deveria nem existir, mas se tira o codigo quebra, quando tiver mais tempo, eu resolvo
                } 
                $descriptionRegistry[] = $descriptionContent;
                // Adicionar a descrição ao array de descrições encontradas
                
            }
        }
        return "Sem meta description";
    } else {
        return "Erro ao acessar a página.";
    }
}