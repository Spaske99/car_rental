<?php

namespace App\Controller;

use App\Repository\RentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class RentController extends AbstractController
{
    public $rentRepository;

    public function __construct(RentRepository $rentRepository)
    {
        $this->rentRepository = $rentRepository;
    }

    public function add(Request $request): JsonResponse
    {
        $rent = json_decode($request->getContent(), true);

        $rantedFrom = $rent['rentedFrom'];
        $rentedUntil = $rent['rentedUntil'];
        $approved = $rent['approved'];
        $user = $rent['user'];
        $car = $rent['car'];

        if (empty($rantedFrom) || empty($rentedUntil || empty($approved) || empty($user) || empty($car))) {
            throw new BadRequestHttpException('Expecting mandatory parameters!');
        }

        $this->rentRepository->add($rantedFrom, $rentedUntil, $approved, $user, $car);
        
        return new JsonResponse('Rent added!', Response::HTTP_CREATED);
    }

    public function getAll(): JsonResponse
    {
        $rents = $this->rentRepository->findAll();

        $data = [];
        foreach($rents as $rent) {
            $data[] = [
                "rentedFrom" => $rent->getRentedFrom(),
                "rentedUntil" => $rent->getRentedUntil(),
                "approved" => $rent->isApproved(),
                "user" => $rent->getUser()->getFirstName(),
                "car" => [
                    $rent->getCar()->getBrand(), 
                    $rent->getCar()->getModel()
                ]
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

}
