<?php
declare(strict_types=1);

namespace App\ValueObject;

use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class AbstractId
{
    /**
     * @var UuidInterface
     */
    protected $id;

    /**
     * @param UuidInterface $id
     */
    private function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }

    /**
     * @param string $id
     * @return AbstractId
     * @throws \Exception
     */
    public static function fromString(string $id)
    {
        try {
            return new static(Uuid::fromString($id));
        } catch (InvalidUuidStringException $exception) {
            throw new \Exception("ID is not valid");
        }
    }

    /**
     * @return AbstractId
     * @throws \Exception
     */
    public static function next()
    {
        return new static(Uuid::uuid4());
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id->toString();
    }

    /**
     * @param AbstractId $id
     * @return bool
     */
    public function equalTo(AbstractId $id): bool
    {
        return $this->getId() === $id->getId() &&
               get_class($this) === get_class($id);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getId();
    }
}
