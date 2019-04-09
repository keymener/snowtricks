<?php

namespace App\Service;


use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class UrlMaker transforms symfony routes to url
 * @package App\Service
 */
class UrlMaker
{

    /**
     * @var UrlGeneratorInterface
     */
    private $generator;

    public function __construct(UrlGeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    /**
     * Generates url with token
     *
     * @param string $route
     * @param string $token
     * @return string
     */
    public function generate(string $route, string $token)
    {
        return $this->generator->generate($route, [
            'token' => $token,
        ], UrlGeneratorInterface::ABSOLUTE_URL);
    }
}
