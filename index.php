<link rel="stylesheet" href="css/index.css">
<?php

function crawler($dominio){
    $dominio = $dominio.'/mapa-site';
    $urls = [];
    foreach($dominio as $dominios){
        $urlFinal = $dominios->getAttribute('href');
        //verifica se o link é absoluto ou relativo e o trate como necessario
        if(strpos($urlFinal, 'https') !== 0){
            $urlFinal = $urlFinal;
        }
        $urls[] = $urlFinal;
    }
    return $urls;
}

require('./title.php');
require('./description.php');
require('./truncado.php');
require('./h1.php');
require('./imagem.php');
require('./link.php');
require('./w3c.php');

//gerando o sitemap
//$sitemap = createSitemap($urls);


//print_r($sitemap);


    //file_put_contents('sitemaps.xml', $sitemap);

?>
<h1>Cloud 2.0</h1>
<form action="" method="post">
    <input type="text" name="url" id="url" placeholder="Digite a URL do projeto"> 
    <input type="submit" value="Validar" name="submit">
</form>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Capture o valor do input
        $url = filter_input(INPUT_POST, 'url', FILTER_SANITIZE_URL);
        //$url = $url.'mapa-site';
        // Valide a URL
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            echo "<h3>A URL informada é: " . htmlspecialchars($url) . "</h3>";

            // Obtenha o conteúdo da página
            $htmlContent = file_get_contents($url);

            if ($htmlContent !== false) {
                $dom = new DOMDocument();
                @$dom->loadHTML($htmlContent); // O @ suprime os avisos de erros de HTML malformado

                // Encontre todos os links
                $links = $dom->getElementsByTagName('a');
                $linkArray = [];

                foreach ($links as $link) {
                    $href = $link->getAttribute('href');
                    // Adicione ao array se for uma URL válida e não um link âncora ou javascript
                    if (strpos($href, 'mailto:') !== 0 &&                    
                    strpos($href, '#') !== 0 &&
                    strpos($href, 'javascript:') !== 0 &&
                    strpos($href, 'tel:') !== 0 &&
                    strpos($href, 'https://www.instagram.com') !== 0 &&
                    strpos($href, 'https://www.linkedin.com') !== 0 &&
                    strpos($href, 'https://www.facebook.com') !== 0 &&
                    strpos($href, 'https://validator.w3.org') !== 0 &&
                    strpos($href, 'http://validator.w3.org') !== 0 &&
                    strpos($href, 'https://jigsaw.w3.org') !== 0 &&
                    strpos($href, 'http://jigsaw.w3.org') !== 0 &&
                    strpos($href, 'https://api.whatsapp.com') !== 0 &&
                    strpos($href,  $url.'/mapa-site') !== 0 &&
                    strpos($href, 'https://web.whatsapp.com') !== 0 &&
                    strpos($href, 'https://www.youtube.com') !== 0 ) {
                        $linkArray[] = $href;
                    }
                }

                // Remova duplicados
                $uniqueLinks = array_unique($linkArray);
                echo "<h2>Validações Manuais</h2>";
                echo "<h3>Validações da home</h3>";
                echo "<input type='checkbox' name='h1'> O H1 da pagina é formado pelo nome da empresa + segmento?<br>
<input type='checkbox' name='banner'> Os banners linkam para as MPIs?<br>
<input type='checkbox' name='caroussel'> A Home contem ao menos um Caroussel?<br>
<input type='checkbox' name='caroussel'> Existe Self Redirect?<br>
<input type='checkbox' name='caroussel'> Botão Link (formulário)?<br>
<input type='checkbox' name='caroussel'> Botão Link (WhatsApp)?<br>

<input type='checkbox' name='caroussel'> A home contém produtos em destaque?";
echo '<h3></h3>';
                echo "<h2>Validações Automáticas</h2>";
                // Exiba os links únicos
                //echo "<h3>Links encontrados:</h3>";
                echo "<table><thead>
                <tr>
                <th>link</th> <th>title</th><th>Description</th><th>Imagens</th><th>H1</th><th>links</th> <th>W3C</th> </tr></thead><tbody>";
                $descriptionRegistry = [];
                foreach ($uniqueLinks as $uniqueLink) {
                    //print_r($descriptionRegistry);
                    
                    $title = title($uniqueLink);
                    $description = description($uniqueLink, $descriptionRegistry);
                    $imagens = imagem($uniqueLink);
                    $h1 = ValidaH1($uniqueLink);
                    $link = validaLink($uniqueLink, $url);
                    //$validacaoW3C = validateW3c($uniqueLink);
                    $w3c = validaEstrutura($uniqueLink);


                    echo "<tr>
    <td><a target='_blank' href=\"" . htmlspecialchars($uniqueLink) . "\">" . htmlspecialchars($uniqueLink) . "</a></td><td> ".$title." </td><td>".$description." </td><td>";
    if(is_array($imagens)){
        if(count($imagens)>0){
          echo "<p>imagens sem atributo</p> <ul>";
          foreach ($imagens as $image) {
            echo "<li> Imagem: ". htmlspecialchars($image['src']);
            if($image['missingAlt']){
                echo "(sem alt)";
            }
            if($image['missingTitle']){
                echo "(sem Title)";
            }
           
            echo "</li>";
          }
          echo "</ul>";
        }  else{
            echo "todas as imagens aprovadas";
        } 
                }
    echo "</td><td>".$h1."</td>
    <td>";
        if(is_array($link)){
            if(count($link) > 0){
                echo "links sem atributos <ul>";
                foreach($link as $links){
                    echo $links['href'] ."<br>";
                    if($links["target"]){
                        echo "Link externo sem o atributo target <br>";
                    }
                    if($links["rel"]){
                        echo "link externo sem o atributo rel <br>";
                    }
                    if($links["title"]){
                        echo "reprovado, link sem title <br>";
                    }
                }
                echo "</ul>";
            }else{
                echo "Todos os links aprovados.";
            }
        }
    echo "</td> <td>".$w3c."</td> </tr>";
                }
                echo "</tbody>
</table>";
            } else {
                echo "Não foi possível obter o conteúdo da página.";
            }
        } else {
            echo "URL inválida.";
        }
    }
    
    
    






    ?>
