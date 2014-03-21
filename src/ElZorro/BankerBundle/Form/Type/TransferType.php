<?php

namespace ElZorro\BankerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Transfer form type
 */
class TransferType extends AbstractType
{
    /**
     * @var array
     */
    private $categories;

    /**
     * @param array $categories
     */
    public function __construct(array $categories = null)
    {
        if ($categories !== null) {
            $this->categories = $categories;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('description', 'textarea', array(
                'attr' => array('rows' => 3),
            ))
            ->add('date', 'date', array(
                'widget' => 'single_text',
            ))
            ->add('amount', 'money')
            ->add('accountFrom', 'text', array(
                'required' => false,
            ))
            ->add('accountTo', 'text', array(
                'required' => false,
            ))
            ->add('category', 'choice', array(
                'choices' => array_combine($this->categories, $this->categories),
                'required' => false,
            ))
            ->add('save', 'submit');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'el_zorro_banker_transfer';
    }
}
