<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\TaskCategory;
use App\Entity\TaskStatus;
use App\Entity\User;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a Title',
                    ]),
                    new Length([
                        'minMessage' => 'title should be at least {{ limit }} characters',
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('taskDescription', TextareaType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a Task Description',
                    ]),

                ],
            ])
            ->add('dueDate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('status', EntityType::class, [
                'class' => TaskStatus::class,
                'choice_label' => 'name',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please select status',
                    ]),

                ],
            ])
            ->add('category', EntityType::class, [
                'class' => TaskCategory::class,
                'choice_label' => 'name',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please select category',
                    ]),

                ],
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
