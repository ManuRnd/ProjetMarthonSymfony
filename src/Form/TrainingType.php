<?php
/**
 * Created by PhpStorm.
 * User: manuel.renaudineau
 * Date: 21/12/17
 * Time: 01:48
 */

namespace App\Form;


use App\Entity\Media;
use App\Entity\Training;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class TrainingType extends AbstractType
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
                'data_class' => Training::class,
            ]
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add("name",TextType::class, ['label' => 'Name',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'name',
                    'type' => 'text'
                ]
            ])
            ->add("steps", TextareaType::class, ['label' => 'Etapes',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'steps',
                    'rows' => '10'
                ]
            ])
            ->add("muscles",TextareaType::class, ['label' => 'Muscles',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'muscles',
                    'rows' => '10'
                ]
            ])
            ->add("difficulty",IntegerType::class, ['label' => 'Difficulte',
        'attr' => [
            'class' => 'form-control',
            'id' => 'difficulty',
            'type' => 'Number'
        ]
    ])
            ->add("trainingTime", TimeType::class, ['label' => 'Duree',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'trainingTime',
                    'type' => 'Date'
                ]
            ])
            ->add("restTime", TimeType::class, ['label' => 'Temps restant',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'restTime',
                    'type' => 'Date'
                ]
            ])

            ->add("materials", TextareaType::class, ['label' => 'Materiel',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'materials',
                    'rows' => '10'
                ]
            ])

            ->add("astuce", TextareaType::class, ['label' => 'Astuce',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'astuce',
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
        $training = $formEvent->getData();

        $form->add("save", SubmitType::class, ["label" => "Créer",
            'attr' => [
                'class' => 'btn btn-primary',
                'type' => 'submit'
            ]
        ]);


    }
}