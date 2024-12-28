<?php
/**
 * Created by PhpStorm.
 * User: brainstream
 * Date: 25/12/24
 * Time: 2:50 AM
 */

namespace App\Controller;


use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Attribute\Route;
use FOS\RestBundle\View\View;

class TaskApiController extends AbstractFOSRestController
{

    #[Rest\Route("/api/task", name:"api_task_list",methods:"GET")]
    public function listAction(EntityManagerInterface $entityManager)
    {

        try {
            $task = $entityManager->getRepository(Task::class)->getTaskByUser($this->getUser());

            return $this->view([
                'code' => Response::HTTP_OK,
                'data' => $task,
                'message' => 'Task get successfully.'
            ], Response::HTTP_OK);
        } catch (Exception $exception) {
            return $this->view([
                'code' => Response::HTTP_OK,
                'data' => [],
                'message' => 'There are some issue while processing.Please try after some time.'
            ], Response::HTTP_OK);
        }

    }

    #[Rest\Route("/api/task/create", name:"api_task_create",methods:"POST")]
    public function createAction(request $request,EntityManagerInterface $entityManager)
    {

        try {
            $params = json_decode($request->getContent(), true, 512);
            $objTask = new Task();
            $form = $this->createForm(TaskType::class,$objTask);
            $form->submit($params, true);
            if ($form->isSubmitted() || $form->isValid()) {
                var_dump("valid");
                die;
            }
            else{
                var_dump("errr");
                die;
            }

        } catch (Exception $exception) {
            return $this->view([
                'code' => Response::HTTP_OK,
                'data' => [],
                'message' => 'There are some issue while processing.Please try after some time.'
            ], Response::HTTP_OK);
        }

    }
}