<?php

namespace App\Form;

use App\Entity\InvoiceItem;
use App\Entity\Product;
use App\Entity\Invoice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class InvoiceItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'label' => 'Product'
            ])
            ->add('quantity', IntegerType::class, [
                'attr' => ['min' => 1],
                'label' => 'Quantity'
            ])
            ->add('price_unit', NumberType::class, [
                'scale' => 2,
                'label' => 'Unit Price'
            ])
            ->add('total_price', NumberType::class, [
                'scale' => 2,
                'label' => 'Total Price'
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save Invoice Item'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => InvoiceItem::class,
        ]);
    }
}
