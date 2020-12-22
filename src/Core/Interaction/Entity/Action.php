<?php

namespace App\Core\Interaction\Entity;

use App\Core\Node\Entity\Node;
use Doctrine\ORM\Mapping as ORM;

class Action
{
    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;
    /**
     * @ORM\ManyToOne(targetEntity="App\Core\Node\Entity\Node")
     * @ORM\JoinColumn(name="node_id", referencedColumnName="id")
     */
    private Node $node;
    /**
     * @ORM\Column(type="json")
     */
    private string $data;
}