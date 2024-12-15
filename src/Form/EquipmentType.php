<?php

namespace App\Form;

use App\Entity\Equipment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EquipmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'équipement',
                'attr' => ['placeholder' => 'Entrez le nom de l\'équipement']
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Arme' => 'weapon',
                    'Armure' => 'armor',
                    'Accessoire' => 'accessory'
                ],
                'label' => 'Type d\'équipement'
            ])
            ->add('power', IntegerType::class, [
                'label' => 'Puissance',
                'attr' => [
                    'min' => 1,
                    'max' => 999
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => ['rows' => 3]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipment::class,
        ]);
    }
}