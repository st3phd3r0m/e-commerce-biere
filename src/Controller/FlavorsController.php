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
 * @Route("/admin/dashboard/flavors")
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

            //Envoi d'un message de succès
            $this->addFlash('success', 'La nouvelle saveur a bien été ajoutée en bdd.');

            return $this->redirectToRoute('flavors_index');
        }

        return $this->render('flavors/new.html.twig', [
            'flavor' => $flavor,
            'form' => $form->createView(),
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

            //Envoi d'un message de succès
            $this->addFlash('success', 'La saveur a bien été modifiée dans la bdd.');

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
        if ($this->isCsrfTokenValid('delete' . $flavor->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($flavor);
            $entityManager->flush();

            //Envoi d'un message de succès
            $this->addFlash('success', 'La saveur a bien été supprimée de la bdd.');
        }

        return $this->redirectToRoute('flavors_index');
    }
}
