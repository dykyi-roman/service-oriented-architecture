<?php

namespace App\Application\Auth\DTO;

final class UserDTO
{
    public string $id;
    public string $email;
    public string $phone;
    public string $fullName;
    public bool $isActive;

    public function __construct(array $user)
    {
        $this->id = $user['id'];
        $this->email = $user['email'];
        $this->phone = $user['phone'];
        $this->fullName = $user['fullName'];
        $this->isActive = $user['isActive'];
    }
}
