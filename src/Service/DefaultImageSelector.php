<?php
/**
 * Created by PhpStorm.
 * User: keyme
 * Date: 01/02/2019
 * Time: 22:34
 */

namespace App\Service;


use App\Entity\Image;

/**
 * This is used to get a default image
 * Class DefaultImageSelector
 * @package App\Service
 */
class DefaultImageSelector
{

    private $defaultImageName;

    public function __construct($defaultImageName)
    {

        $this->defaultImageName = $defaultImageName;
    }

    public function getDefaultImage(): Image
    {
        $image = new Image();
        $image->setName($this->defaultImageName);

        return $image;
    }

}