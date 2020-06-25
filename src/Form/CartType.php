<?php

namespace App\Form;

use App\Entity\Cart;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class CartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity', NumberType::class, [
                'required' => true,
                'label' => 'Quantitée',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir une quantitée de produit',
                        'groups' => ['new', 'update']
                    ])
                ]
            ])
            ->add('unitPrice', NumberType::class, [
                'required' => true,
                'label' => 'Prix unitaire en €',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un prix unitaire',
                        'groups' => ['new', 'update']
                    ])
                ]
            ])
            ->add('ammount', NumberType::class, [
                'required' => true,
                'label' => 'Montant total en €',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un montant total',
                        'groups' => ['new', 'update']
                    ])
                ]
            ])
            // ->add('product')
            // ->add('orders')
            ->add('editer', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cart::class,
        ]);
    }
}
