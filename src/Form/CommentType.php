<?php
/**
 * Created by PhpStorm.
 * User: manuel.renaudineau
 * Date: 20/12/17
 * Time: 13:59
 */

namespace App\Form;


use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class CommentType extends AbstractType
{
    protected $token;
    protected $training;

    public function __construct(TokenStorageInterface $storage)
    {
        $this->token = $storage;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Comment::class,
            ]
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add("content",TextType::class)
            ->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData'])->getForm();
    }

    public function onPreSetData(FormEvent $formEvent)
    {
        $form = $formEvent->getForm();
        $comment = $formEvent->getData();

        if($comment->getId() === null){
            $comment->setUser($this->token->getToken()->getUser());
            $comment->setCreatedAt(new \DateTime());
            $comment->setUpdatedAt(new \DateTime());
            $form->add("save", SubmitType::class, ["label" => "Créer"]);
        } else{
            $comment->setUpdatedAt(new \DateTime());
            $form->add("save", SubmitType::class, ["label" => "Modifier"]);
        }
    }
}