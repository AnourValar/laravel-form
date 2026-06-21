<?php

namespace AnourValar\LaravelForm\Tests;

use AnourValar\LaravelForm\Components\Select;

class SelectTest extends AbstractSuite
{
    /**
     * Without validation errors the "selected" prop is used as-is.
     *
     * @return void
     */
    public function test_selected_prop_used_without_errors(): void
    {
        $select = (new Select(selected: '2'))->withAttributes(['name' => 'foo']);

        $result = $select->calculate($this->errorBag(), ['foo' => '3']);

        $this->assertSame('2', $result['selected']);
        $this->assertFalse($result['hasError']);
    }

    /**
     * On validation failure the old value becomes the selected one.
     *
     * @return void
     */
    public function test_old_value_replaces_selected_on_failure(): void
    {
        $select = (new Select(selected: '2'))->withAttributes(['name' => 'foo']);

        $result = $select->calculate($this->errorBag(['foo' => ['Required.']]), ['foo' => '3']);

        $this->assertSame('3', $result['selected']);
    }

    /**
     * The selected old value is HTML-escaped.
     *
     * @return void
     */
    public function test_old_selected_value_is_escaped(): void
    {
        $select = (new Select())->withAttributes(['name' => 'foo']);

        $result = $select->calculate($this->errorBag(['foo' => ['Required.']]), ['foo' => '<x>']);

        $this->assertSame('&lt;x&gt;', $result['selected']);
    }

    /**
     * A multiple select keeps an array of old values as the selection.
     *
     * @return void
     */
    public function test_multiple_keeps_array_of_old_values(): void
    {
        $select = (new Select(selected: ['9']))->withAttributes(['name' => 'foo', 'multiple' => true]);

        $result = $select->calculate($this->errorBag(['foo' => ['Required.']]), ['foo' => ['1', '2']]);

        $this->assertSame(['1', '2'], $result['selected']);
    }

    /**
     * A multidimensional old value is ignored and the "selected" prop is used instead.
     *
     * @return void
     */
    public function test_multidimensional_old_value_is_ignored(): void
    {
        $select = (new Select(selected: ['9']))->withAttributes(['name' => 'foo', 'multiple' => true]);

        $result = $select->calculate($this->errorBag(['foo' => ['Required.']]), ['foo' => [['nested']]]);

        $this->assertSame(['9'], $result['selected']);
    }

    /**
     * Disabled / readonly props are turned into the corresponding HTML attributes.
     *
     * @return void
     */
    public function test_disabled_and_readonly_attributes(): void
    {
        $select = (new Select(disabled: true, readonly: true))->withAttributes(['name' => 'foo']);

        $select->calculate($this->errorBag(), null);

        $this->assertTrue($select->attributes->has('disabled'));
        $this->assertTrue($select->attributes->has('readonly'));
    }

    /**
     * The "error" prop forces the error state.
     *
     * @return void
     */
    public function test_error_prop_overrides_detection(): void
    {
        config(['form.error' => 'is-invalid']);

        $forcedOn = (new Select(error: true))->withAttributes(['name' => 'foo']);
        $forcedOff = (new Select(error: false))->withAttributes(['name' => 'foo']);

        $this->assertTrue($forcedOn->calculate($this->errorBag(), null)['hasError']);
        $this->assertFalse($forcedOff->calculate($this->errorBag(['foo' => ['Required.']]), null)['hasError']);
    }

    /**
     * The error state is detected from the validation bag when "form.error" is configured.
     *
     * @return void
     */
    public function test_error_detected_from_bag(): void
    {
        config(['form.error' => 'is-invalid']);

        $select = (new Select())->withAttributes(['name' => 'foo']);

        $this->assertTrue($select->calculate($this->errorBag(['foo' => ['Required.']]), null)['hasError']);
    }
}
