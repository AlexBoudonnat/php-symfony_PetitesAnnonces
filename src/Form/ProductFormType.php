<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("name", TextType::class)
            ->add("releaseOn", DateType::class, ["widget" => "single_text"])
            ->add("description", TextType::class)
            ->add("pictureName", FileType::class)
            ->add("localisation", ChoiceType::class, array(
                'choices' => array(
                    'Choose your region' => null,
                    'Auvergne-Rhône-Alpes' => 'Auvergne-Rhône-Alpes',
                    'Bourgogne-Franche-Comté' => 'Bourgogne-Franche-Comté',
                    'Bretagne' => 'Bretagne',
                    'Centre-Val de Loire' => 'Centre-Val de Loire',
                    'Corse' => 'Corse',
                    'Grand Est' => 'Grand Est',
                    'Hauts-de-France' => 'Hauts-de-France',
                    'Île-de-France' => 'Île-de-France',
                    'Normandie' => 'Normandie',
                    'Nouvelle-Aquitaine' => 'Nouvelle-Aquitaine',
                    'Occitanie' => 'Occitanie',
                    'Pays de la Loire' => 'Pays de la Loire',
                    'Provence-Alpes-Côte d\'Azur' => 'Provence-Alpes-Côte d\'Azur',
                )
            ))
            ->add("category", ChoiceType::class, array(
                'choices' => array(
                    'Choose a category' => null,
                    'Emploi' => 'emploi',
                    'Véhicules' => 'vehicules',
                    'Immobilier' => 'immobilier',
                    'Vacances' => 'vacances',
                    'Multimedia' => 'multimedia',
                    'Materiel Professionnel' => 'materielPro',
                    'Services' => 'services',
                    'Maison' => 'maison',
                    'Autres' => 'autres',
                )
            ))
            ->add("otherDetails", TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
