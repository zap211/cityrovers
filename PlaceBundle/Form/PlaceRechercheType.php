<?php

namespace CTRV\PlaceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class PlaceRechercheType extends AbstractType {

public function buildForm(FormBuilderInterface $builder, array $options){

	$builder->add('motcle','text', array('label' => 'place.rechercheForm.rechercher','required'=>false))
	;
}

public function getName() {
	return 'placerecherche';
}

}
