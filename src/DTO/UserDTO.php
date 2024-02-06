<?php

namespace App\DTO;

use App\Entity\Role;

class UserDTO
{
    /**
     * @param int id
     * @param string firstName
     * @param string lastName
     * @param string email
     * @param string password
     * @param Role role
     */

    public function __construct(
        public readonly int     $id,
        public readonly string  $firstName,
        public readonly string  $lastName,
        public readonly string  $email,
        public readonly string  $password,
        public readonly Role  $role
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            "id"        => $this->id,
            'firstName' => $this->firstName,
            'lastName'  => $this->lastName,
            'email'     => $this->email
        ];
    }
}
