<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;

class UtilsType extends AbstractType {

    /**
     * form Configuration optional fields
     *
     * @param string $label
     * @param string $placeholder
     * @param array  $options 
     * @return array
     */
    protected function configuration($label, $placeholder, $options = [])
    {
        return array_merge ([
                'label' => $label,
                'attr'  => [
                    'placeholder' => $placeholder
                ]
            ], $options);
    }
}