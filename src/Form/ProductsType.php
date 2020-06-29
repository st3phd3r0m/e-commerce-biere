<?php

namespace App\Form;

use App\Entity\Categories;
use App\Entity\Flavors;
use App\Entity\Products;
use App\Entity\Volumes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Vich\UploaderBundle\Form\Type\VichImageType;


class ProductsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('name', TextType::class, [
                'required' => true,
                'label'=>'Nom de l\'article de vente',
                'attr'=>[
                    'class'=>'form-control'
                ],
                'constraints' => [
                    new NotBlank([
                        'message'=>'Veuillez saisir un nom pour l\'article de vente.',
                        'groups'=> ['new', 'update']
                    ]),
                    new Length([
                        'min' => 4,
                        'minMessage' => "Le nom doit comporter au minimum {{ limit }}
                        caractères.",
                        'groups'=> ['new', 'update']
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'required'=>true,
                'label'=>'Description de l\'article de vente',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez saisir la description de l\'article',
                        'groups'=> ['new', 'update']
                    ]),
                    new Length([
                        'min' => 50,
                        'minMessage' => "Le texte doit comporter au minimum {{ limit }}
                        caractères.",
                        'groups'=> ['new', 'update']
                    ])
                ] 
            ])            
            ->add('category', EntityType::class, [
                'label'=>'Choisir une catégorie',
                'class'=> Categories::class,
                'choice_label'=> 'name'
            ])
            ->add('flavor', EntityType::class, [
                'label'=>'Choisir une catégorie',
                'class'=> Flavors::class,
                'choice_label'=> 'name'
            ])
            ->add('volume', EntityType::class, [
                'label'=>'Choisir une catégorie',
                'class'=> Volumes::class,
                'choice_label'=> 'volume'
            ])
            ->add('degree', NumberType::class,[
                'required' => true,
                'label'=>'Pourcentage d\'alcool du produit',
                'constraints'=>[
                    new NotBlank([
                        'message'=> 'Veuillez saisir la teneur en alcool.',
                        'groups'=> ['new', 'update']
                    ])
                ]
            ])
            ->add('price', NumberType::class,[
                'required' => true,
                'label'=>'Prix unitaire de l\'article de vente',
                'constraints'=>[
                    new NotBlank([
                        'message'=> 'Veuillez saisir le prix unitaire.',
                        'groups'=> ['new', 'update']
                    ])
                ]
            ])
            // ->add('created_at', DateType::class, [
            //     'required' => true,
            //     'label'=>'Date de publication',
            //     //'data'=> new \DateTime(),  //permet de forcer le remplissage de l'input
            //     'widget'=> 'single_text',
            //     'constraints' => [
            //         new NotBlank([
            //             'message'=>'Veuillez saisir une date',
            //             'groups'=> ['new', 'update']
            //         ]),
            //         new DateTime([
            //             'message'=> 'La date est invalide',
            //             'groups'=> ['new', 'update']
            //         ])
            //     ]
            // ])
            // ->add('updated_at', DateType::class, [
            //     'required' => true,
            //     'label'=>'Date de publication',
            //     //'data'=> new \DateTime(),  //permet de forcer le remplissage de l'input
            //     'widget'=> 'single_text',
            //     'constraints' => [
            //         new NotBlank([
            //             'message'=>'Veuillez saisir une date',
            //             'groups'=> ['new', 'update']
            //         ]),
            //         new DateTime([
            //             'message'=> 'La date est invalide',
            //             'groups'=> ['new', 'update']
            //         ])
            //     ]
            // ])
            ->add('quantity', IntegerType::class,[
                'required' => true,
                'label'=>'Quantité de bouteilles du produit en stock',
                'constraints'=>[
                    new NotBlank([
                        'message'=> 'Veuillez saisir la quantité en stock.',
                        'groups'=> ['new', 'update']
                    ])
                ]
            ])
            ->add('imageFile', VichImageType::class,[
                'required' => true,
                'label'=>'Image d\'illustration de l\'article de vente',
                'download_link'=>false,
                'imagine_pattern'=>'miniatures',
                'constraints'=>[
                    new NotBlank([
                        'message'=> 'Veuillez choisir une image de présentation.',
                        'groups'=> ['new']
                    ]),
                    new Image([
                        'maxSize'=>'2M',
                        'maxSizeMessage'=> 'Votre image dépasse les 2Mo',
                        'mimeTypes'=>['image/png', 'image/gif', 'image/jpeg'],
                        'mimeTypesMessage'=>'Votre image doit être de type PNG, GIF ou JPEG',
                        'groups'=> ['new', 'update']
                    ])
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
