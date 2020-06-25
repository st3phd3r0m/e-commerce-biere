<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class UsersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class,[
                'required' => true,
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un prénom'
                    ])
                ]
            ])

            ->add('lastname', TextType::class,[
                'required' => true,
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un nom'
                    ])
                ]
            ])

            ->add('adress', TextType::class,[
                'required' => true,
                'label' => 'Adresse',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir une adresse'
                    ])
                ]
            ])

            ->add('postal_code', TextType::class,[
                'required' => true,
                'label' => 'Code Postal',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un code postal'
                    ])
                ]
            ])

            ->add('city', TextType::class,[
                'required' => true,
                'label' => 'Ville',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir une ville'
                    ])
                ]
            ])
            
            ->add('phone', TextType::class,[
                'required' => true,
                'label' => 'Téléphone',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un numéro de téléphone'
                    ])
                ]
            ])

            ->add('email', EmailType::class,[
                'required' => true,
                'label' => 'Adresse e-mail',
				'constraints' => [
					new NotBlank([
						'message' => 'Veuillez saisir une adresse e-mail'
					]),
					new Email([
						'message' => 'Votre adresse e-mail n\'est pas valide'
					])
				]
            ])
            
            ->add('role', ChoiceType::class, [
                'required' => true,
				'label' => 'Rôle utilisateur',
				'expanded' => true,
				'multiple' => true,
				'choices' => [
					'Utilisateur' => 'ROLE_USER',
					'Administrateur' => 'ROLE_ADMIN'
				],
				'constraints' => [
					new NotBlank([
						'message' => 'Veuillez choisir un rôle pour l\'utilisateur'
					])
				]
            ])

            ->add('valider', SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
