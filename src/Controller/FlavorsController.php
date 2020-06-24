<?php

namespace App\Controller;

use App\Entity\Flavors;
use App\Form\FlavorsType;
use App\Repository\FlavorsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/flavors")
 */
class FlavorsController extends AbstractController
{
    /**
     * @Route("/", name="flavors_index", methods={"GET"})
     */
    public function index(FlavorsRepository $flavorsRepository): Response
    {
        return $this->render('flavors/index.html.twig', [
            'flavors' => $flavorsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="flavors_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $flavor = new Flavors();
        $form = $this->createForm(FlavorsType::class, $flavor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($flavor);
            $entityManager->flush();

            return $this->redirectToRoute('flavors_index');
        }

        return $this->render('flavors/new.html.twig', [
            'flavor' => $flavor,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="flavors_show", methods={"GET"})
     */
    public function show(Flavors $flavor): Response
    {
        return $this->render('flavors/show.html.twig', [
            'flavor' => $flavor,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="flavors_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Flavors $flavor): Response
    {
        $form = $this->createForm(FlavorsType::class, $flavor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('flavors_index');
        }

        return $this->render('flavors/edit.html.twig', [
            'flavor' => $flavor,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="flavors_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Flavors $flavor): Response
    {
        if ($this->isCsrfTokenValid('delete'.$flavor->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($flavor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('flavors_index');
    }
}
