<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Orders;
use App\Entity\Products;
use App\Entity\Users;
use App\Form\CartType;
use App\Form\UsersType;
use App\Repository\CartRepository;
use App\Repository\OrdersRepository;
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

        $total = 0;
        foreach ($cart as $line) {
            $total += $line['product']->getPrice() * $line['quantity'];
        }

        // Stripe - Create a PaymentIntent on the server
        Stripe::setApiKey('sk_test_51H02NpD7y6oTPe9NYnY22AFmxD034fdn0ndOVbOe63fGV5hQLUfHVhlIi59PGsFQWVvyfefK3c6MNKoBihYojpBT00Qo2t4tvx');

        // Prix en centimes !!!
        $intent = PaymentIntent::create([
            'amount' => $total * 100,
            'currency' => 'eur'
        ]);

        return $this->render('customer/cartPayment.html.twig', ['stripe' => $intent]);
    }

    /**
     * @Route("/store/transaction", name="store_transaction", methods={"GET","POST"})
     * @return Response
     */
    public function storeTransaction(): Response
    {

        $entityManager = $this->getDoctrine()->getManager();

        //On appel la variable globale de session
        $cartSession = $this->session->get('cart');

        //On calcule le montant total de la transaction
        $total = 0;
        foreach ($cartSession as $line) {
            $total += $line['product']->getPrice() * $line['quantity'];
        }

        //Concaténation de l'adresse de l'utilisateur
        $address = $this->getUser()->getAdress() . ', ' . $this->getUser()->getCity() . ', ' . $this->getUser()->getPostalCode();

        //Instanciation de Orders et "hydratation"
        $order = new Orders();
        $order->setCreatedAt(new \DateTime());
        $order->setRef(rand(00000000, 99999999));
        $order->setPayment('Carte bancaire');
        $order->setStatus('En attente de préparation');
        $order->setAmount($total);
        $order->setDeliveryPoint($address);
        $order->setUser($this->getUser());
        $entityManager->persist($order);

        // dd($order);

        foreach ($cartSession as $line) {

            $amount = $line['product']->getPrice() * $line['quantity'];
            //Instanciation de Cart et "hydratation"
            $cart = new Cart;
            $cart->setProduct($entityManager->getRepository(Products::class)->find($line['product']->getId()));
            $cart->setQuantity($line['quantity']);
            $cart->setUnitPrice($line['product']->getPrice());
            $cart->setAmmount($amount);
            $cart->setOrders($order);
            $entityManager->persist($cart);
        }

        $entityManager->flush();


        //On vide le panier de la variable globale de session
        $cartSession = [];
        $this->session->set('cart', $cartSession);

        return $this->redirectToRoute('customer_send_bill');
    }


    /**
     * @Route("/send/bill", name="customer_send_bill")
     */
    public function sendBill()
    {

        return $this->render('customer/send_bill.html.twig', []);
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

            if ($cart = $this->session->get('cart')){
                return $this->redirectToRoute('customer_purchase_summary');
            }
            return $this->redirectToRoute('customer_details');
            
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


    /**
     * @Route("/orders/record", name="customer_orders_record")
     */
    public function ordersRecord(OrdersRepository $ordersRepository)
    {
        $user = $this->getUser();

        $orders = $ordersRepository->findBy(['user' => $user], ['created_at' => 'DESC']);

        return $this->render('customer/ordersRecord.html.twig', [
            'orders' => $orders,
        ]);
    }

    /**
     * @Route("/order/record/{id}", name="customer_order_record", methods={"GET","POST"})
     * @param Request $request
     * @param Orders $order
     * @return Response
     */
    public function orderRecord(Orders $order)
    {
        return $this->render('customer/orderRecord.html.twig', [
            'order' => $order
        ]);
    }
}
