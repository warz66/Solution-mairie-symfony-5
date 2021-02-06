<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class ResetPassword
{

    /**
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit faire au moins 8 caractères !")
     */
    private $newPassword;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }
}
