<?php

namespace ElZorro\BankerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ScheduleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('period', 'choice', array(
                'choices' => array(
                    'month' => 'month',
                    'quarter' => 'quarter',
                    'year' => 'year',
                ),
            ))
            ->add('amount', 'money', array(
            ))
            ->add('startdate', 'date', array(
                'widget' => 'single_text',
                'required' => false,
            ))
            ->add('enddate', 'date', array(
                'widget' => 'single_text',
                'required' => false,
            ))
            ->add('save', 'submit');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'el_zorro_banker_schedule';
    }
}
