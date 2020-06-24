<?php

namespace App\Controller;

use App\Entity\Volumes;
use App\Form\VolumesType;
use App\Repository\VolumesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/volumes")
 */
class VolumesController extends AbstractController
{
    /**
     * @Route("/", name="volumes_index", methods={"GET"})
     */
    public function index(VolumesRepository $volumesRepository): Response
    {
        return $this->render('volumes/index.html.twig', [
            'volumes' => $volumesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="volumes_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $volume = new Volumes();
        $form = $this->createForm(VolumesType::class, $volume);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($volume);
            $entityManager->flush();

            return $this->redirectToRoute('volumes_index');
        }

        return $this->render('volumes/new.html.twig', [
            'volume' => $volume,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="volumes_show", methods={"GET"})
     */
    public function show(Volumes $volume): Response
    {
        return $this->render('volumes/show.html.twig', [
            'volume' => $volume,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="volumes_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Volumes $volume): Response
    {
        $form = $this->createForm(VolumesType::class, $volume);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('volumes_index');
        }

        return $this->render('volumes/edit.html.twig', [
            'volume' => $volume,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="volumes_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Volumes $volume): Response
    {
        if ($this->isCsrfTokenValid('delete'.$volume->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($volume);
            $entityManager->flush();
        }

        return $this->redirectToRoute('volumes_index');
    }
}
