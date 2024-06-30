<?php
namespace App\Form;

use App\Entity\Quote;
use App\Entity\Customer;
use App\Entity\Client;
use App\Entity\QuoteItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\QuoteItemType;

class QuoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quote_date', DateTimeType::class, [
                'widget' => 'single_text',
                'label'=>'quote date'
            ])
            ->add('quote_number', TextType::class,
            ['label'=>'Quote Number'
            ])
            ->add('customer', EntityType::class, [
                'class' => Customer::class,
                'choice_label' => 'name', 
                ])
            ->add('client', EntityType::class, [
                    'class' => Client::class,
                    'choice_label' => 'name', 
                ])
            ->add('total_amount', NumberType::class, [
                        'scale' => 2,
                        'label' => 'Total Amount'
                ])
            ->add('quoteItems', CollectionType::class, [
                'entry_type' => QuoteItemType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,  
                'label' => 'Quote Items'
            ])
            ->add('accepted', SubmitType::class, [
                'label' => 'Quote Accepted'
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'save'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quote::class,
        ]);
    }
}
