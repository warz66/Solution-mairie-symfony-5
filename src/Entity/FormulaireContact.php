<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class FormulaireContact
{

    /**
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $email;

    /**
     * @Assert\NotBlank
     * @Assert\Length(max = 255)
     */
    private $objet;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min = 10, max = 2500)
     */
    private $message;

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(string $objet): self
    {
        $this->objet = $objet;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }
}
