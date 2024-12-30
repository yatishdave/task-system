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
use App\Service\CommonUtility;
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
    public function createAction(request $request, EntityManagerInterface $entityManager, CommonUtility $commonUtility)
    {
        try {
            $params = json_decode($request->getContent(), true, 512);
            $objTask = new Task();
            $objTask->setUser($this->getUser());
            $form = $this->createForm(TaskType::class, $objTask, ['csrf_protection' => false, 'allow_extra_fields' => false]);
            $form->submit($params, true);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($objTask);
                $entityManager->flush();
                return $this->view([
                    'code' => Response::HTTP_OK,
                    'message' => 'Task added successfully.'
                ], Response::HTTP_OK);
            }

            $errors = $commonUtility->getErrorMessages($form);
            return $this->view([
                'code' => Response::HTTP_BAD_REQUEST,
                'data' => [],
                'message' => $errors,
            ], Response::HTTP_BAD_REQUEST);


        } catch (Exception $exception) {
            return $this->view([
                'code' => Response::HTTP_OK,
                'data' => [],
                'message' => 'There are some issue while processing.Please try after some time.'
            ], Response::HTTP_OK);
        }
    }

    #[Rest\Route("/api/task/edit", name:"api_task_update",methods:"POST")]
    public function editAction(request $request, EntityManagerInterface $entityManager, CommonUtility $commonUtility)
    {

        try {
            $params = json_decode($request->getContent(), true, 512);
            if (isset($params['id'])) {
                $objTask = $entityManager->getRepository(Task::class)
                    ->findOneBy(['id' => $params['id'], 'user' => $this->getUser()]);
                if ($objTask instanceof Task) {
                    $form = $this->createForm(TaskType::class, $objTask, ['csrf_protection' => false, 'allow_extra_fields' => true]);
                    $form->submit($params, true);

                    if ($form->isSubmitted() && $form->isValid()) {
                        $entityManager->persist($objTask);
                        $entityManager->flush();
                        return $this->view([
                            'code' => Response::HTTP_OK,
                            'message' => 'Task updated successfully.'
                        ], Response::HTTP_OK);
                    }
                    $errors = $commonUtility->getErrorMessages($form);
                    return $this->view([
                        'code' => Response::HTTP_BAD_REQUEST,
                        'data' => [],
                        'message' => $errors,
                    ], Response::HTTP_BAD_REQUEST);
                }
                return $this->view([
                    'code' => Response::HTTP_BAD_REQUEST,
                    'data' => [],
                    'message' => 'Task not found.'
                ], Response::HTTP_BAD_REQUEST);
            }

            return $this->view([
                'code' => Response::HTTP_BAD_REQUEST,
                'data' => [],
                'message' => 'Required parameter missing.'
            ], Response::HTTP_BAD_REQUEST);


        } catch (Exception $exception) {
            return $this->view([
                'code' => Response::HTTP_OK,
                'data' => [],
                'message' => 'There are some issue while processing.Please try after some time.'
            ], Response::HTTP_OK);
        }
    }

    #[Rest\Route("/api/task/delete", name:"api_task_delete",methods:"POST")]
    public function deleteAction(request $request, EntityManagerInterface $entityManager)
    {
        try {
            $params = json_decode($request->getContent(), true, 512);
            if (isset($params['id'])) {
                $objTask = $entityManager->getRepository(Task::class)
                    ->findOneBy(['id' => $params['id'], 'user' => $this->getUser()]);
                if ($objTask instanceof Task) {
                    $entityManager->remove($objTask);
                    $entityManager->flush();
                    return $this->view([
                        'code' => Response::HTTP_OK,
                        'message' => 'Task delete successfully.'
                    ], Response::HTTP_OK);
                }
                return $this->view([
                    'code' => Response::HTTP_BAD_REQUEST,
                    'data' => [],
                    'message' => 'Task not found.'
                ], Response::HTTP_BAD_REQUEST);
            }

            return $this->view([
                'code' => Response::HTTP_BAD_REQUEST,
                'data' => [],
                'message' => 'Required parameter missing.'
            ], Response::HTTP_BAD_REQUEST);
        } catch (Exception $exception) {
            return $this->view([
                'code' => Response::HTTP_OK,
                'data' => [],
                'message' => 'There are some issue while processing.Please try after some time.'
            ], Response::HTTP_OK);
        }
    }


}