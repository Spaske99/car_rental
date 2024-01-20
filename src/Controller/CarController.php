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
}
