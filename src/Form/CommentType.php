<?php

namespace App\Form;

use App\Entity\Comment;
use App\Form\ApplicationType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rating', IntegerType::class, $this->getConfiguration("Rating on 5", "Please rate your trip from 0 to 5", [
                'attr'=> [
                    'min' => 0,
                    'max' => 5,
                    'step' => 1
                ]
                ]))
            ->add('content', TextareaType::class,$this->getConfiguration("How was your trip ?", "What did you like, or didn't ? Please tell us."))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}
