<!DOCTYPE html>
<html>
<head>
    <meta charset="Windows-1251">
    <title>Контрольная работа №2</title>
</head>
<body>
<?php
require "text.php";
$text = mb_convert_encoding($text,'UTF-8', 'CP1251');

define('ParagraphsInPage', 10);

$currentPage = Controller();

$numberParagraphs = substr_count( $text, "\r\n");
$numberOfPages = $numberParagraphs/ParagraphsInPage;

Model($currentPage, $numberOfPages);

$text = &setColor($text);
$content = Color($text, $numberParagraphs);
$content = explode ("\r\n", $content);
//echo $text;
View ($currentPage, $content, $numberOfPages);

computeSize($content, $currentPage);
?>
</body>
</html>
<?php

function Controller (){
    $page = empty($_GET['page']) ? 1 : intval($_GET['page']);
    return $page;
}

function Model ($currentPage, $numberOfPages){
    if ($currentPage <= 0 || $currentPage > $numberOfPages){
        die('Неправильный ввод!');
    }
}

function View ($currentPage, $data, $numberOfPages)
{
    $start = ($currentPage - 1) * ParagraphsInPage;
    $end = $start + ParagraphsInPage;
    for ($i = $start; $i < $end; $i++) {
        echo '<p>' . $data[$i] . '</p>';
    }
    function viewHref($hrefPage)
    {
        echo ' <a href="script2.php?page=' . $hrefPage . '">' . $hrefPage . '</a> ';
    }

    echo '<p>';
    if ($currentPage > 2) viewHref(1);
    if ($currentPage > 3) echo '...';
    if ($currentPage > 1) viewHref($currentPage - 1);
    echo ' ' . $currentPage;
    if ($currentPage < $numberOfPages) viewHref($currentPage + 1);
    if ($currentPage < $numberOfPages - 2) echo '...';
    if ($currentPage < $numberOfPages - 1) viewHref($numberOfPages);
    echo '</p>';
}

function computeSize ($content, $currentPage){
    $start = ($currentPage-1)*ParagraphsInPage;     $end = $start + ParagraphsInPage;
    for ($i = $start, $j = 1; $i < $end; $i++, $j++){
        echo '<p>';
        echo 'Количество символов в абзаце ' . $j . ': ' . mb_strlen($content[$i], 'CP1251') . '<br>';
        echo 'Количество слов в абзаце ' . $j . ': ' . str_word_count($content[$i]) . '<br>';
        echo '</p>';
    }
}

function Color (&$content, $numberParagraphs){
    $pattern = '/(^|[.!?]\s+)(<.*>)?([0-9,A-Z,a-z,А-Я,а-я,Ёё])/Uu';


    $replace = '$1$2<b>$3</b>';


    $content = preg_replace($pattern, $replace, $content);
    return $content;

}

function &setColor ($content)
{
    $patternJava = '/(j)(a)(v)(a)/i';
    $patternHTML = '/(h)(t)(m)(l)/i';
    $patternPHP = '/(p)(h)(p)/i';
    $patternASP = '/(a)(s)(p)/i';
    $patternASPNET = '/(a)(s)(p)(.)(n)(e)(t)/i';

    $content = preg_replace($patternJava, '<span style="color: red;">$1$2$3$4</span>', $content);
    $content = preg_replace($patternHTML, '<span style="color: green;">$1$2$3$4</span>', $content);
    $content = preg_replace($patternPHP, '<span style="color: blue;">$1$2$3</span>', $content);
    $content = preg_replace($patternASP, '<span style="color: yellow">$1$2$3</span>', $content);
    $content = preg_replace($patternASPNET, '<span style="color: grey;">$1$2$3$4$5$6</span>', $content);
    return $content;
}