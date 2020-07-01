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
use Stripe\Stripe;
use Stripe\PaymentIntent;


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

            $this->addFlash('successCart', 'Les articles ont été ajoutés à votre panier.');

            return $this->redirectToRoute('home_show',  ['slug' => $slug]);
        }
    }

    /**
     * @Route("/summary/remove/{id}", name="visitor_cart_remove", methods={"GET"})
     */
    public function removeCartLine(int $id)
    {
        //On appel la variable globale de session
        $cart = $this->session->get('cart');

        unset($cart['product_' . $id]);
        $this->session->set('cart', $cart);
        //On renvoie un message à l'utilisateur
        $this->addFlash('successCart', 'L\'article a été retirée de votre panier.');


        return $this->render('home/cartSummary.html.twig', []);
    }

    /**
     * @Route("/summary/{id}/{what}", name="visitor_cart_summary", methods={"GET"})
     */
    public function cartSummary(int $id = null, string $what = null)
    {
        //Est ce qu'il sagit d'une requete d'incrementation/decrementation de la quantité d'un article dans le panier ?
        if (isset($id) && !empty($id) && isset($what) && !empty($what)) {
            //oui

            //On appel la variable globale de session
            $cart = $this->session->get('cart');

            //On chope la quantité dans le panier
            $quantity =  $cart['product_' . $id]['quantity'];
            //incrementation/decrementation selon requete utilisateur
            if ($what === 'decr') {
                $quantity--;
            } elseif ($what === 'incr') {
                $quantity++;
            }

            //On vérifie que la quantité demandée pas l'utilisateur n'est pas nulle
            if ($quantity > 0) {

                //On verifie que la quantité demandée n'est pas supérieure à celle disponible en stock
                $quantityInStock =  $cart['product_' . $id]['product']->getQuantity();

                if ($quantityInStock >= $quantity) {
                    //si ce n'est pas le cas, on charge dans la globale session
                    $cart['product_' . $id]['quantity'] = $quantity;
                    $this->session->set('cart', $cart);
                } else {
                    //si c'est le cas, on ne charge pas dans la globale session et on renvoie un message à l'utilisateur
                    $this->addFlash('invalidQuantity', 'Nous ne pouvons malheureusement accéder à votre demande, la quantité en stock de cet article étant insuffisante.');
                }
            } else {
                //Si la quantité demandée est nulle, on supprime la ligne de l'article correspondant
                return $this->redirectToRoute('visitor_cart_remove', ['id' => $id]);
            }
        }


        return $this->render('home/cartSummary.html.twig', []);
    }


    /**
     * @Route("/payment", name="visitor_cart_payment", methods={"GET"})
     */
    public function cartPayment()
    {
        //On appel la variable globale de session
        $cart = $this->session->get('cart');


        $total = 999;

        // Stripe - Create a PaymentIntent on the server
        Stripe::setApiKey('sk_test_51H02NpD7y6oTPe9NYnY22AFmxD034fdn0ndOVbOe63fGV5hQLUfHVhlIi59PGsFQWVvyfefK3c6MNKoBihYojpBT00Qo2t4tvx');

        // Prix en centimes !!!
        $intent = PaymentIntent::create([
            'amount' => $total * 100,
            'currency' => 'eur'
        ]);

        return $this->render('home/cartPayment.html.twig', ['stripe' => $intent]);
    }
}
