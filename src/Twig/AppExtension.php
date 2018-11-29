<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFilters() : array
    {
        return [
            new TwigFilter('parse_icons', [$this, 'parseIcons'], ['is_safe' => ['html']]),
            new TwigFilter('slugify', [$this, 'slugify']),
        ];
    }

    public function getFunctions() : array
    {
        return [];
    }

    /**
     * Transforme .icon- en balise fontawesome {{ '.icon-trash'|parse_icons }} => <i class="fa fa-trash"></i>
     */
    public function parseIcons($text)
    {
        return preg_replace('/\.icon-([a-z0-9+-]+)/', '<i class="fa fa-$1"></i> ', $text);
    }
    // Liste d'articles -> liste-d-articles
    public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        // trim
        $text = trim($text, '-');
        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);
        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}