<?php

namespace App\Controller;

use App\Entity\Status;
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
 * @Route("/api/status")
 */
class StatusController extends AbstractController
{
    /**
     * @Route("", name="status_index", methods={"GET"})
     */
    public function index(Request $request, StatusRepository $statusRepository): Response
    {
        if ($request->query->has('laptop') && $request->query->has('employee')) {
            return $this->json($statusRepository->findBy(['laptop' => $request->query->get('laptop'), 'employee' => $request->query->get('employee')]));
        } elseif ($request->query->has('laptop')) {
            return $this->json($statusRepository->findBy(['laptop' => $request->query->get('laptop')]));
        } elseif ($request->query->has('employee')) {
            return $this->json($statusRepository->findBy(['employee' => $request->query->get('employee')]));
        }

        return $this->json($statusRepository->findAll());
    }

    /**
     * @Route("", name="status_new", methods={"POST"})
     */
    public function new(Request $request, StatusRepository $statusRepository, LaptopRepository $laptopRepository, EmployeeRepository $employeeRepository): Response
    {
        $status = new Status();
        $data = json_decode($request->getContent(), true);

        if (isset($data['employee'], $data['laptop'], $data['status'], $data['dateStart'])) {
            $laptop = $laptopRepository->find($data['laptop']);
            $last = $statusRepository->findLast($laptop);

            $dateStart = new DateTime($data['dateStart'], new DateTimeZone('Europe/Moscow'));

            $status
                ->setEmployee($employeeRepository->find($data['employee']))
                ->setLaptop($laptop)
                ->setStatus($data['status'])
                ->setDateStart($dateStart)
                ->setDateEnd(isset($data['dateEnd']) ? new DateTime($data['dateEnd'], new DateTimeZone('Europe/Moscow')) : null);

            if ($last instanceof Status) {
                $last->setDateEnd($dateStart);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($status);
            $entityManager->flush();

            return $this->json(null, Response::HTTP_CREATED);
        }

        return $this->json(null, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/{id}", name="status_show", methods={"GET"})
     */
    public function show(Status $status): Response
    {
        return $this->json($status);
    }

    /**
     * @Route("/{id}", name="status_edit", methods={"PUT"})
     */
    public function edit(Request $request, Status $status, LaptopRepository $laptopRepository, EmployeeRepository $employeeRepository): Response
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['employee'], $data['laptop'], $data['status'], $data['dateStart'], $data['dateEnd'])) {
            $status
                ->setEmployee($employeeRepository->find($data['employee']))
                ->setLaptop($laptopRepository->find($data['laptop']))
                ->setStatus($data['status'])
                ->setDateStart(new DateTime($data['dateStart']), new DateTimeZone('Europe/Moscow'))
                ->setDateEnd(new DateTime($data['dateEnd']), new DateTimeZone('Europe/Moscow'));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->json(null);
        }

        return $this->json(null, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/{id}", name="status_delete", methods={"DELETE"})
     */
    public function delete(Status $status): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($status);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
