<?php

namespace App\Service;


class TokenGenerator
{

    /**
     * Generates random token
     * @return string
     * @throws \Exception
     */
    public function generate()
    {
        return sha1(random_bytes(10));
    }

}
