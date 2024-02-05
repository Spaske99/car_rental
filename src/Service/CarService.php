<?php

namespace App\Service;

use App\Entity\Car;
use App\Entity\Rent;
use App\Repository\CarRepository;
use Doctrine\ORM\EntityManagerInterface;

class CarService 
{
    private $manager;
    private $carRepository;

    public function __construct(EntityManagerInterface $manager, CarRepository $carRepository)
    {
        $this->manager = $manager;
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
        empty($data['brand']) ? true : $car->setBrand($data['brand']);
        empty($data['model']) ? true : $car->setModel($data['model']);
        empty($data['dailyPrice']) ? true : $car->setDailyPrice($data['dailyPrice']);
        empty($data['description']) ? true : $car->setDescription($data['description']);

        $updatedCar = $this->carRepository->update($car);
        
        return $updatedCar->jsonSerialize();
    }

    public function delete($car) 
    {
        $rents = $this->manager->getRepository(Rent::class)->findByCar($car);

        foreach ($rents as $rent) {
            $this->manager->remove($rent);
        }

        $this->carRepository->delete($car);
    }
}