<?php

namespace App\Controller\Panel;

use App\Entity\Task;
use App\Form\TaskType;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Omines\DataTablesBundle\Column\DateTimeColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;


class TaskController extends AbstractController
{
    private $factory;


    public function __construct(DataTableFactory $factory)
    {
        $this->factory = $factory;
    }

    #[Route('/task', name: 'app_task')]
    public function index(Request $request, DataTableFactory $dataTableFactory): Response
    {
        $table = $this->factory->create()
            ->add('title', TextColumn::class, ["label" => "Title", "searchable" => true])
            ->add('dueDate', DateTimeColumn::class, ["label" => "Due Date", "format" => "d-m-Y"])
            ->add('status', TextColumn::class, ["label" => "Status", 'field' => 'status.name'])
            ->add('category', TextColumn::class, ["label" => "Category", 'field' => 'category.name'])
            ->createAdapter(ORMAdapter::class, [
                'entity' => Task::class,
            ])->handleRequest($request);
        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('task/list.html.twig', ['datatable' => $table]);
    }


    #[Route('/task/add', name: 'app_task_add')]
    public function add(Request $request, EntityManagerInterface $entityManager)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($this->getUser());
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
    }
}
