<?php

namespace App\Controller\Panel;

use App\Entity\Task;
use App\Entity\TaskStatus;
use App\Entity\User;
use App\Form\TaskType;

use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORM\SearchCriteriaProvider;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\Column\TwigStringColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\DBAL\Query\QueryBuilder as dql;


class TaskController extends AbstractController
{
    private $factory;


    public function __construct(DataTableFactory $factory)
    {
        $this->factory = $factory;
    }

    #[Route('/task', name: 'app_task')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $currentStatus = $request->query->get('current_status');
        $objStatus = $entityManager->getRepository(TaskStatus::class)->findOneBy(['name'=>$currentStatus]);

        $user = $this->getUser();
        $table = $this->factory->create()
            ->add('title', TextColumn::class, ["label" => "Title", "orderable" => false])
            ->add('dueDate', DateTimeColumn::class, ["label" => "Due Date", "format" => "d-m-Y"])
            ->add('status', TextColumn::class, ["label" => "Status", 'field' => 't.status.name', "orderable" => false])
            ->add('category', TextColumn::class, ["label" => "Category", 'field' => 't.category.name', "orderable" => false])
            ->add('link', TwigStringColumn::class, ["label" => "Action",
                'template' => '<a href="{{ path(\'app_task_edit\',{id: row.id}) }}">Edit</a>&nbsp;
                        <a data-id="{{row.id}}" class="delete-task-link">Delete</a>',
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Task::class,
                'query' => function (QueryBuilder $qb) use ($user, $objStatus) {
                    $qb
                        ->select('t')
                        ->from(Task::class, 't')
                        ->andWhere('t.user=:user')
                        ->setParameter('user', $user);

                    if ($objStatus) {

                        $qb->andWhere('t.status =:status')
                            ->setParameter('status', $objStatus);
                    }

                }
            ])
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }
        $taskStatus = $entityManager->getRepository(TaskStatus::class)->findAll();
        $status = [];
        foreach ($taskStatus as $key => $val) {
            $status[] = $val->getName();
        }
        return $this->render('task/list.html.twig', ['datatable' => $table, 'currentStatus' => $currentStatus, 'allStatus' => $status]);
    }


    #[Route('/task/add', name: 'app_task_add')]
    public function add(Request $request, EntityManagerInterface $entityManager)
    {
        try {
            $task = new Task();
            $task->setUser($this->getUser());
            $form = $this->createForm(TaskType::class, $task, ['csrf_protection' => true, 'allow_extra_fields' => false]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $entityManager->persist($task);
                $entityManager->flush();

                $this->addFlash(
                    'success',
                    'Task added successfully.'
                );

                return $this->redirectToRoute('app_task');
            }

            return $this->render('task/add.html.twig', [
                'task' => $task,
                'form' => $form,
            ]);
        } catch (Exception $exception) {

            $this->addFlash(
                'error',
                'There are some issue while processing.Please try again.'
            );

            return $this->redirectToRoute('app_task');
        }

    }

    #[Route('/task/edit/{id}', name: 'app_task_edit')]
    public function edit(Request $request, Task $task, EntityManagerInterface $entityManager)
    {
        try {
            if ($task instanceof Task && $task->getUser()->getId() == $this->getUser()->getId()) {
                $form = $this->createForm(TaskType::class, $task, ['csrf_protection' => true, 'allow_extra_fields' => false]);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($task);
                    $entityManager->flush();

                    $this->addFlash(
                        'success',
                        'Task updated successfully.'
                    );

                    return $this->redirectToRoute('app_task');
                }

                return $this->render('task/add.html.twig', [
                    'task' => $task,
                    'form' => $form,
                ]);
            }

        } catch (Exception $exception) {
            $this->addFlash(
                'error',
                'There are some issue while processing.Please try again.'
            );
            return $this->redirectToRoute('app_task');
        }
    }

    #[Route('/task/delete', name: 'app_task_delete',methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $entityManager)
    {
        try {
            $id = $request->request->get('id', 0);
            $objTask = $entityManager->getRepository(Task::class)->findOneBy(['id' => $id, 'user' => $this->getUser()->getId()]);
            if ($objTask) {
                $entityManager->remove($objTask);
                $entityManager->flush();

                return new JsonResponse(['success' => true, 'message' => 'Task deleted successfully.']);
            }
            return new JsonResponse(['success' => false, 'message' => 'Task not found.']);

        } catch (Exception $exception) {
            return new JsonResponse(['success' => false, 'message' => 'There are some issue while processing.Please try again.']);
        }

    }
}
