<?php

namespace App\Service;

use App\Entity\Car;
use App\Repository\CarRepository;

class CarService 
{
    private $carRepository;

    public function __construct(CarRepository $carRepository)
    {
        $this->carRepository = $carRepository;
    }

    public function add($data)
    {
        $brand = $data['brand'];
        $model = $data['model'];
        $dailyPrice = $data['dailyPrice'];
        $description = $data['description'];
        $image = $data['image'];

        $car = new Car();

        $car
            ->setBrand($brand)
            ->setModel($model)
            ->setDailyPrice($dailyPrice)
            ->setDescription($description)
            ->setImage($image);

        $this->carRepository->add($car);
    }

    public function getAll($cars)
    {
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

        return $data;
    }

    public function get($car)
    {
        $data = [
            "id" => $car->getId(),
            "brand" => $car->getBrand(),
            "model" => $car->getModel(),
            "dailyPrice" => $car->getDailyPrice(),
            "description" => $car->getDescription(),
        ];

        return $data;
    }

    public function update($car, $data)
    {
        empty($data['brand'])? true : $car->setBrand($data['brand']);
        empty($data['model'])? true : $car->setModel($data['model']);
        empty($data['dailyPrice'])? true : $car->setDailyPrice($data['dailyPrice']);
        empty($data['description'])? true : $car->setDescription($data['description']);

        $updatedCar = $this->carRepository->update($car);
        
        return $updatedCar->jsonSerialize();
    }
}