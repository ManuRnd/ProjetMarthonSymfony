<?php
/**
 * Created by PhpStorm.
 * User: manuel.renaudineau
 * Date: 20/12/17
 * Time: 09:34
 */

namespace App\Form;


use App\Entity\Article;
use App\Entity\Media;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ArticleType extends AbstractType
{
    protected $token;
    protected $media;


    public function __construct(TokenStorageInterface $storage)
    {
        $this->token = $storage;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Article::class,
            ]
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add("title",TextType::class, ['label' => 'Titre',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'title',
                    'type' => 'text'
                ]
            ])
            ->add("summary",TextareaType::class, ['label' => 'Accroche',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'summary',
                    'rows' => '5'
                ]
            ])
            ->add("content",TextareaType::class, ['label' => 'Content',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'content',
                    'rows' => '10'
    ]
            ])
            ->add('media', FileType::class, [
                "mapped"=>false,
                'label' => 'Media',
                'attr' => [
                    'type' => 'file',
                    'class' => 'form-control',
                    'id' => 'media'
                ]
            ])
            ->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData'])->getForm();
    }

    public function onPreSetData(FormEvent $formEvent)
    {
        $form = $formEvent->getForm();
        $article = $formEvent->getData();

        if($article->getId() === null){
            $article->setUser($this->token->getToken()->getUser());
            $form->add("save", SubmitType::class, ["label" => "Créer",
                'attr' => [
                    'class' => 'btn btn-primary',
                    'type' => 'submit'
            ]
            ]);
        } else{
            $form->add("save", SubmitType::class, ["label" => "Modifier"]);
        }
    }
}