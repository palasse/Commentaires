<?php

namespace App\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CommentResponseForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextType::class, array(
                'label' => 'Comments ',
                'attr' => array(
                    'placeholder' => 'Répondre'
                )))
            ->add('send', SubmitType::class, [
                'label' => "Envoyer",
            ])
            ->add('idComment', HiddenType::class)
        ;
    }
}
