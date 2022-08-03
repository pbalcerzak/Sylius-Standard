<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity
 * @ORM\Table(name="app_supplier")
 * @ApiResource(
 *     routePrefix="admin",
 *     denormalizationContext={"groups"={"admin:supplier:write"}},
 *     itemOperations={
 *          "get",
 *          "trust"={
 *              "path"="/suppliers/{id}/trust",
 *              "method"="PATCH",
 *              "controller"=\App\Applicator\TrustSupplierApplicator::class,
 *              "input"=false
 *          }
 *     }
 * )
 */
class Supplier implements SupplierInterface
{
    public const STATE_NEW = 'new';
    public const STATE_TRUSTED = 'trusted';

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Groups("admin:supplier:write")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Groups("admin:supplier:write")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $state = self::STATE_NEW;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Product\Product", mappedBy="supplier")
     */
    private $products;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function setState(string $state): void
    {
        $this->state = $state;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function countProducts(): int
    {
        return $this->products->count();
    }

}
