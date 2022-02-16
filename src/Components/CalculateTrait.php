<?php

namespace AnourValar\LaravelForm\Components;

trait CalculateTrait
{
    /**
     * Calculates all props.
     *
     * @param \Illuminate\Support\ViewErrorBag $errors
     * @param array $old
     * @param string $slot
     * @return array
     */
    abstract public function calculate(\Illuminate\Support\ViewErrorBag $errors, ?array $old, string $slot = null): array;

    /**
     * @param \Illuminate\Support\ViewErrorBag $errors
     * @param array $old
     * @param bool $required
     * @return array
     */
    protected function getBackground(\Illuminate\Support\ViewErrorBag $errors, ?array $old, bool $required): array
    {
        $hasError = false;
        $hasOld = false;

        if ($errors->keys() && $this->attributes->has('name')) {
            $key = str_replace(['[', ']'], ['.', ''], $this->attributes['name']);
            $splittedKey = explode('.', $key);

            // old
            if (config('form.old')) {
                $hasOld = true;
                $prefix = '';
                foreach ($splittedKey as $item) {
                    if ($item === '') {
                        break;
                    }

                    if (array_key_exists($prefix.$item, (array) $old)) {
                        $old = $old[$prefix.$item];
                        $prefix = '';
                    } else {
                        $prefix .= $item.'.';
                    }
                }

                if ($prefix) {
                    $old = null;
                    if ($required) {
                        $hasOld = false;
                    }
                }
            }

            // error
            if (config('form.error')) {
                $hasError = $errors->has($key);

                // data[key] => data.key.sub
                if (! $hasError) {
                    foreach ($errors->keys() as $errorKey) {
                        if (strpos($errorKey, $key.'.') === 0) {
                            $hasError = true;
                            break;
                        }
                    }
                }

                // data[key][sub] => data.key
                if (! $hasError) {
                    while ((! $hasError = $errors->has(implode('.', $splittedKey))) && count($splittedKey) > 1) {
                        array_pop($splittedKey);
                    }
                }
            }
        }

        if (! $hasOld) {
            $old = null;
        } elseif (is_string($old)) {
            $old = e($old);
        }

        return compact('hasError', 'hasOld', 'old');
    }
}
