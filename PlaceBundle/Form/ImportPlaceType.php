<?php

namespace CTRV\PlaceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ImportPlaceType extends AbstractType
{
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('placeType','entity', array(
	        		'class' => 'CTRVPlaceBundle:PlaceType',
	        		'query_builder' => function(EntityRepository $er) {
	        		return $er->createQueryBuilder('u')
	        		->orderBy('u.label', 'ASC');
	        		},
	        		'label'=>'place.import.form.placetype',
	        		'attr' =>array('class'=>'input-xlarge')
	        ))
            ->add('file','file',array('label'=>'place.import.form.file'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CTRV\PlaceBundle\Entity\ImportPlace'
        ));
    }

    public function getName()
    {
        return 'ctrv_placebundle_placetype_import_place';
    }
}
