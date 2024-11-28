<?php

namespace AnourValar\LaravelForm\Components;

use Illuminate\View\Component;

class Hidden extends Component
{
    /**
     * @var array
     */
    public $data;

    /**
     * @var array
     */
    public $prefix;

    /**
     * Create a new component instance.
     *
     * @param array|null $data
     * @param array|string|null $prefix
     * @return void
     */
    public function __construct(?array $data = null, array|string|null $prefix = null)
    {
        $this->data = (array) $data;
        $this->prefix = (array) $prefix;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return $this->walk($this->data, $this->prefix);
    }

    /**
     * @param array $data
     * @param array $prefix
     * @return string
     */
    protected function walk(array $data, array $prefix = []): string
    {
        $result = '';

        foreach ($data as $name => $value) {
            if (is_array($value)) {
                $result .= $this->walk($value, array_merge($prefix, [$name]));
            } elseif (is_scalar($value) && mb_strlen($value)) {
                $result .= sprintf(
                    '<input type="hidden" name="%s" value="%s"/>',
                    $this->buildName($prefix, $name),
                    $this->buildValue($value)
                );
            }
        }

        return $result;
    }

    /**
     * @param array $prefix
     * @param mixed $key
     * @return string
     */
    protected function buildName(array $prefix, $key): string
    {
        if (count($prefix) > 1) {
            return e(array_shift($prefix) . '[' . implode('][', $prefix) . '][' . $key . ']');
        }

        if (count($prefix) == 1) {
            return e($prefix[0] . '[' . $key . ']');
        }

        return e($key);
    }

    /**
     * @param string|numeric $value
     * @return string
     */
    protected function buildValue($value): string
    {
        return e($value);
    }
}
