<?php

namespace App\Form;

use App\Entity\PropertySearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertySearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('maxPrice' , IntegerType::class , [
                'required'=> false,
                'label'=> false,
                'attr'=>[
                    'placeholder'=> 'Prix maximum'
                ]
            ])
            ->add('minSurface' , IntegerType::class , [
                'required'=> false,
                'label'=> false,
                'attr'=>[
                    'placeholder'=> 'Surface minimale'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PropertySearch::class,
            'method'=> 'get'
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
