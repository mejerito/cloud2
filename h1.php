<?php
function ValidaH1($url){
    $htmlContent = file_get_contents($url);
    if($htmlContent !== false){
        $dom = new DOMDocument();
        @$dom->loadHTML($htmlContent); // O @ suprime os avisos de erros de HTML malformado
        $h1 = $dom->getElementsByTagName('h1');
        
        if(is_array($h1)){
            return "reprovado - h1 duplicado na mesma pagina";
        }elseif($h1->length == 0){
            return "reprovado - pagina sem h1";
        
        }elseif(($h1->item(0)->nodeValue == "")){
            return "reprovado - h1 vazio";
        
        }elseif(strlen($h1->item(0)->nodeValue) !== 0){
            return "aprovado";
        }else{
            return "reprovado - sem description";
        }
    }else {
        return "Erro ao acessar a página.";
    }
}