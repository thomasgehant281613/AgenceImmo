<?php

namespace App\Form;

use App\Entity\Option;
use App\Entity\Property;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('surface')
            ->add('floor')
            ->add('rooms')
            ->add('bedrooms')
            ->add('price')
            ->add('heat',ChoiceType::class, [
                'choices' => $this->getChoices()
            ])
            ->add('options', EntityType::class,[
                'class'=> Option::class,
                'choice_label'=>'name',
                'multiple'=> true
            ])
            ->add('imageFile', FileType::class, [
                'required' => false
            ])
            ->add('sold')
            ->add('city')
            ->add('address')
            ->add('postal_code')



        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
            'translation_domain' => 'forms'
        ]);
    }

    private function getChoices()
    {
        $choices = Property::HEAT;
        $output = [];
        foreach($choices as $k => $v){
            $output[$v] = $k;
        }
        return $output;
    }
}