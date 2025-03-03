<?php

namespace App\Form;

use App\Entity\Candidate;
use App\Entity\Experience;
use App\Entity\Gender;
use App\Entity\JobCategory;
use DateTimeImmutable;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CandidateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'first_name',
                ],
                'label' => 'First name',
            ])

            ->add('lastName', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'last_name',
                ],
                'label' => 'Last name',
            ])

            ->add('gender', EntityType::class, [
                'class' => Gender::class,
                'choice_label' => 'name',
                'required' => false,
                'attr' => [
                    'id' => 'gender',
                ],
                'label' => 'Gender',
                'label_attr' => [
                    'class' => 'active',
                ],
                'placeholder' => 'Choose an option...',
            ])
            
            ->add('currentLocation', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'current_location',
                ],
                'label' => 'Current location (optionnal)',
            ])

            ->add('address', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'address',
                ],
                'label' => 'address',
            ])

            ->add('profilePictureFile', FileType::class,[
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '20M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Image document',
                    ])
                ],
                'attr' => [
                    'accept' => '.jpg,.jpeg,.png,.gif',
                    'id' => 'photo',
                ]
            ])

            ->add('country', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'country',
                ],
                'label' => 'Country',
            ])


            ->add('nationality', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'nationality',
                ],
                'label' => 'Nationality'
                ])

            ->add('birthdate', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'dd/MM/yyyy',
                'required' => false,
                'attr' => [
                    'class' => 'form-control datepicker',
                    'id' => 'birth_date',
                ],
                'label' => 'Birthdate',
            ])


            ->add('birthplace', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'birth_place',
                ],
                'label' => 'Birthplace (optionnal)',
            ])

            ->add('experience', EntityType::class, [
                'class' => Experience::class,
                'choice_label' => 'time',
                'required' => false,
                'attr' => [
                    'id' => 'experience',
                ],
                'label' => 'Experience',
                'label_attr' => [
                    'class' => 'active',
                ],
                'placeholder' => 'Choose an option...',
            ])

            ->add('passportFile', FileType::class,[
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '20M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid document',
                    ])
                ],
                'attr' => [
                    'accept' => '.jpg,.jpeg,.png,.gif,.pdf,.doc,.docx',
                     'id' => 'passport',
                ]
            ])


            ->add('CVFile', FileType::class,[
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '20M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid document',
                    ])
                ],
                'attr' => [
                    'accept' => '.jpg,.jpeg,.png,.gif,.pdf,.doc,.docx',
                     'id' => 'cv',
                ]
            ])

            ->add('jobcategory', EntityType::class, [
                'class' => JobCategory::class,
                'choice_label' => 'name',
                'required' => false,
                'attr' => [
                    'id' => 'job_category',
                ],
                'label' => 'Job category',
                'label_attr' => [
                    'class' => 'active',
                ],
                'placeholder' => 'Interest in job sector',
            ])

            ->add('description', TextType::class, [
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'description',
                ],
                'label' => 'Description (optionnal)',
            ])


            ->addEventListener(FormEvents::POST_SUBMIT, $this->setUpdatedAt(...))


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidate::class,
        ]);
    }


    private function setUpdatedAt(FormEvent $event): void
    {
        $candidate = $event->getData();
        $candidate->setUpdatedAt(new DateTimeImmutable());
    }
}