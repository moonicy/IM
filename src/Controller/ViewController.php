<?php

namespace App\Controller;

use App\Repository\EmployeeRepository;
use App\Repository\LaptopRepository;
use App\Repository\StatusRepository;
use DateTime;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class ViewController extends AbstractController
{
    /**
     * @Route("", name="index_view", methods={"GET"})
     */
    public function index(EmployeeRepository $employeeRepository): Response
    {
        return $this->render('index.html.twig', [
            'employees' => $employeeRepository->findBy([], ['id' => 'DESC']),
        ]);
    }

    /**
     * @Route("laptop", name="laptop_view", methods={"GET"})
     */
    public function laptop(Request $request, LaptopRepository $laptopRepository): Response
    {
        $filter = [];

        if ($request->query->has('firm')) {
            $filter['firm'] = $request->query->get('firm');
        }

        if ($request->query->has('dateBuy')) {
            $filter['dateBuy'] = new DateTime($request->query->get('dateBuy'), new DateTimeZone('Europe/Moscow'));
        }

        return $this->render('laptop.html.twig', [
            'laptops' => $laptopRepository->findBy($filter, ['id' => 'DESC']),
        ]);
    }

    /**
     * @Route("status", name="statuses_view", methods={"GET"})
     */
    public function statuses(StatusRepository $statusRepository, LaptopRepository $laptopRepository, EmployeeRepository $employeeRepository): Response
    {
        return $this->render('status.html.twig', [
            'employees' => $employeeRepository->findBy([], ['id' => 'DESC']),
            'statuses' => $statusRepository->findBy([], ['id' => 'DESC']),
        ]);
    }

    /**
     * @Route("laptop/{id}/status", name="laptop_status_view", methods={"GET"})
     */
    public function status(Request $request, StatusRepository $statusRepository, LaptopRepository $laptopRepository, EmployeeRepository $employeeRepository): Response
    {
        return $this->render('status.html.twig', [
            'employees' => $employeeRepository->findBy([], ['id' => 'DESC']),
            'laptop' => $laptopRepository->find($request->get('id')),
            'statuses' => $statusRepository->findBy(['laptop' => $request->get('id')], ['id' => 'DESC']),
        ]);
    }
}