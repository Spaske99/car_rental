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

    public function add(Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (empty($data['firstName']) || empty($data['lastName']) || empty($data['email']) || empty($data['password']) || empty($data['role'])) {
                throw new BadRequestHttpException('Expecting mandatory parameters!');
            }

            $this->userService->add($data);

            return new JsonResponse('User added!', Response::HTTP_CREATED);
        } catch (BadRequestHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function getAll(): JsonResponse
    {
        try {
            $users = $this->userRepository->findAll();

            if (empty($users)) {
                throw new NotFoundHttpException('No users found');
            }

            return new JsonResponse($this->userService->getAll($users), Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function get($id): JsonResponse
    {
        try {
            $user = $this->userRepository->find($id);  

            if ($user === null) {
                throw new NotFoundHttpException('User not found');
            }        

            return new JsonResponse($this->userService->get($user), Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {      
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function update($id, Request $request): JsonResponse
    {
        try {
            $user = $this->userRepository->find($id);

            if ($user === null) {
                throw new NotFoundHttpException('User not found');
            }
            $data = json_decode($request->getContent(), true);

            $updatedUser = $this->userService->update($user, $data);  

            return new JsonResponse($updatedUser, Response::HTTP_OK);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
    }

    public function delete($id): JsonResponse
    {
        try {
            $user = $this->userRepository->find($id);

            if ($user === null) {
                throw new NotFoundHttpException('User not found');
            }

            $this->userRepository->delete($user);      

            return new JsonResponse('User deleted!', Response::HTTP_NO_CONTENT);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse($e->getMessage(), Response::HTTP_NO_CONTENT);
        }
    }
}


