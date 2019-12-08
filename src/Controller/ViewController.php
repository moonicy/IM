<?php


namespace App\Controller;


use App\Repository\EmployeeRepository;
use App\Repository\LaptopRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            'page' => 'index',
            'employees' => $employeeRepository->findBy([], ['id' => 'DESC']),
        ]);
    }

    /**
     * @Route("laptop", name="laptop_view", methods={"GET"})
     */
    public function laptop(LaptopRepository $laptopRepository): Response
    {
        return $this->render('laptop.html.twig', [
            'page' => 'index',
            'laptops' => $laptopRepository->findBy([], ['id' => 'DESC']),
        ]);
    }
}