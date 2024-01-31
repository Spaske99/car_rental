<?php

namespace App\Controller;

use App\Repository\RentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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

        if (empty($rantedFrom) || empty($rentedUntil) || empty($approved) || empty($user) || empty($car)) {
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
                'id' => $rent->getId(),
                'rentedFrom' => $rent->getRentedFrom(),
                'rentedUntil' => $rent->getRentedUntil(),
                'approved' => $rent->isApproved(),
                'user' => [
                    'firstName' => $rent->getUser()->getFirstName(),
                    'lastName' => $rent->getUser()->getLastName(),
                    'email' => $rent->getUser()->getEmail()
                ],
                'car' => [
                    'brand' => $rent->getCar()->getBrand(), 
                    'model' => $rent->getCar()->getModel()
                ]
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    public function get($id): JsonResponse
    {
        $rent = $this->rentRepository->find($id);

        $data[] = [
            'id' => $rent->getId(),
            'rentedFrom' => $rent->getRentedFrom(),
            'rentedUntil' => $rent->getRentedUntil(),
            'approved' => $rent->isApproved(),
            'user' => [
                'firstName' => $rent->getUser()->getFirstName(),
                'lastName' => $rent->getUser()->getLastName(),
                'email' => $rent->getUser()->getEmail()
            ],
            'car' => [
                'brand' => $rent->getCar()->getBrand(), 
                'model' => $rent->getCar()->getModel()
            ]
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $rent = $this->rentRepository->find($id);
        $data = json_decode($request->getContent(), true);

        $rentedFrom = $data['rentedFrom'];
        $rentedUntil = $data['rentedUntil'];
        $car = $data['car'];

        $uodatedRent = $this->rentRepository->update($rent, $rentedFrom, $rentedUntil, $car);

        return new JsonResponse($uodatedRent->jsonSerialize(), Response::HTTP_OK);
    }

    public function delete($id): jsonResponse
    {
        $rent = $this->rentRepository->find($id);

        $this->rentRepository->delete($rent);
        
        return new JsonResponse('Rent deleted!', Response::HTTP_NO_CONTENT);
    }
}
