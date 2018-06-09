<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class JokesType extends AbstractType
{
    /**
     * Имя поля формы, в котором выводятся категории шуток.
     */
    const FIELD_CATEGORIES = 'categories';

    /**
     * Имя поля формы, в которое необходимо вводить email для отправки.
     */
    const FIELD_EMAIL = 'email';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $categories = $options[self::FIELD_CATEGORIES];
        $builder
            ->add(self::FIELD_CATEGORIES, ChoiceType::class, [
                    'choices'  => $categories,
                    'choice_label' => function ($choiceValue) {
                        return $choiceValue;
                    },
                    'label' => 'Categories: '
                ]
            )
            ->add(self::FIELD_EMAIL, EmailType::class, [
                'label' => 'Email: ',
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\Email,
                    new \Symfony\Component\Validator\Constraints\NotBlank(),
                ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Send'])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            self::FIELD_CATEGORIES => [],
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
