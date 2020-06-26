<?php

namespace App\Form;

use App\Entity\Orders;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class OrdersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('users')
            ->add('ref', TextType::class, [
                'required' => true,
                'label' => 'Référence de la commande',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir une réference',
                        'groups' => ['new', 'update']
                    ])
                ]
            ])
            ->add('created_at', DateType::class, [
                'required' => true,
                'label' => 'Date de création',
                //'data' => new \DateTime(), // ligne à enlever pour ne pas modifier la date de création lors d'une modification d'article
                'widget' => 'single_text', // Pour afficher le calendrier en popup sinon menu simple déroulant
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez choisir une date',
                        'groups' => ['new', 'update']
                    ]),
                    new DateTime([
                        'message' => 'La date est invalide',
                        'groups' => ['new', 'update']
                    ])
                ]
            ])
            ->add('status', TextType::class, [
                'required' => true,
                'label' => 'Status de commande',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un status de commande',
                        'groups' => ['new', 'update']
                    ])
                ]
            ])
            ->add('amount', NumberType::class, [
                'required' => true,
                'label' => 'Total de la commande',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un total de commande',
                        'groups' => ['new', 'update']
                    ])
                ]
            ])
            ->add('delivery_point', TextType::class, [
                'required' => true,
                'label' => 'Adresse de livraison',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir une adresse de livraison',
                        'groups' => ['new', 'update']
                    ])
                ]
            ])
            ->add('payment', ChoiceType::class, [
                'required' => true,
                'label' => 'Moyens de paiement',
                'attr' => ['class' => 'custom-select'],
                'choices' => [
                    'Carte banquaire' => 'carte banquaire',
                    'Stripe' => 'stripe'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un moyens de paiement',
                        'groups' => ['new', 'update']
                    ])
                ]
            ])
            ->add('editer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Orders::class,
        ]);
    }
}
