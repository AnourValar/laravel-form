<?php

namespace AnourValar\LaravelForm\Components;

use Illuminate\View\Component;

class Select extends Component
{
    use CalculateTrait;

    /**
     * @var mixed
     */
    public $options;

    /**
     * @var mixed
     */
    public $prepends;

    /**
     * @var mixed
     */
    public $conditions;

    /**
     * @var mixed
     */
    public $mapping;

    /**
     * @var mixed
     */
    public $selected;

    /**
     * @var mixed
     */
    public $disabled;

    /**
     * @var mixed
     */
    public $readonly;

    /**
     * @var mixed
     */
    public $merge;

    /**
     * Create a new component instance.
     *
     * @param mixed $options
     * @param mixed $prepends
     * @param mixed $conditions
     * @param mixed $mapping
     * @param mixed $selected
     * @param mixed $disabled
     * @param mixed $readonly
     * @param mixed $merge
     * @return void
     */
    public function __construct(
        $options = null,
        $prepends = null,
        $conditions = null,
        $mapping = null,
        $selected = null,
        $disabled = null,
        $readonly = null,
        $merge = null
    ) {
        $this->options = $options;
        $this->prepends = $prepends;
        $this->conditions = $conditions;
        $this->mapping = $mapping;
        $this->selected = $selected;
        $this->disabled = $disabled;
        $this->readonly = $readonly;
        $this->merge = (array) $merge;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('form::select');
    }

    /**
     * Calculates all props.
     *
     * @param \Illuminate\Support\ViewErrorBag $errors
     * @param array $old
     * @param string $slot
     * @return array
     */
    public function calculate(\Illuminate\Support\ViewErrorBag $errors, ?array $old, string $slot = null): array
    {
        $background = $this->getBackground($errors, $old);
        $hasError = $background['hasError'];
        $hasOld = $background['hasOld'];
        $old = $background['old'];


        $selected = $hasOld ? $old : $this->selected;


        if ($this->disabled) {
            $this->attributes->offsetSet('disabled', true);
        }

        if ($this->readonly) {
            $this->attributes->offsetSet('readonly', true);
        }

        return compact('selected', 'hasError');
    }
}
