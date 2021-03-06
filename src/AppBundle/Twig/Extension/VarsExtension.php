<?php

namespace AppBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;
use \Twig_Extension;

class VarsExtension extends Twig_Extension
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getName()
    {
        return 'twig.extension';
    }

    public function getFilters()
    {
        return array(
            'json_decode' => new \Twig_Filter_Method($this, 'jsonDecode'),
            'youtube_id' => new \Twig_Filter_Method($this, 'youtubeId'),
        );
    }

    public function jsonDecode($str)
    {
        return json_decode($str);
    }

    public function youtubeId($str)
    {
        parse_str(parse_url($str, PHP_URL_QUERY), $url);
        return $url['v'];
    }

}