<?php

namespace AppBundle\Form;


use AppBundle\Entity\League;
use AppBundle\Entity\Tournament;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LeagueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'League name',
                'attr' => [
                    'class' => ' form-control'
                ]
            ])
            ->add('ranking', IntegerType::class, [
                'label' => 'Leagu ranking',
                'attr' => [
                    'class' => ' form-control'
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save league',
                'attr' => [
                    'class' => 'btn btn-success btn-flat'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => League::class
        ]);
    }
}