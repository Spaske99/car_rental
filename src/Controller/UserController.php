<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends AbstractController
{
    private $userRepository;
    private $userService;

    public function __construct(UserRepository $userRepository, UserService $userService)
    {
        $this->userRepository = $userRepository;
        $this->userService = $userService;
    }

    // CREATE USER
    public function create(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
  
            $this->userService->create($data);

            return new JsonResponse('User created.', Response::HTTP_CREATED);
        } catch (BadRequestHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    // GET_ALL USERS
    public function getAll(): JsonResponse
    {
        try {
            return new JsonResponse($this->userService->getAll(), Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    // GET USER
    public function get($id): JsonResponse
    {
        try {
            return new JsonResponse($this->userService->get($id), Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {      
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    // UPDATE USER
    public function update($id, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            $updatedUser = $this->userService->update($id, $data);  

            return new JsonResponse($updatedUser, Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    // DELETE USER
    public function delete($id): JsonResponse
    {
        try {
            $this->userService->delete($id);      

            return new JsonResponse('User deleted.', Response::HTTP_NO_CONTENT);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NO_CONTENT);
        }
    }
}


