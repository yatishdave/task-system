<?php
/**
 * Created by PhpStorm.
 * User: brainstream
 * Date: 25/12/24
 * Time: 2:50 AM
 */

namespace App\Controller\Panel;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;

class TaskApiController extends AbstractFOSRestController
{

    /**
     * List
     *
     * @Rest\Route("/api/task", name="api_get_task", methods={"GET"})
     *
     *
     * @return View
     */
    public function listAction()
    {
        die("Dave");
    }
}