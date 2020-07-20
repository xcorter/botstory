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
     * @var integer $id
     */
    private $id;

    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @var integer $updateId
     */
    private $updateId;
    /**
     * @ORM\Column(type="string", length=255)
     * @var string $name
     */
    private $provider;

    /**
     * Update constructor.
     * @param int $updateId
     * @param string $provider
     */
    public function __construct(int $updateId, string $provider)
    {
        $this->updateId = $updateId;
        $this->provider = $provider;
    }

}
