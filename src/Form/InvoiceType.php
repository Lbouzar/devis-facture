<?php

namespace App\Form;

use App\Entity\Invoice;
use App\Entity\Customer;
use App\Entity\Client;
use App\Entity\InvoiceItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType; 
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class InvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('customer', EntityType::class, [
            'class' => Customer::class,
            'choice_label' => 'name', 
        ])
        ->add('client', EntityType::class, [
            'class' => Client::class,
            'choice_label' => 'name', 
        ])
        ->add('invoice_number', TextType::class,['label'=>'Invoice Number'])

            ->add('invoice_date', DateTimeType::class, [
                'widget' => 'single_text', 
                'label' => 'Invoice Date',
            ])
            ->add('due_date', DateTimeType::class, [
                'widget' => 'single_text', 
                'label' => 'Due Date',
            ])
            ->add('total_amount', NumberType::class, [
                'scale' => 2,
                'label' => 'Total Amount'
            ])
            ->add('invoiceItems', CollectionType::class, [
                'entry_type' => InvoiceItem::class,
               
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Save Invoice'
            ]);
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Invoice::class,
        ]);
    }
}
