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
    public $appends;

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
     * @var mixed
     */
    public $error;

    /**
     * Create a new component instance.
     *
     * @param mixed $options
     * @param mixed $prepends
     * @param mixed $appends
     * @param mixed $conditions
     * @param mixed $mapping
     * @param mixed $selected
     * @param mixed $disabled
     * @param mixed $readonly
     * @param mixed $merge
     * @param mixed $error
     * @return void
     */
    public function __construct(
        $options = null,
        $prepends = null,
        $appends = null,
        $conditions = null,
        $mapping = null,
        $selected = null,
        $disabled = null,
        $readonly = null,
        $merge = null,
        $error = null
    ) {
        $this->options = $options;
        $this->prepends = $prepends;
        $this->appends = $appends;
        $this->conditions = $conditions;
        $this->mapping = $mapping;
        $this->selected = $selected;
        $this->disabled = $disabled;
        $this->readonly = $readonly;
        $this->merge = (array) $merge;
        $this->error = $error;
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
     * @param string|null $slot
     * @return array
     */
    public function calculate(\Illuminate\Support\ViewErrorBag $errors, ?array $old, ?string $slot = null): array
    {
        $background = $this->getBackground($errors, $old, !$this->attributes['multiple']);
        $hasError = is_null($this->error) ? $background['hasError'] : ((bool) $this->error);
        $hasOld = $background['hasOld'];
        $old = $background['old'];


        if (is_array($old)) {
            foreach ($old as $item) {
                if (is_array($item)) {
                    $hasOld = false;
                }
            }
        }
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
