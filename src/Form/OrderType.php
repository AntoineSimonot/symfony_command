<?php

namespace App\Form;

use App\Entity\Order;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('client_name')
            ->add('clientEmail')
            ->add('address')
            ->add('phone')
            ->add('maximum_date')
            ->add('products', CollectionType::class, [
                'entry_type' => ProductType::class,
                'label' => false,
                'allow_add' => true,
                'by_reference' => false,
                'required' => true
            ])
            ->add('payments', CollectionType::class, [
                'entry_type' => PaymentType::class,
                'label' => false,
                'allow_add' => true,
                'by_reference' => false,
            ])
            ->add('submit', 'Symfony\Component\Form\Extension\Core\Type\SubmitType');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
