<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig');
    }

<<<<<<< HEAD
    
=======
    /**
     * @Route("/products", name="products_index")
     */
    public function products()
    {
        return $this->render('home/products/index.html.twig');
    }

>>>>>>> a1280acc62f661439fa18b0357e702e09f4a1edf

}