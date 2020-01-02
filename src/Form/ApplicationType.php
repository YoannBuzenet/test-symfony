<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

class ApplicationType extends AbstractType{

    /**
     * Builds automatically label & placeholder for the form
     *
     * @param string $label
     * @param string $placeholder
     * @param array other options that we want to add to form parameters
     * @return array
     */
    protected function getConfiguration(string $label, string $placeholder, array $options=[]) : array{

        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
            ], $options);
    }


}

?>