<?php

namespace App\Service;

use App\DTO\CarDTO;
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

    // CREATE CAR
    public function create($data)
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

    // GET_ALL CARS
    public function getAll($cars)
    {
        $data = [];
            
        foreach ($cars as $car) {
            $data[] = $this->getCarDTO($car)->jsonSerialize();
        }

        return $data;
    }

    // GET CAR
    public function get($car)
    {
        return $this->getCarDTO($car)->jsonSerialize();
    }

    // UPDATE CAR
    public function update($car, $data)
    {
        empty($data['brand']) ? true : $car->setBrand($data['brand']);
        empty($data['model']) ? true : $car->setModel($data['model']);
        empty($data['dailyPrice']) ? true : $car->setDailyPrice($data['dailyPrice']);
        empty($data['description']) ? true : $car->setDescription($data['description']);

        $this->carRepository->update($car);
        
        return $this->getCarDTO($car)->jsonSerialize();
    }

    // DELETE CAR AND ASSOCIATED RENTS
    public function delete($car) 
    {
        $rents = $this->manager->getRepository(Rent::class)->findByCar($car);

        foreach ($rents as $rent) {
            $this->manager->remove($rent);
        }

        $this->carRepository->delete($car);
    }

    // CAR DTO
    public function getCarDTO($car): CarDTO
    {
        return new CarDTO(
            $car->getId(),
            $car->getBrand(),
            $car->getModel(),
            $car->getDailyPrice(),
            $car->getDescription(),
            // $car->getImage()     RESI PROBLEM SA TIPOM PODATAKA!
        );
    }
}