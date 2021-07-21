<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Form\Type\PriceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du produit',
                'attr' => ['class' => 'form-control', 'placeholder' => 'Tapez le nom du produit']
            ])
            ->add('shortDescription', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Tapez une description asser courte mais parlante pour le visiteur'
                ]
            ])
            ->add('price', PriceType::class, [
                'label' => 'Prix du produit',
                'attr' => [
                    'placeholder' => 'Tapez le prix du produit',
                    'class' => 'form-controler'
                ]
            ])
            ->add('category', EntityType::class, [
                    'label' => 'Catégorie',
                    'attr' => ['class' => 'form-control'],
                    'placeholder' => 'Choisir une catégorie',
                    'class' => Category::class,
                    'choice_label' => 'name'
                ])
            ->add('mainPicture', UrlType::class, [
                'label' => 'Image du produit',
                'attr' => ['placeholder' => 'Tapez une URL d\'image !']
            ]);
            
            // $builder->get('price')->addModelTransformer(new CallbackTransformer(
            //     function($value){
            //         if ($value === null){
            //             return;
            //         }
            //         return $value / 100;
            //     },
            //     function($value) {
            //         if ($value === null){
            //             return;
            //         }
            //         return $value * 100;

            //     }
            // ));
            
            // $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event){

            //     /** @var Product */
            //     $product = $event->getData();
                
            //     if ($product->getPrice() !== null){
            //         $product->setPrice($product->getPrice() / 100);
            //     }
                
            // });
            
            // $builder->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) {

            //     /** @var Product */
            //     $product = $event->getData();
                
            //     if ($product->getPrice() !== null) {
            //         $product->setPrice($product->getPrice() * 100);
            //     }
            // });
        }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
