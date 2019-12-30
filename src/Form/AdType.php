<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends AbstractType
{

    /**
     * Builds automatically label & placeholder for the form
     *
     * @param string $label
     * @param string $placeholder
     * @param array other options that we want to add to form parameters
     * @return array
     */
    private function getConfiguration(string $label, string $placeholder, array $options=[]) : array{

        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
            ], $options);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration('Titre', 'Taper un super titre pour votre annonce'))
            ->add('slug', TextType::class, $this->getConfiguration('Chaine URL', 'Adresse web (automatique)', ['required' => false]))
            ->add('coverImage', UrlType::class, $this->getConfiguration('URL de l\'image principale', 'Collez ici l\'adresse de l\'image'))
            ->add('introduction', TextType::class, $this->getConfiguration('Introduction', 'Présentez votre bien'))
            ->add('content', TextareaType::class, $this->getConfiguration('Contenu', 'Décrivez votre bien à vos futurs visiteurs. Qu\'ont-ils à découvrir dans votre magnifique propriété ?'))
            ->add('rooms', IntegerType::class, $this->getConfiguration('Nombre de chambres', 'Nombre de chambres disponibles'))
            ->add('price', MoneyType::class, $this->getConfiguration('Prix', 'Indiquez le prix par nuit'))
            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class,
                'allow_add' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
