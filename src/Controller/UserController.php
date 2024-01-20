<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserController extends AbstractController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $firstName = $data['firstName'];
        $lastName = $data['lastName'];
        $email = $data['email'];
        $password = $data['password'];
        $role = $data['role'];

        if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($role)) {
            throw new BadRequestHttpException('Expecting mandatory parameters!');
        }

        $this->userRepository->add($firstName, $lastName, $email, $password, $role);

        return new JsonResponse('User added!', Response::HTTP_CREATED);
    }

    public function getAll(): JsonResponse
    {
        $users = $this->userRepository->findAll();
        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
                'email' => $user->getEmail(),
                'password' => $user->getPassword(),
                'created' => $user->getCreated(),
                'updated' => $user->getUpdated(),
                'role' => $user->getRole()->getRoleType()
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    public function get($id): JsonResponse
    {
        $user = $this->userRepository->find($id);

        $data[] = [
            'id' => $user->getId(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'created' => $user->getCreated(),
            'updated' => $user->getUpdated(),
            'role' => $user->getRole()->getRoleType()
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    public function update($id, Request $request): JsonResponse
    {
        $user = $this->userRepository->find($id);
        $data = json_decode($request->getContent(), true);

        empty($data['firstName']) ? true : $user->setFirstName($data['firstName']);
        empty($data['lastName']) ? true : $user->setLastName($data['lastName']);
        empty($data['email']) ? true : $user->setEmail($data['email']);
        empty($data['password']) ? true : $user->setPassword($data['password']);

        $updatedUser = $this->userRepository->update($user);

        return new JsonResponse($updatedUser->jsonSerialize(), Response::HTTP_OK);
    }

    public function delete($id): jsonResponse
    {
        $user = $this->userRepository->find($id);

        $deleteUser = $this->userRepository->delete($user);
        
        return new JsonResponse('User deleted!', Response::HTTP_NO_CONTENT);
    }
}


