<?php

namespace App\Controller;

use App\Repository\CarRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CarController extends AbstractController
{
    private $carRepository;

    public function __construct(CarRepository $carRepository) 
    {
        $this->carRepository = $carRepository;
    }

    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $brand = $data['brand'];
        $model = $data['model'];
        $dailyPrice = $data['dailyPrice'];
        $description = $data['description'];
        $image = $data['image'];

        if (empty($brand) || empty($model) || empty($dailyPrice) || empty($description)) {
            throw new BadRequestHttpException('Expecting mandatory parameters!');
        }
        
        $this->carRepository->add($brand, $model, $dailyPrice, $description, $image);

        return new JsonResponse('Car added!', Response::HTTP_CREATED);
    }

    public function getAll(): JsonResponse
    {
        $cars = $this->carRepository->findAll();
        $data = [];
        
        foreach ($cars as $car) {
            $data[] = [
                "id" => $car->getId(),
                "brand" => $car->getBrand(),
                "model" => $car->getModel(),
                "dailyPrice" => $car->getDailyPrice(),
                "description" => $car->getDescription(),
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    public function get($id): JsonResponse
    {
        $car = $this->carRepository->find($id);

        $data[] = [
            "id" => $car->getId(),
            "brand" => $car->getBrand(),
            "model" => $car->getModel(),
            "dailyPrice" => $car->getDailyPrice(),
            "description" => $car->getDescription(),
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        
        $car = $this->carRepository->find($id);
        
        empty($data['brand'])? true : $car->setBrand($data['brand']);
        empty($data['model'])? true : $car->setModel($data['model']);
        empty($data['dailyPrice'])? true : $car->setDailyPrice($data['dailyPrice']);
        empty($data['description'])? true : $car->setDescription($data['description']);
        
        $updatedCar = $this->carRepository->update($car);
        
        return new JsonResponse($updatedCar->jsonSerialize(), Response::HTTP_OK);
    }

    public function delete($id): JsonResponse
    {
        $car = $this->carRepository->find($id);

        $this->carRepository->delete($car);

        return new JsonResponse('Car deleted!', Response::HTTP_NO_CONTENT);
    }
}
