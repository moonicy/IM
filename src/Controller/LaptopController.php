<?php

namespace App\Controller;

use App\Entity\Laptop;
use App\Repository\LaptopRepository;
use DateInterval;
use DateTime;
use DateTimeZone;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/laptop")
 */
class LaptopController extends AbstractController
{
    /**
     * @Route("", name="laptop_index", methods={"GET"})
     */
    public function index(LaptopRepository $laptopRepository): Response
    {
        return $this->json($laptopRepository->findAll());
    }

    /**
     * @Route("", name="laptop_new", methods={"POST"})
     */
    public function new(Request $request): Response
    {
        $laptop = new Laptop();
        $data = json_decode($request->getContent(), true);

        if (isset($data['number'], $data['firm'], $data['model'], $data['dateBuy'], $data['interval'], $data['numberCores'], $data['memory'], $data['disk'])) {
            $laptop
                ->setNumber($data['number'])
                ->setFirm($data['firm'])
                ->setModel($data['model'])
                ->setDateBuy(new DateTime($data['dateBuy'], new DateTimeZone('Europe/Moscow')))
                ->setInterval(new DateInterval($data['interval']))
                ->setNumberCores($data['numberCores'])
                ->setMemory($data['memory'])
                ->setDisk($data['disk']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($laptop);
            $entityManager->flush();

            return $this->json($laptop, Response::HTTP_CREATED);
        }

        return $this->json(null, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/{id}", name="laptop_show", methods={"GET"})
     */
    public function show(Laptop $laptop): Response
    {
        return $this->json($laptop);
    }

    /**
     * @Route("/{id}", name="laptop_edit", methods={"PUT"})
     */
    public function edit(Request $request, Laptop $laptop): Response
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['number'], $data['firm'], $data['model'], $data['dateBuy'], $data['interval'], $data['numberCores'], $data['memory'], $data['disk'])) {
            $laptop
                ->setNumber($data['number'])
                ->setFirm($data['firm'])
                ->setModel($data['model'])
                ->setDateBuy(new DateTime($data['dateBuy']))
                ->setInterval(new DateInterval($data['interval']))
                ->setNumberCores($data['numberCores'])
                ->setMemory($data['memory'])
                ->setDisk($data['disk']);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->json($laptop, Response::HTTP_CREATED);
        }

        return $this->json(null, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/{id}", name="laptop_delete", methods={"DELETE"})
     */
    public function delete(Laptop $laptop): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($laptop);
        $entityManager->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
