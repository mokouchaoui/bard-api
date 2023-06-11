<?php

// src/Form/KeyboardFormType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class KeyboardFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('keyboard', TextType::class, [
                'label' => 'Keyboard',
                'required' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Submit',
            ]);
    }
}
