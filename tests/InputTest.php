<?php

namespace AnourValar\LaravelForm\Tests;

use AnourValar\LaravelForm\Components\Input;

class InputTest extends AbstractSuite
{
    /**
     * Without validation errors the value passed to the component is kept untouched.
     *
     * @return void
     */
    public function test_value_is_kept_without_errors(): void
    {
        $input = (new Input())->withAttributes(['type' => 'text', 'name' => 'foo', 'value' => 'original']);

        $result = $input->calculate($this->errorBag(), ['foo' => 'typed']);

        $this->assertSame('original', $input->attributes['value']);
        $this->assertFalse($result['hasError']);
    }

    /**
     * On validation failure the old (submitted) value replaces the value of a text input.
     *
     * @return void
     */
    public function test_old_value_replaces_text_value_on_failure(): void
    {
        $input = (new Input())->withAttributes(['type' => 'text', 'name' => 'foo', 'value' => 'original']);

        $input->calculate($this->errorBag(['foo' => ['Required.']]), ['foo' => 'typed']);

        $this->assertSame('typed', $input->attributes['value']);
    }

    /**
     * Old values are HTML-escaped before being placed into the value attribute.
     *
     * @return void
     */
    public function test_old_value_is_escaped(): void
    {
        $input = (new Input())->withAttributes(['type' => 'text', 'name' => 'foo', 'value' => 'original']);

        $input->calculate($this->errorBag(['foo' => ['Required.']]), ['foo' => '<b>"x"']);

        $this->assertSame('&lt;b&gt;&quot;x&quot;', $input->attributes['value']);
    }

    /**
     * Old replacement can be globally disabled via "form.old" config.
     *
     * @return void
     */
    public function test_old_replacement_can_be_disabled_via_config(): void
    {
        config(['form.old' => false]);

        $input = (new Input())->withAttributes(['type' => 'text', 'name' => 'foo', 'value' => 'original']);
        $input->calculate($this->errorBag(['foo' => ['Required.']]), ['foo' => 'typed']);

        $this->assertSame('original', $input->attributes['value']);
    }

    /**
     * Nested array names are resolved against the old input ("foo[bar]" => $old['foo']['bar']).
     *
     * @return void
     */
    public function test_old_value_resolves_nested_name(): void
    {
        $input = (new Input())->withAttributes(['type' => 'text', 'name' => 'foo[bar]', 'value' => 'original']);

        $input->calculate($this->errorBag(['foo.bar' => ['Required.']]), ['foo' => ['bar' => 'nested']]);

        $this->assertSame('nested', $input->attributes['value']);
    }

    /**
     * Radio: gets checked when its value equals the "checked-value".
     *
     * @return void
     */
    public function test_radio_checked_by_checked_value(): void
    {
        $checked = (new Input(checkedValue: '2'))->withAttributes(['type' => 'radio', 'name' => 'foo', 'value' => '2']);
        $unchecked = (new Input(checkedValue: '2'))->withAttributes(['type' => 'radio', 'name' => 'foo', 'value' => '3']);

        $checked->calculate($this->errorBag(), null);
        $unchecked->calculate($this->errorBag(), null);

        $this->assertTrue($checked->attributes->has('checked'));
        $this->assertFalse($unchecked->attributes->has('checked'));
    }

    /**
     * Radio: the old value wins over "checked-value" on validation failure.
     *
     * @return void
     */
    public function test_radio_old_value_overrides_checked_value(): void
    {
        $matching = (new Input(checkedValue: 'blue'))->withAttributes(['type' => 'radio', 'name' => 'color', 'value' => 'red']);
        $other = (new Input(checkedValue: 'blue'))->withAttributes(['type' => 'radio', 'name' => 'color', 'value' => 'blue']);

        $matching->calculate($this->errorBag(['color' => ['Required.']]), ['color' => 'red']);
        $other->calculate($this->errorBag(['color' => ['Required.']]), ['color' => 'red']);

        $this->assertTrue($matching->attributes->has('checked'));
        $this->assertFalse($other->attributes->has('checked'));
    }

