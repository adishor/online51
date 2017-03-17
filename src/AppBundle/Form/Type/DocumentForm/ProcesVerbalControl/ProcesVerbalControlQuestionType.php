<?php

namespace AppBundle\Form\Type\DocumentForm\ProcesVerbalControl;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProcesVerbalControlQuestionType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('question', TextType::class)
          ->add('answer', ChoiceType::class, array(
              'choices' => array('DA' => 'DA', 'NU' => 'NU', 'Partial' => 'Partial', 'Nu e cazul' => 'Nu e cazul'),
              'expanded' => true,
              'multiple' => false,
          ))
          ->add('observations', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Document\ProcesVerbalControl\ProcesVerbalControlQuestion',
            'cascade_validation' => true,
        ));
    }

    public function getName()
    {
        return 'proces_verbal_control_question_type';
    }

}