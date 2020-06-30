<?php

namespace App\Form;

use App\Entity\Comments;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('comment', TextareaType::class, [
            	'required' => true,
				// 'placeholder' => 'Votre commentaire',
				'label' => 'Ecrivez votre commentaire :',
				'constraints' => [
					new NotBlank([
						'message' => 'Vous devez saisir un commentaire'
					]),
					new Length([
						'min' => 10,
						// 'max' => 1024,
						'minMessage' => 'Veuillez entrer un minimum de {{ limit }} caractères',
						// 'maxMessage' => 'Le commentaire ne doit pas dépasser les {{ limit }} caractères.'
					])
				]
			])
			->add('valider', SubmitType::class)
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comments::class,
        ]);
    }
}
