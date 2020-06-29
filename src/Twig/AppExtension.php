<?php
namespace App\Twig;

use App\Repository\CategoriesRepository;
use App\Repository\FlavorsRepository;
use Twig\Extension\AbstractExtension;

use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('allCategories', [$this, 'showCategories']),
            new TwigFunction('allFlavors', [$this, 'showFlavors']),
        ];
    }

    protected $categoriesRepository;
    protected $flavorsRepository;

    public function __construct(CategoriesRepository $categoriesRepository, FlavorsRepository $flavorsRepository)
    {
        $this->categoriesRepository = $categoriesRepository;
        $this->flavorsRepository = $flavorsRepository;
    }

    public function showCategories()
    {
        return $this->categoriesRepository->findAll();   
    }

    public function showFlavors()
    {
        return $this->flavorsRepository->findAll();   
    }
}