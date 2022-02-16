<?php

namespace AnourValar\LaravelForm\Components;

use Illuminate\View\Component;

class Input extends Component
{
    use CalculateTrait;

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
    public $checked;

    /**
     * @var mixed
     */
    public $checkedValue;

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
     * @param mixed $disabled
     * @param mixed $readonly
     * @param mixed $checked
     * @param mixed $checkedValue
     * @param mixed $merge
     * @param mixed $error
     * @return void
     */
    public function __construct($disabled = null, $readonly = null, $checked = null, $checkedValue = null, $merge = null, $error = null)
    {
        $this->disabled = $disabled;
        $this->readonly = $readonly;
        $this->checked = $checked;
        $this->checkedValue = $checkedValue;
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
        return view('form::input');
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
        $type = $this->attributes->has('type') ? mb_strtolower($this->attributes['type']) : null;

        $background = $this->getBackground($errors, $old, !in_array($type, ['radio', 'checkbox']));
        $hasError = is_null($this->error) ? $background['hasError'] : ((bool) $this->error);
        $hasOld = $background['hasOld'];
        $old = $background['old'];

        $checked = $this->checked;

        $hasValue = $this->attributes->has('value');
        $value = $hasValue ? $this->attributes['value'] : null;


        if ($type == 'radio') {
            if ($hasOld) {
                $checked = ($value == $old);
            } elseif (! is_null($this->checkedValue)) {
                $checked = ($value == $this->checkedValue);
            }
        } elseif ($type == 'checkbox' && $errors->keys()) {
            $checked = $old;
        } elseif ($hasOld && $hasValue && !is_array($old)) {
            $value = $old;
        }


        if ($hasValue) {
            $this->attributes->offsetSet('value', $value);
        }

        if ($this->disabled) {
            $this->attributes->offsetSet('disabled', true);
        }

        if ($this->readonly) {
            $this->attributes->offsetSet('readonly', true);
        }

        if ($checked) {
            $this->attributes->offsetSet('checked', true);
        }

        return compact('hasError');
    }
}