    /**
     * Checkbox: the "checked" prop drives the checked state when there are no errors.
     *
     * @return void
     */
    public function test_checkbox_checked_prop(): void
    {
        $on = (new Input(checked: true))->withAttributes(['type' => 'checkbox', 'name' => 'foo', 'value' => '1']);
        $off = (new Input(checked: false))->withAttributes(['type' => 'checkbox', 'name' => 'foo', 'value' => '1']);

        $on->calculate($this->errorBag(), null);
        $off->calculate($this->errorBag(), null);

        $this->assertTrue($on->attributes->has('checked'));
        $this->assertFalse($off->attributes->has('checked'));
    }

    /**
     * Checkbox: a truthy old value checks the box on validation failure.
     *
     * @return void
     */
    public function test_checkbox_checked_by_old(): void
    {
        $input = (new Input())->withAttributes(['type' => 'checkbox', 'name' => 'agree', 'value' => '1']);

        $input->calculate($this->errorBag(['other' => ['Required.']]), ['agree' => '1']);

        $this->assertTrue($input->attributes->has('checked'));
    }

    /**
     * Checkbox: an unchecked box (absent from old input) stays unchecked on validation failure.
     *
     * @return void
     */
    public function test_checkbox_unchecked_when_absent_from_old(): void
    {
        $input = (new Input(checked: true))->withAttributes(['type' => 'checkbox', 'name' => 'agree', 'value' => '1']);

        $input->calculate($this->errorBag(['agree' => ['Required.']]), ['other' => '1']);

        $this->assertFalse($input->attributes->has('checked'));
    }

    /**
     * Disabled / readonly props are turned into the corresponding HTML attributes.
     *
     * @return void
     */
    public function test_disabled_and_readonly_attributes(): void
    {
        $input = (new Input(disabled: true, readonly: true))->withAttributes(['type' => 'text', 'name' => 'foo']);

        $input->calculate($this->errorBag(), null);

        $this->assertTrue($input->attributes->has('disabled'));
        $this->assertTrue($input->attributes->has('readonly'));
    }

    /**
     * The "error" prop forces the error state regardless of the actual validation errors.
     *
     * @return void
     */
    public function test_error_prop_overrides_detection(): void
    {
        config(['form.error' => 'is-invalid']);

        $forcedOn = (new Input(error: true))->withAttributes(['type' => 'text', 'name' => 'foo']);
        $forcedOff = (new Input(error: false))->withAttributes(['type' => 'text', 'name' => 'foo']);

        $resultOn = $forcedOn->calculate($this->errorBag(), null);
        $resultOff = $forcedOff->calculate($this->errorBag(['foo' => ['Required.']]), null);

        $this->assertTrue($resultOn['hasError']);
        $this->assertFalse($resultOff['hasError']);
    }

    /**
     * The error state is detected from the validation bag when "form.error" is configured.
     *
     * @return void
     */
    public function test_error_detected_from_bag(): void
    {
        config(['form.error' => 'is-invalid']);

        $invalid = (new Input())->withAttributes(['type' => 'text', 'name' => 'foo']);
        $valid = (new Input())->withAttributes(['type' => 'text', 'name' => 'bar']);

        $this->assertTrue($invalid->calculate($this->errorBag(['foo' => ['Required.']]), null)['hasError']);
        $this->assertFalse($valid->calculate($this->errorBag(['foo' => ['Required.']]), null)['hasError']);
    }

    /**
     * The error state is not computed unless "form.error" is configured.
     *
     * @return void
     */
    public function test_error_not_detected_when_config_disabled(): void
    {
        config(['form.error' => null]);

        $input = (new Input())->withAttributes(['type' => 'text', 'name' => 'foo']);

        $this->assertFalse($input->calculate($this->errorBag(['foo' => ['Required.']]), null)['hasError']);
    }

    /**
     * Error detection matches a child key: name "data[key]" against error "data.key.sub".
     *
     * @return void
     */
    public function test_error_matches_child_key(): void
    {
        config(['form.error' => 'is-invalid']);

        $input = (new Input())->withAttributes(['type' => 'text', 'name' => 'data[key]']);

        $this->assertTrue($input->calculate($this->errorBag(['data.key.sub' => ['Required.']]), null)['hasError']);
    }

    /**
     * Error detection matches a parent key: name "data[key][sub]" against error "data.key".
     *
     * @return void
     */
    public function test_error_matches_parent_key(): void
    {
        config(['form.error' => 'is-invalid']);

        $input = (new Input())->withAttributes(['type' => 'text', 'name' => 'data[key][sub]']);

        $this->assertTrue($input->calculate($this->errorBag(['data.key' => ['Required.']]), null)['hasError']);
    }
}
