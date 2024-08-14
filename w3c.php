<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<?php
function validaEstrutura($url){
    $htmlContent = file_get_contents($url);
    if($htmlContent === false){
        return "Erro ao acessar a pagina";
    }
// 
    $dom = new DOMDocument();
    @$dom->loadHTML($htmlContent);
    $errors = [];
// 
    $htmlContent = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $htmlContent);
    $htmlContent = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $htmlContent);
    //$htmlContent = preg_replace('/<base\b[^>]*>(.*?)/is', '', $htmlContent);
    $htmlContent = preg_replace('/<!--.*?-->/s', '', $htmlContent);
// 
    if(stripos($htmlContent, '<!DOCTYPE html>') === false){
        $errors[] = "Doctype ausente";
    }
// 
    $html = $dom->getElementsByTagName('html');
    if($html->length === 0){
        $errors[] = "tag html ausente";
    }
// 
    $head = $dom->getElementsByTagName('head');
    if($head->length === 0){
        $errors[] = "tag head ausente";
    }
// 
    $body = $dom->getElementsByTagName('body');
    if($body->length === 0){
        $errors[] = "tag body ausente";
    }
// 
$listaDuplicado = [];
$allElements = $dom->getElementsByTagName('*');
foreach($allElements as $element){
    if($element->hasAttribute('id')){
        $id = $element->getAttribute('id');
        if(array_key_exists($id, $listaDuplicado)){
            $errors[] = "ID duplicado encontrado #" . $id;
        }else{
            $listaDuplicado[$id] = true;
        }
    }
}
    // Verificar se existem tags mal formadas (sem fechamento)
    preg_match_all('/<([a-zA-Z0-9]+)([^<]*)?(?<!\/)>/', $htmlContent, $matches);
    //var_dump($matches[3]);
    foreach($matches[1] as $tag){
        if (!preg_match('/<\/' . $tag . '>/i', $htmlContent) && !in_array($tag, ['br', 'hr', 'img', 'input', 'meta', 'link', 'base'])) {
            var_dump($tag);
            $errors[] = "Tag <".$tag."> não fechada.";
        }

    }
    $ulElements = $dom->getElementsByTagName('ul');
    foreach ($ulElements as $ul) {
        foreach ($ul->childNodes as $child) {
            if ($child->nodeType === XML_ELEMENT_NODE && $child->nodeName !== 'li') {
                $errors[] = "<ul> contém filho que não é <li>: <" . $child->nodeName . ">";
            }
        }
    }
// 
    $olElements = $dom->getElementsByTagName('ol');
    foreach ($olElements as $ol) {
        foreach ($ol->childNodes as $child) {
            if ($child->nodeType === XML_ELEMENT_NODE && $child->nodeName !== 'li') {
                $errors[] = "<ol> contém filho que não é <li>: <" . $child->nodeName . ">";
            }
        }
    }
   
    if (empty($errors)) {
        return "aprovado";
    } else {
        //var_dump($errors);
        return "reprovado - " . implode(", ", $errors);
    }
}
// 