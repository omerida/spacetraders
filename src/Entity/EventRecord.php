<?php

namespace Phparch\SpaceTraders\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'event_record')]
class EventRecord
{
    /**
     * @readonly
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id;

    #[ORM\Column(name: 'created_at', type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable $createdAt;

    /**
     * Stores data as a JSON object/array in the database,
     * but accepts/returns a JSON string via the getters and setters.
     *
     * @var array<string, mixed>|null $data
     */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $data = null;

    public function __construct(
        #[ORM\Column(type: Types::STRING, length: 512)]
        private string $name,
        #[ORM\Column(type: Types::STRING, length: 512)]
        private string $source,
        #[ORM\Column(type: Types::TEXT, nullable: true)]
        private ?string $description = null
    ) {
        $this->createdAt = new DateTimeImmutable();
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    // --- Getters & Setters ---

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Gets the data property converted back into a JSON string.
     * @throws \JsonException
     * @return array<string,mixed>
     */
    public function getData(): array
    {
        if ($this->data === null) {
            return [];
        }

        return $this->data;
    }

    /**
     * Accepts a raw JSON string and decodes it for Doctrine's JSON type storage.
     *
     * @throws \JsonException If the provided string is invalid JSON
     */
    public function setData(?string $jsonString): self
    {
        if ($jsonString === null || trim($jsonString) === '') {
            $this->data = null;
            return $this;
        }

        /** @var array<string, mixed> $json */
        $json = json_decode($jsonString, true, 512, JSON_THROW_ON_ERROR);
        $this->data = $json;
        return $this;
    }

    /**
     * Convenience method if you prefer to set the decoded array directly.
     * @return array<string, mixed>
     */
    public function getDataAsArray(): ?array
    {
        return $this->data;
    }

    /**
     * @param array<string, mixed>|null $data
     * @return $this
     */
    public function setDataFromArray(?array $data): self
    {
        $this->data = $data;
        return $this;
    }
}
