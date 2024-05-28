<?php

namespace App\Form;

use App\Entity\Invoice;
use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DecimalType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class InvoiceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('customer', EntityType::class, [
                'class' => Customer::class,
                'choice_label' => function(Customer $customer) {
                    return $customer->getName();
                },
                'label' => 'Customer',
                'placeholder' => 'Select a customer',
            ])
            ->add('invoice_date', DateTimeType::class, [
                'widget' => 'single_text', 
                'label' => 'Invoice Date',
            ])
            ->add('due_date', DateTimeType::class, [
                'widget' => 'single_text', 
                'label' => 'Due Date',
            ])
            ->add('total_amount', DecimalType::class, [
                'scale' => 2,
                'label' => 'Total Amount ($)'
            ])
            ->add('invoiceItems', CollectionType::class, [
                'entry_type' => InvoiceItemType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'label' => 'Invoice Items',
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
