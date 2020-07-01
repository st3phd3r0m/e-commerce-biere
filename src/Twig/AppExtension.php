<?php
namespace App\Twig;

use App\Repository\CategoriesRepository;
use App\Repository\FlavorsRepository;
use App\Repository\VolumesRepository;
use Twig\Extension\AbstractExtension;

use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('allCategories', [$this, 'showCategories']),
            new TwigFunction('allFlavors', [$this, 'showFlavors']),
            new TwigFunction('allVolumes', [$this, 'showVolumes']),
            new TwigFunction('categoryById', [$this, 'showCategoryById']),
            new TwigFunction('flavorById', [$this, 'showFlavorById']),
            new TwigFunction('volumeById', [$this, 'showVolumeById'])
        ];
    }

    protected $categoriesRepository;
    protected $flavorsRepository;
    protected $volumesRepository;

    public function __construct(CategoriesRepository $categoriesRepository, FlavorsRepository $flavorsRepository, VolumesRepository $volumesRepository)
    {
        $this->categoriesRepository = $categoriesRepository;
        $this->flavorsRepository = $flavorsRepository;
        $this->volumesRepository = $volumesRepository;
    }

    public function showCategories()
    {
        return $this->categoriesRepository->findAll();   
    }

    public function showFlavors()
    {
        return $this->flavorsRepository->findAll();   
    }

    public function showVolumes()
    {
        return $this->volumesRepository->findAll();   
    }

    public function showCategoryById($id)
    {
        return $this->categoriesRepository->find($id)->getName();   
    }

    public function showFlavorById($id)
    {
        return $this->flavorsRepository->find($id)->getName();     
    }

    public function showVolumeById($id)
    {
        return $this->volumesRepository->find($id)->getVolume();  
    }
}