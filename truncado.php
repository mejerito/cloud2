<?php
function isDescriptionTruncated($description) {
    // Média de pixels por caractere (com base em uma média de fontes comuns usadas por navegadores)
    $averagePixelWidth = [
        'a' => 7, 'b' => 7, 'c' => 7, 'd' => 7, 'e' => 7, 'f' => 4, 'g' => 7, 'h' => 7, 'i' => 3, 'j' => 4,
        'k' => 6, 'l' => 3, 'm' => 10, 'n' => 7, 'o' => 7, 'p' => 7, 'q' => 7, 'r' => 4, 's' => 7, 't' => 4,
        'u' => 7, 'v' => 6, 'w' => 9, 'x' => 6, 'y' => 7, 'z' => 6, 'A' => 8, 'B' => 8, 'C' => 8, 'D' => 8,
        'E' => 7, 'F' => 6, 'G' => 9, 'H' => 8, 'I' => 3, 'J' => 5, 'K' => 8, 'L' => 7, 'M' => 10, 'N' => 8,
        'O' => 9, 'P' => 8, 'Q' => 9, 'R' => 8, 'S' => 8, 'T' => 7, 'U' => 8, 'V' => 8, 'W' => 11, 'X' => 8,
        'Y' => 8, 'Z' => 7, ' ' => 4, '.' => 3, ',' => 3, '-' => 4, '_' => 4, ':' => 3, ';' => 3, '!' => 3,
        '?' => 6, '"' => 5, '\'' => 2, '(' => 4, ')' => 4, '[' => 4, ']' => 4, '{' => 5, '}' => 5, '/' => 4,
        '\\' => 4, '|' => 2, '@' => 12, '#' => 8, '$' => 7, '%' => 11, '^' => 6, '&' => 8, '*' => 5, '+' => 7,
        '=' => 7, '~' => 7, '`' => 4, '<' => 7, '>' => 7
    ];

    $pixelLength = 0;
    $maxPixelLength = 920; // Definido como limite para evitar truncamento

    for ($i = 0; $i < strlen($description); $i++) {
        $char = $description[$i];
        $pixelLength += $averagePixelWidth[$char] ?? 7; // Usar 7 como largura padrão para caracteres desconhecidos
    }

    return $pixelLength;// > $maxPixelLength;
}