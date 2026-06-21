<?php

namespace AnourValar\LaravelForm\Tests;

use Illuminate\Support\Facades\Blade;

class RenderTest extends AbstractSuite
{
    /**
     * End-to-end: on validation failure the rendered input shows the old value and the error class.
     *
     * @return void
     */
    public function test_input_old_value_and_error_class_end_to_end(): void
    {
        config(['form.error' => 'is-invalid']);
        $this->shareErrors(['foo' => ['The foo field is required.']]);
        $this->setOld(['foo' => 'old-value']);

        $html = trim(Blade::render('<x-input type="text" name="foo" value="bar" />'));

        $this->assertStringContainsString('value="old-value"', $html);
        $this->assertStringContainsString('class="is-invalid"', $html);
    }

    /**
     * A valid sibling field does not receive the error class.
     *
     * @return void
     */
    public function test_input_without_error_has_no_error_class(): void
    {
        config(['form.error' => 'is-invalid']);
        $this->shareErrors(['other' => ['The other field is required.']]);

        $html = trim(Blade::render('<x-input type="text" name="foo" value="bar" />'));

        $this->assertStringNotContainsString('is-invalid', $html);
    }

    /**
     * The <x-select> component renders options and marks the selected one.
     *
     * @return void
     */
    public function test_select_renders_options(): void
    {
        $html = trim(Blade::render(
            '<x-select name="foo" :options="[1 => [\'title\' => \'One\'], 2 => [\'title\' => \'Two\']]" selected="2" />'
        ));

        $this->assertStringContainsString('<select ', $html);
        $this->assertStringContainsString('<option value="1">One</option>', $html);
        $this->assertStringContainsString('<option value="2" selected="selected">Two</option>', $html);
    }

    /**
     * The <x-select> component renders prepends before the options.
     *
     * @return void
     */
    public function test_select_renders_prepends(): void
    {
        $html = trim(Blade::render(
            '<x-select name="foo" :prepends="[\'\' => [\'title\' => \'Choose...\']]" :options="[1 => [\'title\' => \'One\']]" />'
        ));

        $this->assertStringContainsString('Choose...', $html);
        $this->assertStringContainsString('<option value="1">One</option>', $html);
    }

    /**
     * End-to-end: on validation failure the select marks the old value as selected.
     *
     * @return void
     */
    public function test_select_old_value_selected_end_to_end(): void
    {
        $this->shareErrors(['foo' => ['The foo field is required.']]);
        $this->setOld(['foo' => '1']);

        $html = trim(Blade::render(
            '<x-select name="foo" :options="[1 => [\'title\' => \'One\'], 2 => [\'title\' => \'Two\']]" selected="2" />'
        ));

        $this->assertStringContainsString('<option value="1" selected="selected">One</option>', $html);
        $this->assertStringContainsString('<option value="2">Two</option>', $html);
    }

    /**
     * The <x-textarea> component renders the slot as its content.
     *
     * @return void
     */
    public function test_textarea_renders_slot(): void
    {
        $html = trim(Blade::render('<x-textarea name="foo">Hello</x-textarea>'));

        $this->assertStringContainsString('<textarea ', $html);
        $this->assertStringContainsString('name="foo"', $html);
        $this->assertStringContainsString('>Hello</textarea>', $html);
    }

    /**
     * End-to-end: on validation failure the textarea shows the old value.
     *
     * @return void
     */
    public function test_textarea_old_value_end_to_end(): void
    {
        $this->shareErrors(['foo' => ['The foo field is required.']]);
        $this->setOld(['foo' => 'typed text']);

        $html = trim(Blade::render('<x-textarea name="foo">Hello</x-textarea>'));

        $this->assertStringContainsString('>typed text</textarea>', $html);
    }
}
