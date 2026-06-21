<?php

namespace AnourValar\LaravelForm\Tests;

use AnourValar\LaravelForm\Components\Hidden;

class HiddenTest extends AbstractSuite
{
    /**
     * Flat data produces a hidden input per field.
     *
     * @return void
     */
    public function test_flat_fields(): void
    {
        $html = (new Hidden(['foo' => 'bar', 'baz' => '1']))->render();

        $this->assertSame(
            '<input type="hidden" name="foo" value="bar"/><input type="hidden" name="baz" value="1"/>',
            $html
        );
    }

    /**
     * Nested data produces bracketed names.
     *
     * @return void
     */
    public function test_nested_fields_build_bracket_names(): void
    {
        $html = (new Hidden(['a' => ['b' => 'c']]))->render();

        $this->assertSame('<input type="hidden" name="a[b]" value="c"/>', $html);
    }

    /**
     * Deeply nested data keeps nesting the brackets.
     *
     * @return void
     */
    public function test_deeply_nested_fields(): void
    {
        $html = (new Hidden(['a' => ['b' => ['c' => 'd']]]))->render();

        $this->assertSame('<input type="hidden" name="a[b][c]" value="d"/>', $html);
    }

    /**
     * A string prefix wraps every top-level field.
     *
     * @return void
     */
    public function test_string_prefix(): void
    {
        $html = (new Hidden(['foo' => 'bar'], 'pre'))->render();

        $this->assertSame('<input type="hidden" name="pre[foo]" value="bar"/>', $html);
    }

    /**
     * An array prefix of several levels produces a nested name.
     *
     * @return void
     */
    public function test_array_prefix(): void
    {
        $html = (new Hidden(['foo' => 'bar'], ['a', 'b']))->render();

        $this->assertSame('<input type="hidden" name="a[b][foo]" value="bar"/>', $html);
    }

    /**
     * Names and values are HTML-escaped.
     *
     * @return void
     */
    public function test_escapes_name_and_value(): void
    {
        $html = (new Hidden(['na"me' => 'va"lue']))->render();

        $this->assertSame('<input type="hidden" name="na&quot;me" value="va&quot;lue"/>', $html);
    }

    /**
     * Null, non-scalar and empty-string values are skipped.
     *
     * @return void
     */
    public function test_skips_null_nonscalar_and_empty(): void
    {
        $html = (new Hidden(['a' => null, 'b' => '', 'c' => new \stdClass(), 'd' => 'keep']))->render();

        $this->assertSame('<input type="hidden" name="d" value="keep"/>', $html);
    }

    /**
     * Zero and other numeric values are kept (they have a length).
     *
     * @return void
     */
    public function test_keeps_zero_and_numeric_values(): void
    {
        $html = (new Hidden(['a' => 0, 'b' => 1.5]))->render();

        $this->assertSame(
            '<input type="hidden" name="a" value="0"/><input type="hidden" name="b" value="1.5"/>',
            $html
        );
    }

    /**
     * Empty data renders to an empty string.
     *
     * @return void
     */
    public function test_empty_data_renders_empty_string(): void
    {
        $this->assertSame('', (new Hidden([]))->render());
        $this->assertSame('', (new Hidden(null))->render());
    }
}
