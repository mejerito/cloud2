<?php

function imagem($url){
    $htmlContent = file_get_contents($url);
    if($htmlContent !== false){
        $dom = new DOMDocument();
        @$dom->loadHTML($htmlContent); // O @ suprime os avisos de erros de HTML malformado
        $imagens = $dom->getElementsByTagName('img');
        
        $missingAttributes = [];
        foreach ($imagens as $image) {
            //echo "imagem: ".$image->getAttribute('src'). " alt ".$image->getAttribute('alt'). " title ".$image->getAttribute('title')."<br>";
            $src = $image->getAttribute('src');
            $alt = $image->getAttribute('alt');
            $title = $image->getAttribute('title');
            if(empty($alt) || empty($title)){
                $missingAttributes[] = [
                    'src' => $src,
                    'missingAlt' => empty($alt),
                    'misingTitle' => empty($title)
                ];
            }
        }
        return $missingAttributes;
    }else {
        return "Erro ao acessar a página.";
    }
}