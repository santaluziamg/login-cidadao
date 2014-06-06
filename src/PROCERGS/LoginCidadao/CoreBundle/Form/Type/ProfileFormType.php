<?php

namespace PROCERGS\LoginCidadao\CoreBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class ProfileFormType extends BaseType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //parent::buildForm($builder, $options);

        $builder/* ->add('username', null,
                        array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle')) */
                ->add('email', 'email',
                        array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
                ->add('firstName', 'text',
                        array('label' => 'form.firstName', 'translation_domain' => 'FOSUserBundle'))
                ->add('surname', 'text',
                        array('label' => 'form.surname', 'translation_domain' => 'FOSUserBundle'))
                ->add('cep', null,
                        array('required' => false, 'label' => 'form.cep', 'translation_domain' => 'FOSUserBundle'))
                ->add('cpf', null,
                        array('required' => false, 'label' => 'form.cpf', 'translation_domain' => 'FOSUserBundle'))
                ->add('birthdate', 'birthday',
                        array(
                    'required' => false,
                    'format' => 'dd MMMM yyyy',
                    'widget' => 'choice',
                    'years' => range(date('Y'), 1898),
                    'label' => 'form.birthdate',
                    'translation_domain' => 'FOSUserBundle')
                )
                ->add('mobile', null,
                        array('required' => false, 'label' => 'form.mobile', 'translation_domain' => 'FOSUserBundle'))
                ->add('image')
                ->add('voterRegistration', 'text', array('required' => false, 'label' => 'form.voterRegistration', 'translation_domain' => 'FOSUserBundle'))
                ;

    }

    public function getName()
    {
        return 'procergs_person_profile';
    }

}
