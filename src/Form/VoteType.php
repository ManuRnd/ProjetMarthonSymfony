<?php
/**
 * Created by PhpStorm.
 * User: manuel.renaudineau
 * Date: 20/12/17
 * Time: 17:16
 */

namespace App\Form;


use App\Entity\Vote;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class VoteType extends AbstractType
{
    protected $token;


    public function __construct(TokenStorageInterface $storage)
    {
        $this->token = $storage;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Vote::class,
            ]
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {



        $builder
            ->add("value",IntegerType::class)
            ->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData'])->getForm();
    }

    public function onPreSetData(FormEvent $formEvent)
    {
        $form = $formEvent->getForm();
        $vote = $formEvent->getData();

        if($vote->getId() === null){
            $vote->setUser($this->token->getToken()->getUser());
            $form->add("save", SubmitType::class, ["label" => "Créer"]);
        } else{
            $form->add("save", SubmitType::class, ["label" => "Modifier"]);
        }
    }
}