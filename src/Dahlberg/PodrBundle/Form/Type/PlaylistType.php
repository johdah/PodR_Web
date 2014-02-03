<?php
// src/Acme/TaskBundle/Form/Type/PlaylistType.php
namespace Dahlberg\PodrBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PlaylistType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', array(
            'required' => true
            ))
            ->add('icon', 'choice', array(
                'required' => true,
                'choices' => array(
                    "heart"     => "Heart",
                    "like"      => "Like",
                    "marker"    => "Marker",
                )
            ))
            ->add('style', 'choice', array(
                'required' => true,
                'choices' => array(
                    "blue"                  => "Blue",
                    "blue-glowing"          => "Blue Glowing",
                    "darkblue"              => "Dark Blue",
                    "darkblue-glowing"      => "Dark Blue Glowing",
                    "darkgreen"             => "Dark Green",
                    "darkgreen-glowing"     => "Dark Green Glowing",
                    "darkpurple"            => "Dark Purple",
                    "darkpurple-glowing"    => "Dark Purple Glowing",
                    "darkred"               => "Dark Red",
                    "darkred-glowing"       => "Dark Red Glowing",
                    "darkyellow"            => "Dark Yellow",
                    "darkyellow-glowing"    => "Dark Yellow Glowing",
                    "green"                 => "Green",
                    "green-glowing"         => "Green Glowing",
                    "purple"                => "Purple",
                    "purple-glowing"        => "Purple Glowing",
                    "red"                   => "Red",
                    "red-glowing"           => "Red Glowing",
                    "yellow"                => "Yellow",
                    "yellow-glowing"        => "Yellow Glowing",
                )
            ))
            ->add('save', 'submit');
    }

    public function getName()
    {
        return 'playlistform';
    }
}