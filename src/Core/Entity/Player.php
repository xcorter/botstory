<?php

namespace App\Core\Entity;

use App\Core\Player\PlayerContext;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class Player
{
    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer $id
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=255)
     * @var string $name
     */
    private $providerUserId;
    /**
     * @ORM\Column(type="string", length=255)
     * @var string $name
     */
    private $providerName;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|null $firstName
     */
    private $firstName;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|null $lastName
     */
    private $lastName;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string|null $username
     */
    private $username;
    /**
     * @ORM\Column(type="string", length=1000)
     * @var string $context
     */
    private $context;
    /**
     * @var PlayerContext
     */
    private $contextObject;
    /**
     * @ORM\Column(type="datetime_immutable")
     * @var DateTimeImmutable $createdDate
     */
    private $createdDate;
    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @var DateTimeImmutable $updatedDate
     */
    private $updatedDate;

    /**
     * Player constructor.
     * @param string $providerUserId
     * @param string $providerName
     */
    public function __construct(string $providerUserId, string $providerName)
    {
        $this->providerUserId = $providerUserId;
        $this->providerName = $providerName;
        $this->contextObject = new PlayerContext();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getProviderName(): string
    {
        return $this->providerName;
    }

    /**
     * @return string
     */
    public function getProviderUserId(): string
    {
        return $this->providerUserId;
    }

    /**
     * @param string|null $firstName
     * @return Player
     */
    public function setFirstName(?string $firstName): Player
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @param string|null $lastName
     * @return Player
     */
    public function setLastName(?string $lastName): Player
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @param string|null $username
     * @return Player
     */
    public function setUsername(?string $username): Player
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updatedTimestamps(): void
    {
        $this->updatedDate = new DateTimeImmutable('now');
        if ($this->createdDate === null) {
            $this->createdDate = new DateTimeImmutable('now');
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function serializeContext(): void
    {
        $this->context = $this->contextObject->serialize();
    }

    /**
     * @ORM\PostLoad()
     */
    public function deserializeContext(): void
    {
        if (!$this->context) {
            $this->contextObject = new PlayerContext();
            $this->serializeContext();
            return;
        }
        $this->contextObject = PlayerContext::deserialize($this->context);
    }

    public function isStart(): bool
    {
        return $this->contextObject->isStart();
    }

    public function showMenuStep(): void
    {
        $this->contextObject->showMenuMode();
        $this->serializeContext();
    }

    public function selectGameStep(): void
    {
        $this->contextObject->selectGameMode();
        $this->serializeContext();
    }

    public function getContext(): PlayerContext
    {
        return $this->contextObject;
    }

    public function runGame(int $id): void
    {
        $this->contextObject->runGame($id);
        $this->serializeContext();
    }

    public function backToGame(): void
    {
        $this->contextObject->backToGame();
        $this->serializeContext();
    }

    public function setCurrentQuestion(int $questionId): void
    {
        $this->contextObject->setCurrentQuestion($questionId);
        $this->serializeContext();
    }

    public function selectSettingsMenu(): void
    {
        $this->contextObject->selectSettingsMenu();
        $this->serializeContext();
    }

    public function resetQuestionId(): void
    {
        $this->contextObject->resetQuestionId();
        $this->serializeContext();
    }

    public function resetContext(): void
    {
        $this->contextObject = new PlayerContext();
        $this->serializeContext();
    }

    public function gameOver(): void
    {
        $this->getContext()->gameOver();
        $this->serializeContext();
    }
}
