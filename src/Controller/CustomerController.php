<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Orders;
use App\Entity\Products;
use App\Entity\Users;
use App\Form\CartType;
use App\Form\UsersType;
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
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken;

/**
 * @Route("/customer")
 */
class CustomerController extends AbstractController
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
     * @Route("/payment", name="customer_cart_payment", methods={"GET"})
     */
    public function cartPayment()
    {
        // dd($this->session);
        //On appel la variable globale de session
        $cart = $this->session->get('cart');

        $total=0;
        foreach($cart as $line){
            $total += $line['product']->getPrice() * $line['quantity'];
        }

        // Stripe - Create a PaymentIntent on the server
        Stripe::setApiKey('sk_test_51H02NpD7y6oTPe9NYnY22AFmxD034fdn0ndOVbOe63fGV5hQLUfHVhlIi59PGsFQWVvyfefK3c6MNKoBihYojpBT00Qo2t4tvx');

        // Prix en centimes !!!
        $intent = PaymentIntent::create([
            'amount' => $total * 100,
            'currency' => 'eur'
        ]);

        return $this->render('customer/sendBill.html.twig', ['stripe' => $intent]);
    }

    /**
     * @Route("/store/transaction/{id}", name="store_transaction", methods={"GET","POST"})
     * @param Request $request
	 * @param Users $user
	 * @return Response
     */
    public function storeTransaction(Request $request, Users $user): Response
    {

        //On appel la variable globale de session
        $cart = $this->session->get('cart');

        //On calcule le montant total de la transaction
        $total=0;
        foreach($cart as $line){
            $total += $line['product']->getPrice() * $line['quantity'];
        }

        //Concaténation de l'adresse de l'utilisateur
        $address = $this->getUser()->getAdress().', '.$this->getUser()->getCity().', '.$this->getUser()->getPostalCode();

        // dd($this->getUser()->getId());

        //Instanciation de Orders et "hydratation"
        $order = new Orders();
        $order->setCreatedAt(new \DateTime());
        $order->setRef(md5(uniqid()));
        $order->setPayment('Carte bancaire');
        $order->setStatus('En attente de préparation');
        $order->setAmount($total);
        $order->setDeliveryPoint($address);
        $order->setUser($user);
        

        // $order->setStatus($faker->randomElement(['En attente de préparation','Préparer','Envoyer et terminer']));
        // $order->setAmount($faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = 1000)); // Ex: 48.8932
        // $order->setDeliveryPoint($faker->address());
        // $order->setPayment($faker->randomElement(['Stripe','Carte bancaire']));

        dd($order);

        return $this->redirectToRoute('customer_cart_payment');

    }

    /**
     * @Route("/details/change/{id}", name="customer_change_details", methods={"GET","POST"})
     * @param Request $request
	 * @param Users $user
	 * @return Response
     */
    public function changeDetails(Request $request, Users $user): Response
    {
        $form = $this->createForm(UsersType::class, $user);
        $form->remove('roles'); 

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('customer_purchase_summary');
        }

        return $this->render('customer/changeDetails.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }



    /**
     * @Route("/details", name="customer_details")
     */
    public function details()
    {
        return $this->render('customer/details.html.twig');
    }

    /**
     * @Route("/purchase/summary", name="customer_purchase_summary")
     */
    public function purchaseSummary()
    {
        return $this->render('customer/purchaseSummary.html.twig');
    }


}
