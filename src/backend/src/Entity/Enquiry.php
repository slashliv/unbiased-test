<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;

/**
 * @ORM\Entity()
 * @ORM\Table(name="u_enquiry")
 */
class Enquiry
{
    const AIRPORT_HEATHROW = 'heathrow';
    const AIRPORT_GATWICK = 'gatwick';
    const TERMINALS = ['1', '2', '3', '4', 'not sure'];

    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $phone;

    /**
     * @var \DateTimeImmutable
     *
     * @ORM\Column(type="date_immutable")
     */
    private $dateOfArrival;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $airFlightNumber;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $airport;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $terminal;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraints('fullName', [
            new Assert\NotBlank(),
            new Assert\Regex([
                'pattern' => '/^[a-z,\'-]+(\s)[a-z,\'-]+$/i',
                'message' => 'Full name must be valid',
            ]),
        ]);
        $metadata->addPropertyConstraints('phone', [
            new Assert\NotBlank(),
            new Assert\Regex([
                'pattern' => '/^(\+44\s?7\d{3}|\(?07\d{3}\)?)\s?\d{3}\s?\d{3}$/',
                'message' => 'Phone must be valid',
            ]),
        ]);
        $metadata->addPropertyConstraints('dateOfArrival', [
            new Assert\NotBlank(),
        ]);
        $metadata->addPropertyConstraints('airFlightNumber', [
            new Assert\NotBlank(),
            new Assert\Regex([
                'pattern' => '/^([a-z][a-z]|[a-z][0-9]|[0-9][a-z])[a-z]?[0-9]{1,4}[a-z]?$/i',
                'message' => 'Air flight number must be valid',
            ]),
        ]);
        $metadata->addPropertyConstraints('airport', [
            new Assert\NotBlank(),
            new Assert\Choice([
                'choices' => [
                    self::AIRPORT_GATWICK,
                    self::AIRPORT_HEATHROW,
                ],
            ]),
        ]);
        $metadata->addPropertyConstraints('terminal', [
            new Assert\Choice([
                'choices' => self::TERMINALS,
            ]),
        ]);
        $metadata->addConstraint(new Assert\Callback([
            'callback' => 'validate',
        ]));
    }

    public function validate(ExecutionContextInterface $context)
    {
        if (self::AIRPORT_HEATHROW === $this->getAirport() && null === $this->getTerminal()) {
            $context->buildViolation('Terminal should not be blank.')->atPath('terminal');
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): Enquiry
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): Enquiry
    {
        $this->phone = $phone;

        return $this;
    }

    public function getDateOfArrival(): ?\DateTimeImmutable
    {
        return $this->dateOfArrival;
    }

    public function setDateOfArrival(?\DateTimeImmutable $dateOfArrival): Enquiry
    {
        $this->dateOfArrival = $dateOfArrival;

        return $this;
    }

    public function getAirFlightNumber(): ?string
    {
        return $this->airFlightNumber;
    }

    public function setAirFlightNumber(?string $airFlightNumber): Enquiry
    {
        $this->airFlightNumber = $airFlightNumber;

        return $this;
    }

    public function getAirport(): ?string
    {
        return $this->airport;
    }

    public function setAirport(?string $airport): Enquiry
    {
        $this->airport = $airport;

        return $this;
    }

    public function getTerminal(): ?string
    {
        return $this->terminal;
    }

    public function setTerminal(?string $terminal): Enquiry
    {
        $this->terminal = $terminal;

        return $this;
    }
}