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

    public function add(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (empty($data['brand']) || empty($data['model']) || empty($data['dailyPrice']) || empty($data['description'])) {
                throw new BadRequestHttpException('Expecting mandatory parameters!');
            }
            
            $this->carService->add($data);

            return new JsonResponse('Car added!', Response::HTTP_CREATED);  
        } catch (BadRequestHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);  
        }
    }

    public function getAll(): JsonResponse
    {
        try {
            $cars = $this->carRepository->findAll();

            if (empty($cars)) {
                throw new NotFoundHttpException('No cars found');
            }

            return new JsonResponse($this->carService->getAll($cars), Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function get($id): JsonResponse
    {
        try {
            $car = $this->carRepository->find($id);

            if ($car === null) {
                throw new NotFoundHttpException('Car not found');
            } 

            return new JsonResponse($this->carService->get($car), Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $car = $this->carRepository->find($id);

            if ($car === null) {
                throw new NotFoundHttpException('Car not found');
            }
            
            $data = json_decode($request->getContent(), true);

            $updatedCar = $this->carService->update($car, $data);
            
            return new JsonResponse($updatedCar, Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function delete($id): JsonResponse
    {
        try {
            $car = $this->carRepository->find($id);

            if ($car === null) {
                throw new NotFoundHttpException('Car not found');
            }

            $this->carRepository->delete($car);

            return new JsonResponse('Car deleted!', Response::HTTP_NO_CONTENT);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NO_CONTENT);
        }
    }
}
