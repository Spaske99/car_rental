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

    // CREATE RENT
    public function create(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $this->rentService->create($data);
            
            return new JsonResponse('Rent created.', Response::HTTP_CREATED);
        } catch (BadRequestHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    // GET_ALL RENTS
    public function getAll(): JsonResponse
    {
        try {
            return new JsonResponse($this->rentService->getAll(), Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    // GET RENT
    public function get($id): JsonResponse
    {
        try {    
            return new JsonResponse($this->rentService->get($id), Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    // UPDATE RENT
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $updatedRent = $this->rentService->update($id, $data);

            return new JsonResponse($updatedRent, Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    // DELETE RENT
    public function delete($id): jsonResponse
    {
        try {
            $this->rentService->delete($id);
            
            return new JsonResponse('Rent deleted.', Response::HTTP_NO_CONTENT);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NO_CONTENT);
        }
    }
}
