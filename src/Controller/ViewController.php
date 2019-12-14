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

        if ($request->query->has('number')) {
            $filter['number'] = $request->query->get('number');
        }

        return $this->render('laptop.html.twig', [
            'laptops' => $laptopRepository->findBy($filter, ['id' => 'DESC']),
        ]);
    }

    /**
     * @Route("status", name="statuses_view", methods={"GET"})
     */
    public function statuses(Request $request, StatusRepository $statusRepository, LaptopRepository $laptopRepository, EmployeeRepository $employeeRepository): Response
    {
        $filter = [];

        if ($request->query->has('firm')) {
            $filter['laptop']['firm'] = $request->query->get('firm');
        }

        if ($request->query->has('number')) {
            $filter['laptop']['number'] = $request->query->get('number');
        }

        if ($request->query->has('status')) {
            $filter['status'] = $request->query->get('status');
        }

        if ($request->query->has('employee')) {
            $filter['employee'] = $request->query->get('employee');
        }

        if ($request->query->has('dateStart')) {
            $filter['dateStart'] = new DateTime($request->query->get('dateStart'), new DateTimeZone('Europe/Moscow'));
        }

        if ($request->query->has('dateEnd')) {
            $filter['dateEnd'] = new DateTime($request->query->get('dateEnd'), new DateTimeZone('Europe/Moscow'));
        }

        if ($request->query->has('relevant')) {
            $filter['relevant'] = $request->query->get('relevant');
        }

        if ($request->query->has('outdated')) {
            $filter['outdated'] = $request->query->get('outdated');
        }

        return $this->render('status.html.twig', [
            'employees' => $employeeRepository->findBy([], ['id' => 'DESC']),
            'statuses' => $statusRepository->findByFilter($filter, ['id' => 'DESC']),
        ]);
    }
}