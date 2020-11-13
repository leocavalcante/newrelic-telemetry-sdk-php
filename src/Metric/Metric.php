<?php declare(strict_types=1);

namespace NewRelic\Metric;

use function NewRelic\Util\current_timestamp;

/**
 * @template T
 */
abstract class Metric implements DataTypeInterface
{
    private string $name;
    /**
     * @var mixed
     * @psalm-var T
     */
    protected $value;
    /** @var array<string|int|float> */
    private array $attrs = [];
    private int $timestamp;

    /**
     * @template ST
     * @param string $name
     * @param mixed $value
     * @psalm-param ST $value
     * @param int|null $timestamp
     * @return static
     */
    public static function create(
        string $name,
        $value,
        ?int $timestamp = null
    ): self {
        return new static($name, $value, $timestamp);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @psalm-param T $value
     * @param int|null $timestamp
     */
    final public function __construct(
        string $name,
        $value,
        ?int $timestamp = null
    ) {
        $this->name = $name;
        $this->value = $value;
        $this->timestamp = $timestamp ?? current_timestamp();
    }

    /**
     * @param mixed $value
     * @psalm-param T $value
     * @return $this
     */
    public function setValue($value): self
    {
        $this->value = $value;
        return $this;
    }

    public function setTimestamp(int $timestamp): self
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * @param array<string|int|float> $attributes
     * @return $this
     */
    public function setAttributes(array $attributes): self
    {
        $this->attrs = $attributes;
        return $this;
    }

    /**
     * @param string $name
     * @param string|int|float $value
     * @return $this
     */
    public function addAttribute(string $name, $value): self
    {
        $this->attrs[$name] = $value;
        return $this;
    }

    public function jsonSerialize(): array
    {
        $metric = [
            'name' => $this->name,
            'type' => $this->getType(),
            'value' => $this->value,
            'timestamp' => $this->timestamp,
        ];

        if (!empty($this->attrs)) {
            $metric['attributes'] = $this->attrs;
        }

        if ($this instanceof IntervalInterface) {
            $metric['interval.ms'] = $this->getIntervalMs();
        }

        return $metric;
    }

    abstract protected function getType(): string;
}
