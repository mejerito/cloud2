<?php
function title($url){
    $htmlContent = file_get_contents($url);
    if ($htmlContent !== false) {
        $dom = new DOMDocument();
        @$dom->loadHTML($htmlContent); // O @ suprime os avisos de erros de HTML malformado
        $titleElements = $dom->getElementsByTagName('title');
        
            if(isDescriptionTruncated($titleElements->item(0)->textContent) < 561){

                return "aprovado";
            }else{
                return "reprovado - title truncado";
            }
        
    } else {
        return "Erro ao acessar a página.";
    }
}