<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Products;
use App\Form\CartType;
use App\Repository\CartRepository;
use App\Repository\ProductsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/cart")
 */
class VisitorCartController extends AbstractController
{

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
        if (!$this->session->has('cart')) {
            $this->session->set('cart', []);
        }
    }

    /**
     * @Route("/add/product", name="visitor_cart_add", methods={"GET"})
     */
    public function addProduct(Request $request, ProductsRepository $productsRepository): Response
    {
        //Reception des données de la requete en méthode GET
        $id = $request->query->get('product_id');
        $quantity = $request->query->get('quantity');

        //Vérification : le produit est-il bien en bdd ?
        if (!$productsRepository->find($id)) {
            //Non, on renvoie l'utilisateur vers la page d'acceuil
            return $this->redirectToRoute('home');
        } else {
            //oui
            $product = $productsRepository->find($id);
            $slug = $product->getSlug();
            //Si la quantité d'article saisie par l'utilisateur est plus grande que celle en stock,
            if ($product->getQuantity() < $quantity) {
                // dd($product->getQuantity());
                //on renvoi un message
                $this->addFlash('invalidQuantity', 'Il ne reste que ' . ($product->getQuantity()) . ' article' .
                    (($product->getQuantity() > 1) ? 's' : '') . ' en stock. Veuillez resaisir un nombre de produits à ajouter à votre panier');
                return $this->redirectToRoute('home_show',  ['slug' => $slug]);
            }

            //Sinon, on charge les articles dans la variable session 
            $cart = $this->session->get('cart');

            $cart['product_' . $id] = [
                'product' => $productsRepository->find($id),
                'quantity' => $quantity
            ];
            $this->session->set('cart', $cart);

            dd($cart);

            $this->addFlash('successCart', 'Les articles ont été ajoutés à votre panier.');

            return $this->redirectToRoute('home_show',  ['slug' => $slug]);
        }
    }

    /**
     * @Route("/summary", name="visitor_cart_summary", methods={"GET"})
     */
    public function cartSummary()
    {
        return $this->render('home/cartSummary.html.twig', [
        ]);
    }

}
