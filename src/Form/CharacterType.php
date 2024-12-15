<?php

namespace App\Form;

use App\Entity\Character;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class CharacterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du personnage',
                'attr' => [
                    'placeholder' => 'Entrez le nom du personnage',
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le nom est obligatoire']),
                    new Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Le nom doit faire au moins {{ limit }} caractères',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères'
                    ])
                ]
            ])
            ->add('level', IntegerType::class, [
                'label' => 'Niveau',
                'attr' => [
                    'min' => 1,
                    'max' => 99,
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le niveau est obligatoire']),
                    new Range([
                        'min' => 1,
                        'max' => 99,
                        'minMessage' => 'Le niveau minimum est {{ limit }}',
                        'maxMessage' => 'Le niveau maximum est {{ limit }}'
                    ])
                ]
            ])
            ->add('arcana', ChoiceType::class, [
                'label' => 'Arcane',
                'choices' => [
                    'Le Fou' => 'fool',
                    'Le Magicien' => 'magician',
                    'La Prêtresse' => 'priestess',
                    'L\'Impératrice' => 'empress',
                    'L\'Empereur' => 'emperor',
                    'Le Hiérophante' => 'hierophant',
                    'Les Amoureux' => 'lovers',
                    'Le Chariot' => 'chariot',
                    'La Force' => 'strength',
                    'L\'Ermite' => 'hermit',
                    'La Roue de Fortune' => 'fortune',
                    'La Justice' => 'justice',
                    'Le Pendu' => 'hanged',
                    'La Mort' => 'death',
                    'La Tempérance' => 'temperance',
                    'Le Diable' => 'devil',
                    'La Tour' => 'tower',
                    'L\'Étoile' => 'star',
                    'La Lune' => 'moon',
                    'Le Soleil' => 'sun',
                    'Le Jugement' => 'judgement',
                    'Le Monde' => 'world'
                ],
                'attr' => ['class' => 'form-select'],
                'placeholder' => 'Choisissez une arcane',
                'constraints' => [
                    new NotBlank(['message' => 'L\'arcane est obligatoire'])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Character::class,
            'attr' => ['class' => 'character-form'],
        ]);
    }
}