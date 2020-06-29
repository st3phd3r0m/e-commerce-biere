<?php

namespace App\Controller;

use App\Entity\Categories;
use App\Entity\Comments;
use App\Entity\Flavors;
use App\Entity\Products;
use App\Entity\Volumes;
use App\Form\CommentsType;
use App\Repository\CategoriesRepository;
use App\Repository\ProductsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * 
     * @Route("/volume/{slug}", name="home_volume")
     * @param string $slug
     * @param Request $request
     * @return Response
     */
    public function showVolume(string $slug, PaginatorInterface $paginator, Request $request)
    {

        $volume = $this->getDoctrine()->getRepository(Volumes::class)->findOneBy(['slug' => $slug]);

        $products = $paginator->paginate(
            $volume->getProducts(),
            //Le numero de la page, si aucun numero, on force la page 1
            $request->query->getInt('page', 1),
            //Nombre d'élément par page
            12
        );

        return $this->render('home/categories.html.twig', [
            'volume' => $volume,
            'products' => $products
        ]);
    }

    /**
     * 
     * @Route("/flavor/{slug}", name="home_flavor")
     * @param string $slug
     * @param Request $request
     * @return Response
     */
    public function showFlavor(string $slug, PaginatorInterface $paginator, Request $request)
    {

        $flavor = $this->getDoctrine()->getRepository(Flavors::class)->findOneBy(['slug' => $slug]);

        $products = $paginator->paginate(
            $flavor->getProducts(),
            //Le numero de la page, si aucun numero, on force la page 1
            $request->query->getInt('page', 1),
            //Nombre d'élément par page
            12
        );

        return $this->render('home/categories.html.twig', [
            'flavor' => $flavor,
            'products' => $products
        ]);
    }

    /**
     * 
     * @Route("/categorie/{slug}", name="home_categorie")
     * @param string $slug
     * @param Request $request
     * @return Response
     */
    public function showCategory(string $slug, PaginatorInterface $paginator, Request $request)
    {

        $categorie = $this->getDoctrine()->getRepository(Categories::class)->findOneBy(['slug' => $slug]);

        $products = $paginator->paginate(
            $categorie->getProducts(),
            //Le numero de la page, si aucun numero, on force la page 1
            $request->query->getInt('page', 1),
            //Nombre d'élément par page
            12
        );

        return $this->render('home/categories.html.twig', [
            'categorie' => $categorie,
            'products' => $products
        ]);
    }

    /**
     * @Route("/results", name="results", methods={"GET"})
     * @return Response
     */
    public function search(Request $request, PaginatorInterface $paginator)
    {
        $expr = $request->query->get('search');
        $products = $paginator->paginate(
            $this->getDoctrine()->getRepository(Products::class)->search($expr),
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('limit', 12)/*limit per page*/
        );

        return $this->render('home/categories.html.twig',[
            'products' => $products,
            'expr' => $expr,
            'numberOfResults' => $products->getTotalItemCount()
        ]);
    }



    /**
     * @Route("/product/{slug}", name="home_show")
     * @param string $slug
     * @param Request $request
     * @return Response
     */
    public function showProduct(ProductsRepository $productsRepository, string $slug, Request $request)
    {

        //Selectionne 1 donnée de la table "posts" via son Id. getRepository attend en paramètre, l'entité avec laquelle on souhaite travailler
        $product = $this->getDoctrine()->getRepository(Products::class)->findOneBy(['slug' => $slug]);
        $productsBestSales = $productsRepository->findBy([], ['created_at' => 'DESC'], 8);

        // Erreur 404 si aucun article trouvé
        if (!$product) {
            throw $this->createNotFoundException('Cet article est inéxistant.');
        }

        //Ajout du formulaire
        //Instanciation de l'entité Comments
        // $comment = new Comments;
        //Création du formulaire avec pour parametres :
        // -Le formulaire genere en ligne de commande
        //-l'objet de l'intance ci-dessus
        // $form = $this->createForm(CommentsType::class, $comment);

        //Manipulation de la requete pour hydration automatique
        // $form->handleRequest($request);

        //Si le formulaire est envoyé et celui-ci est valide !
        // if ($form->isSubmitted() && $form->isValid()) {
        //     $comment->setCreatedAt(new \DateTime('now'));
        //     $comment->setProduct($product);
        //     $comment->setUser($this->getUser());

        //     $doctrine = $this->getDoctrine()->getManager();
        //     $doctrine->persist($comment);
        //     $doctrine->flush();

        //     //Permet de vider les champs d'un formulaire
        //     $comment = new Comments;
        //     $form = $this->createForm(CommentType::class, $comment);

        //     //Envoi d'un message de succès
        //     $this->addFlash('success', 'Votre commentaire a bien été posté.');
        // }

        return $this->render('home/show.html.twig', [
            'product' => $product,
            'productsBestSales' => $productsBestSales,        
            // 'formComment' => $form->createView()
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function index(ProductsRepository $productsRepository , Request $request)
    {

        //Selectionne toutes les données de la table "posts"
        //getRepository attend en paramètre, l'entité avec laquelle on souhaite travailler
        // $posts = $this->getDoctrine()->getRepository(Posts::class)->findAll(),

        $productsNew = $productsRepository->findBy([], ['created_at' => 'DESC'], 3);
        $productsBestSales = $productsRepository->findBy([], ['created_at' => 'DESC'], 12);

        return $this->render('home/index.html.twig', [
            'productsNew' => $productsNew,
            'productsBestSales' => $productsBestSales
        ]);

    }
}
