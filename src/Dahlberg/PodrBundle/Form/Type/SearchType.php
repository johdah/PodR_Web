<?php
// src/Acme/TaskBundle/Form/Type/SearchType.php
namespace Dahlberg\PodrBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('searchterm', 'text');
        $builder->add('scope', 'choice', array(
            'choices'       => array(
                'episode'       => 'Episodes',
                'podcast'       => 'Podcasts'),
            'required'      => false,
            'expanded'      => true,
            'empty_value'   => 'Both'
        ));
        $builder->add('search', 'submit');
    }

    public function getName()
    {
        return 'searchform';
    }
}