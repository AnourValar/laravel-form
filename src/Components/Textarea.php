<?php

namespace AnourValar\LaravelForm\Components;

use Illuminate\View\Component;

class Textarea extends Component
{
    use CalculateTrait;

    /**
     * @var mixed
     */
    public $value;

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
     * @param mixed $value
     * @param mixed $disabled
     * @param mixed $readonly
     * @param mixed $merge
     * @param mixed $error
     * @return void
     */
    public function __construct($value = null, $disabled = null, $readonly = null, $merge = null, $error = null)
    {
        $this->value = $value;
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
        return view('form::textarea');
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
        $background = $this->getBackground($errors, $old, true);
        $hasError = is_null($this->error) ? $background['hasError'] : ((bool) $this->error);
        $hasOld = $background['hasOld'];
        $old = $background['old'];


        $value = ($hasOld && !is_array($old)) ? $old : (e($this->value) . $slot);


        if ($this->disabled) {
            $this->attributes->offsetSet('disabled', true);
        }

        if ($this->readonly) {
            $this->attributes->offsetSet('readonly', true);
        }

        return compact('value', 'hasError');
    }
}
