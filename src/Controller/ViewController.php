<?php

namespace App\Controller;

use App\Repository\EmployeeRepository;
use App\Repository\LaptopRepository;
use App\Repository\StatusRepository;
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
    public function laptop(LaptopRepository $laptopRepository): Response
    {
        return $this->render('laptop.html.twig', [
            'laptops' => $laptopRepository->findBy([], ['id' => 'DESC']),
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