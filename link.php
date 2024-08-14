<?php

function validaLink($url, $home){
    $htmlContent = file_get_contents($url);
    if($htmlContent !== false){
        $dom = new DOMDocument();
        @$dom->loadHTML($htmlContent); // O @ suprime os avisos de erros de HTML malformado
        $links = $dom->getElementsByTagName('a');
        $missingAttributes = [];
        foreach ($links as $link) {
            $href = $link->getAttribute('href');
            $title = $link->getAttribute('title');
            $target = $link->getAttribute('target');
            $rel = $link->getAttribute('rel');
            if(strpos($link ->getAttribute('href'), $home) !== 0){
                if (strpos($href, 'mailto:') !== 0 &&                    
                    strpos($href, '#') !== 0 &&
                    strpos($href, 'javascript:') !== 0 &&
                    strpos($href, 'tel:') !== 0){

                        if((empty($target))|| (empty($rel)) || (empty($title))){
                            $missingAttributes[] = [
                                'href' => $href,
                                'target' => empty($target),
                                'rel' => empty($rel),
                                'title' =>empty($title)
                            ];
                        }
                    }
                
            }
        }
        return $missingAttributes;
    }else {
        return "Erro ao acessar a página.";
    }
}