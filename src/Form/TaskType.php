<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\TaskCategory;
use App\Entity\TaskStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class TaskType extends AbstractType
{

    protected $isCsrfProtection;
    protected  $isAllowExtraFields;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->isCsrfProtection = $options['csrf_protection'];
        $this->isAllowExtraFields = $options['csrf_protection'];
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new Length([
                        'minMessage' => 'title should be at least {{ limit }} characters',
                        'max' => 255,
                    ]),
                ],
            ])
            ->add('taskDescription', TextareaType::class)
            ->add('dueDate', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('status', EntityType::class, [
                'class' => TaskStatus::class,
                'choice_label' => 'name',
                'invalid_message' => '"{{ value }}" is not valid in status.'
            ])
            ->add('category', EntityType::class, [
                'class' => TaskCategory::class,
                'choice_label' => 'name',
                'invalid_message' => '"{{ value }}" is not valid in category.'
            ])
            ->add('save', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
            'csrf_protection' => $this->isCsrfProtection,
            'allow_extra_fields' =>$this->isAllowExtraFields
        ]);
    }
}
