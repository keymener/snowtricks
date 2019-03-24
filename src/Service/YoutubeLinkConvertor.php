<?php
/**
 * Created by PhpStorm.
 * User: keyme
 * Date: 24/03/2019
 * Time: 23:17
 */

namespace App\Service;

/**
 * Class YoutubeLinkConvertor
 * Convert youtube classic url into embed urls
 * @package App\Service
 */
class YoutubeLinkConvertor
{
    public function convert(string $url)
    {
        preg_match('/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/' , $url, $matches);

        if(null === $matches[2]){
            return $url;
        }

        return 'https://www.youtube.com/embed/'.$matches[2];




    }
}