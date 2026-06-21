<?php

namespace AnourValar\LaravelForm\Tests;

use AnourValar\LaravelForm\Components\Textarea;

class TextareaTest extends AbstractSuite
{
    /**
     * Without validation errors the "value" prop is escaped and used.
     *
     * @return void
     */
    public function test_value_is_escaped(): void
    {
        $textarea = (new Textarea(value: 'Hello <b>'))->withAttributes(['name' => 'foo']);

        $result = $textarea->calculate($this->errorBag(), null);

        $this->assertSame('Hello &lt;b&gt;', $result['value']);
    }

    /**
     * The slot is appended to the value.
     *
     * @return void
     */
    public function test_slot_is_appended_to_value(): void
    {
        $textarea = (new Textarea(value: 'Hello'))->withAttributes(['name' => 'foo']);

        $result = $textarea->calculate($this->errorBag(), null, ' world');

        $this->assertSame('Hello world', $result['value']);
    }

    /**
     * On validation failure the old value replaces the content.
     *
     * @return void
     */
    public function test_old_value_replaces_content_on_failure(): void
    {
        $textarea = (new Textarea(value: 'Hello'))->withAttributes(['name' => 'foo']);

        $result = $textarea->calculate($this->errorBag(['foo' => ['Required.']]), ['foo' => 'typed <x>']);

        $this->assertSame('typed &lt;x&gt;', $result['value']);
    }

    /**
     * An array old value is ignored and the "value" prop is used instead.
     *
     * @return void
     */
    public function test_array_old_value_is_ignored(): void
    {
        $textarea = (new Textarea(value: 'Hello'))->withAttributes(['name' => 'foo']);

        $result = $textarea->calculate($this->errorBag(['foo' => ['Required.']]), ['foo' => ['array']]);

        $this->assertSame('Hello', $result['value']);
    }

    /**
     * Disabled / readonly props are turned into the corresponding HTML attributes.
     *
     * @return void
     */
    public function test_disabled_and_readonly_attributes(): void
    {
        $textarea = (new Textarea(disabled: true, readonly: true))->withAttributes(['name' => 'foo']);

        $textarea->calculate($this->errorBag(), null);

        $this->assertTrue($textarea->attributes->has('disabled'));
        $this->assertTrue($textarea->attributes->has('readonly'));
    }

    /**
     * The "error" prop forces the error state.
     *
     * @return void
     */
    public function test_error_prop_overrides_detection(): void
    {
        config(['form.error' => 'is-invalid']);

        $forcedOn = (new Textarea(error: true))->withAttributes(['name' => 'foo']);
        $forcedOff = (new Textarea(error: false))->withAttributes(['name' => 'foo']);

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

        $textarea = (new Textarea())->withAttributes(['name' => 'foo']);

        $this->assertTrue($textarea->calculate($this->errorBag(['foo' => ['Required.']]), null)['hasError']);
    }
}
