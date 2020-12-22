<?php

namespace App\Core\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class UpdateLog
{
    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private int $updateId;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $provider;

    public function __construct(int $updateId, string $provider)
    {
        $this->updateId = $updateId;
        $this->provider = $provider;
    }

}
