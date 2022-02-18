<?php

namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CommentForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextType::class, array(
                'label' => 'Comments ',
                'attr' => array(
                    'placeholder' => 'Votre commentaire'
                )))
            ->add('send', SubmitType::class, [
                'label' => "Envoyer",
            ])
        ;
    }
}
