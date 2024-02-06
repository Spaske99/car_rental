<?php

namespace App\Controller;

use App\Repository\CarRepository;
use App\Service\CarService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CarController extends AbstractController
{
    private $carRepository;
    private $carService;

    public function __construct(CarRepository $carRepository, CarService $carService) 
    {
        $this->carRepository = $carRepository;
        $this->carService = $carService;
    }

    // CREATE CAR
    public function create(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            
            $this->carService->create($data);

            return new JsonResponse('Car created.', Response::HTTP_CREATED);  
        } catch (BadRequestHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);  
        }
    }

    // GET_ALL CARS
    public function getAll(): JsonResponse
    {
        try {
            return new JsonResponse($this->carService->getAll(), Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    // GET CAR
    public function get($id): JsonResponse
    {
        try {
            return new JsonResponse($this->carService->get($id), Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    // UPDATE CAR
    public function update(Request $request, $id): JsonResponse
    {
        try {          
            $data = json_decode($request->getContent(), true);

            $updatedCar = $this->carService->update($id, $data);
            
            return new JsonResponse($updatedCar, Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    // DELETE CAR
    public function delete($id): JsonResponse
    {
        try {
            $this->carService->delete($id);

            return new JsonResponse('Car deleted.', Response::HTTP_NO_CONTENT);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NO_CONTENT);
        }
    }
}
