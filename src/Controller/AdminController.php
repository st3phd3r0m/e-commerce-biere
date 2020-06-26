<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="log")
     */
    public function index()
    {
        return $this->render('admin/log.html.twig');
    }

    /**
     * @Route("/admin/dashboard", name="dashboard")
     */
    public function dashboard()
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/orders", name="commande")
     */
    public function commande()
    {
        return $this->render('orders/index.html.twig');
    }
    

}