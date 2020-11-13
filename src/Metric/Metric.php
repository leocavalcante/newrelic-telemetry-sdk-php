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

    /**
     * @param string $name
     * @param int|float|SummaryValue|null $value
     * @return static
     */
    public static function create(string $name, $value = null): self
    {
        return new static($name, $value);
    }

    /**
     * @param string $name
     * @param int|float|SummaryValue|null $value
     */
    public final function __construct(string $name, $value = null)
    {
        $this->name = $name;
        $this->value = $value;
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
            'timestamp' => current_timestamp(),
        ];

        if (!empty($this->attrs)) {
            $metric['attributes'] = $this->attrs;
        }

        return $metric;
    }

    abstract protected function getType(): string;
}