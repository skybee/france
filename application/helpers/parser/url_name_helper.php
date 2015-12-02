<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function seoUrl( $title )
{
    $title = iconv('utf-8', 'cp1251//IGNORE', $title);
	$title = preg_replace("#&[a-z]+;#i", ' ', $title); //удаление мнемоники
	$title = preg_replace("|&#\d{2,4};|i", ' ', $title); //удаление мнемоники
	$title = strtolower( trim($title) );

    $letters = array(
        'а'=>'a',
        'б'=>'b',
        'в'=>'v',
        'г'=>'g',
        'д'=>'d',
        'е'=>'e',
        'ё'=>'e',
		'є'=>'e',
        'ж'=>'j',
        'з'=>'z',
        'и'=>'i',
		'і'=>'i',
        'й'=>'y',
        'к'=>'k',
        'л'=>'l',
        'м'=>'m',
        'н'=>'n',
        'о'=>'o',
        'п'=>'p',
        'р'=>'r',
        'с'=>'s',
        'т'=>'t',
        'у'=>'u',
        'ф'=>'f',
        'х'=>'h',
        'ц'=>'c',
        'ч'=>'ch',
        'ш'=>'sh',
        'щ'=>'sh',
        'ъ'=>'',
        'ы'=>'y',
        'ь'=>'',
        'э'=>'e',
        'ю'=>'yu',
        'я'=>'ya',
		'А'=>'a',
        'Б'=>'b',
        'В'=>'v',
        'Г'=>'g',
        'Д'=>'d',
        'Е'=>'e',
        'Ё'=>'e',
		'Є'=>'e',
        'Ж'=>'j',
        'З'=>'z',
        'И'=>'i',
		'І'=>'i',
        'Й'=>'y',
        'К'=>'k',
        'Л'=>'l',
        'М'=>'m',
        'Н'=>'n',
        'О'=>'o',
        'П'=>'p',
        'Р'=>'r',
        'С'=>'s',
        'Т'=>'t',
        'У'=>'u',
        'Ф'=>'f',
        'Х'=>'h',
        'Ц'=>'c',
        'Ч'=>'ch',
        'Ш'=>'sh',
        'Щ'=>'shch',
        'Ъ'=>'',
        'Ы'=>'y',
        'Ь'=>'',
        'Э'=>'e',
        'Ю'=>'yu',
        'Я'=>'ya',
        ' '=>'-',
		'-'=>'-',
		'.'=>'-',
		'`'=>''
    );

    // разбивка строки на буквы
    $pattern = "#[а-яёА-ЯЁ\w\d\s\.-]{1}#i";
    preg_match_all($pattern, $title, $bukvy);

	//echo '<pre>';
	//print_r($bukvy);
	//echo '</pre>';
	
    $urlstr = '';
    foreach ($bukvy[0] as $b)
    {
        if( isset($letters[$b])  )
            $urlstr .= $letters[$b];
        else
            $urlstr .=$b;
    }
    #$urlstr .= '_';

	return $urlstr;
}
