<?php

namespace App\Twig;

use Twig\TwigFilter;
use App\Entity\Galerie;
use App\Entity\Publication;
use Twig\Extension\AbstractExtension;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class TwigExtension extends AbstractExtension {

    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;

    }
    
    public function getFilters()
    {
        return [
            new TwigFilter('debut', [$this, 'debutFilter']),
            new TwigFilter('fin', [$this, 'finFilter']),
            new TwigFilter('origineName', [$this, 'pregReplace']),
            new TwigFilter('pathPage', [$this, 'pathPage']),
            new TwigFilter('shuffle', [$this, 'shuffle']),
            new TwigFilter('filesize', [$this, 'filesize']),
            new TwigFilter('isPublication', [$this, 'isPublication']),
            new TwigFilter('isGalerie', [$this, 'isGalerie']),
        ];
    }

    public function isGalerie($object) {
        if ($object instanceof Galerie) {
            return true;
        }
        return false;
    }

    public function isPublication($object) {
        if ($object instanceof Publication) {
            return true;
        }
        return false;
    }   

    public function filesize($file) {
        $size = filesize($this->parameterBag->get('kernel.project_dir') . '/public/'.$file);
        $units = array('o', 'Ko', 'Mo', 'Go', 'To'); 
        $base = log($size, 1024); 
        return round(pow(1024, $base - floor($base)), 2) . ' ' . $units[$base];
    }

    public function shuffle($array) {
        $key = array_rand($array);
        $value = $array[$key];
        return $value;
    }

    public function pathPage(Publication $page) {

        $url = '/'.$page->getSlug();
        while (!empty($page->getParent())) {
            $url = '/'.$page->getParent()->getSlug().$url;
            $page = $page->getParent();
        }
        return $url;
    }

    public function pregReplace($imageFile) {
        return preg_replace('/^(.*?)_/','',$imageFile);
    }

    public function debutFilter($string, $level)
    {   
        preg_match('/[0-9]?[0-9]?[0-9]/', $string, $match);
        $int = intval(current($match));
        return is_int($int/$level);
    }

    public function finFilter($string, $level)
    {   
        preg_match('/[0-9]?[0-9]?[0-9]/', $string, $match);
        $int = intval(current($match))+1;
        if ($int%$level == 0 ) {
            return true;
        } else {
            return false;
        }
    }
}