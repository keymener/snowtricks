<?php


namespace App\Service;


class SlugTransformer
{


    public function transform(string $text): string
    {
        $string = utf8_encode($text);
        $string = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
        $string = preg_replace('/[^a-z0-9- ]/i', '', $string);
        $string = str_replace(' ', '-', $string);
        $string = trim($string, '-');
        $string = strtolower($string);

        if (empty($string)) {
            return 'n-a';
        }

        return $string;
    }

}
