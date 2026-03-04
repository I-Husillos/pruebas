<?php

namespace Termosalud\Web\User\Domain;

class User
{
    public $id;

    public $name;

    public $email;

    public $roles;

    public function __construct(?int $id, string $name, string $email, array $roles = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->roles = $roles;
    }
}
