<?php

namespace App\Form;

use App\Entity\Program;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use App\Entity\Actor; //ajouté Q15_ajout des acteurs au formualire
use Symfony\Bridge\Doctrine\Form\Type\EntityType; // ajout Q15

class ProgramType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('poster', TextType::class)
            ->add('synopsis',TextType::class)
            ->add('country', TextType::class)
            ->add('year', IntegerType::class)
            ->add('category', null,['choice_label' =>'name'])
            //Q15 ajout des acteurs avec EntityType
            ->add('actors', EntityType::class, [
                'class' => Actor::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'by_reference' =>false,
                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Program::class,
        ]);
    }
}
