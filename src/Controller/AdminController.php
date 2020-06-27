<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


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
     * @IsGranted("ROLE_ADMIN", message="Vous n'avez pas les droits pour acceder à cette partie du site !")
     * @Route("/admin/dashboard", name="dashboard")
     */
    public function dashboard()
    {
        return $this->render('admin/index.html.twig');
    }

}
