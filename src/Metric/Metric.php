<?php declare(strict_types=1);

namespace NewRelic\Metric;

use function NewRelic\Util\current_timestamp;

abstract class Metric implements DataTypeInterface
{
    private string $name;
    /** @var int|float|SummaryValue|null */
    private $value;
    /** @var array<string|int|float> */
    private array $attrs = [];
    private int $timestamp;

    /**
     * @param string $name
     * @param int|float|SummaryValue|null $value
     * @param int|null $timestamp
     * @return static
     */
    public static function create(
        string $name,
        $value = null,
        ?int $timestamp = null
    ): self {
        return new static($name, $value, $timestamp);
    }

    /**
     * @param string $name
     * @param int|float|SummaryValue|null $value
     * @param int|null $timestamp
     */
    final public function __construct(
        string $name,
        $value = null,
        ?int $timestamp = null
    ) {
        $this->name = $name;
        $this->value = $value;
        $this->timestamp = $timestamp ?? current_timestamp();
    }

    /**
     * @param int|float|SummaryValue $value
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

        return $metric;
    }

    abstract protected function getType(): string;
}
