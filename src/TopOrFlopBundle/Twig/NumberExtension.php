<?php

//Ici on crée des filtres twig et on a rajouté les informations dans le services.yml

namespace TopOrFlopBundle\Twig;

use TopOrFlopBundle\Entity\Media;

class NumberExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('floor', array($this, 'floorMethod')),
            new \Twig_SimpleFilter('format_score', array($this, 'formatMediaScore')),
        );
    }

    public function floorMethod($number)
    {
        return floor($number);
    }

    public function formatMediaScore(Media $media)
    {
        $average = $media->getAverage();

        return (null === $average) ? '-' : sprintf('%.1f', $average);
    }

    public function getName()
    {
        return 'number_extension';
    }
}