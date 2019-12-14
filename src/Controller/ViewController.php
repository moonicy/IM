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
    const DEFAULT_LIMIT = 10;
    const DEFAULT_OFFSET = 0;

    /**
     * @Route("", name="index_view", methods={"GET"})
     */
    public function index(Request $request, EmployeeRepository $employeeRepository): Response
    {
        $pagination = $this->pagination($employeeRepository, $request, count($employeeRepository->findAll()));

        return $this->render('index.html.twig', [
            'pagination' => $pagination,
            'employees' => $employeeRepository->findBy([], ['id' => 'DESC'], self::DEFAULT_LIMIT, $pagination['offset']),
        ]);
    }

    /**
     * @Route("laptop", name="laptop_view", methods={"GET"})
     */
    public function laptop(Request $request, LaptopRepository $laptopRepository, EmployeeRepository $employeeRepository): Response
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

        $collection = $laptopRepository->findBy($filter, ['id' => 'DESC'], self::DEFAULT_LIMIT, $this->getOffset($request));
        $pagination = $this->pagination($collection, $request, count($laptopRepository->findBy($filter)));

        return $this->render('laptop.html.twig', [
            'pagination' => $pagination,
            'laptops' => $collection,
            'employees' => $employeeRepository->findBy([], ['id' => 'DESC']),
        ]);
    }

    /**
     * @Route("status", name="statuses_view", methods={"GET"})
     */
    public function statuses(Request $request, StatusRepository $statusRepository, EmployeeRepository $employeeRepository): Response
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

        $collection = $statusRepository->findByFilter($filter, self::DEFAULT_LIMIT, $this->getOffset($request));
        $pagination = $this->pagination($collection, $request, count($statusRepository->findByFilter($filter)));

        return $this->render('status.html.twig', [
            'pagination' => $pagination,
            'employees' => $employeeRepository->findBy([], ['id' => 'DESC']),
            'statuses' => $collection,
        ]);
    }

    private function pagination($collection, Request $request, $count)
    {
        $pages = ceil($count / self::DEFAULT_LIMIT);

        $offset = $this->getOffset($request);

        $pagination = [
            'offset' => $offset,
            'pages' => $pages,
            'current' => ceil($offset / self::DEFAULT_LIMIT) + 1,
            'limit' => self::DEFAULT_LIMIT,
            'isShow' => $count > self::DEFAULT_LIMIT,
        ];

        $previous = $offset - self::DEFAULT_LIMIT;
        if ($previous >= self::DEFAULT_OFFSET) {
            $pagination['previous'] = $previous;
        }

        $next = $offset + self::DEFAULT_LIMIT;
        if ($next < $count) {
            $pagination['next'] = $next;
        }

        return $pagination;
    }

    private function getOffset(Request $request)
    {
        if ($request->query->has('offset')) {
            $offset = $request->query->get('offset');
        } else {
            $offset = self::DEFAULT_OFFSET;
        }

        return $offset;
    }
}