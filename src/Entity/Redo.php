<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RedoRepository")
 */
class Redo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $batch;

    /**
     * @ORM\Column(type="integer")
     */
    private $mappingId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBatch(): ?string
    {
        return $this->batch;
    }

    public function setBatch(string $batch): self
    {
        $this->batch = $batch;

        return $this;
    }

    public function getMappingId(): ?int
    {
        return $this->mappingId;
    }

    public function setMappingId(int $mappingId): self
    {
        $this->mappingId = $mappingId;

        return $this;
    }
}
