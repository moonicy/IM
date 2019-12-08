<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Repository\EmployeeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/employee")
 */
class EmployeeController extends AbstractController
{
    /**
     * @Route("/", name="employee_index", methods={"GET"})
     */
    public function index(EmployeeRepository $employeeRepository): Response
    {
        return $this->json($employeeRepository->findBy());
    }

    /**
     * @Route("", name="employee_new", methods={"POST"})
     */
    public function new(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['fio'], $data['position'])) {
            $employee = new Employee();
            $employee
                ->setFio($data['fio'])
                ->setPosition($data['position']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($employee);
            $entityManager->flush();

            return $this->json($employee, Response::HTTP_CREATED);
        }

        return $this->json(null, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/{id}", name="employee_show", methods={"GET"})
     */
    public function show(Employee $employee): Response
    {
        return $this->json($employee);
    }

    /**
     * @Route("/{id}", name="employee_edit", methods={"PUT"})
     */
    public function edit(Request $request, Employee $employee): Response
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['fio'], $data['position'])) {
            $employee
                ->setFio($data['fio'])
                ->setPosition($data['position']);

            $this->getDoctrine()->getManager()->flush();

            return $this->json($employee);
        }

        return $this->json(null, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/{id}", name="employee_delete", methods={"DELETE"})
     */
    public function delete(Employee $employee): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($employee);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
