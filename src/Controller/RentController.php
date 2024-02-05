<?php

namespace App\Controller;

use App\Repository\RentRepository;
use App\Service\RentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RentController extends AbstractController
{
    private $rentRepository;
    private $rentService;

    public function __construct(RentRepository $rentRepository, RentService $rentService)
    {
        $this->rentRepository = $rentRepository;
        $this->rentService = $rentService;
    }

    public function add(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (empty($data['rentedFrom']) || empty($data['rentedUntil']) || empty($data['approved']) || empty($data['user']) || empty($data['car'])) {
                throw new BadRequestHttpException('Expecting mandatory parameters!');
            }

            $this->rentService->add($data);
            
            return new JsonResponse('Rent added.', Response::HTTP_CREATED);
        } catch (BadRequestHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function getAll(): JsonResponse
    {
        try {
            $rents = $this->rentRepository->findAll();

            if (empty($rents)) {
                throw new NotFoundHttpException('No rents found!');
            }

            return new JsonResponse($this->rentService->getAll($rents), Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function get($id): JsonResponse
    {
        try {
            $rent = $this->rentRepository->find($id);

            if ($rent === null) {
                throw new NotFoundHttpException("Rent with ID $id not found!");
            }
    
            return new JsonResponse($this->rentService->get($rent), Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $rent = $this->rentRepository->find($id);

            if ($rent === null) {
                throw new NotFoundHttpException("Rent with ID $id not found!");
            }

            $data = json_decode($request->getContent(), true);

            $updatedRent = $this->rentService->update($rent, $data);

            return new JsonResponse($updatedRent, Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function delete($id): jsonResponse
    {
        try {
            $rent = $this->rentRepository->find($id);

            if ($rent === null) {
                throw new NotFoundHttpException("Rent with ID $id not found!");
            }

            $this->rentRepository->delete($rent);
            
            return new JsonResponse('Rent deleted.', Response::HTTP_NO_CONTENT);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NO_CONTENT);
        }
    }
}
