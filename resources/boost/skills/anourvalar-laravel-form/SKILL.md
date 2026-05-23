---
name: anourvalar-laravel-form
description: Load when working with the `anourvalar/laravel-form` package, which provides Laravel Blade components (x-input, x-select, x-textarea, x-hidden) that auto-repopulate old() input and add an error CSS class after validation failures.
---

# AnourValar Laravel Form

`anourvalar/laravel-form` ships four Blade view components (`Input`, `Select`, `Textarea`, `Hidden`) that wrap native HTML form elements and automatically: (1) replace the rendered value with `old()` after a validation failure, and (2) attach a configurable CSS class to elements whose `name` attribute is present in `$errors`. Components are registered globally via `loadViewComponentsAs()` so they are usable as `<x-input>`, `<x-select>`, `<x-textarea>`, `<x-hidden>` (with an optional prefix from `config('form.namespace')`).

## When to use

- Building Blade forms in a Laravel app that depends on `anourvalar/laravel-form`.
- Adding/modifying inputs that should preserve user input after a validator redirect (`->withInput()`).
- Wiring up an "invalid" CSS class (e.g. Bootstrap `is-invalid`) to fields that produced errors.
- Rendering grouped hidden fields from a nested array via `<x-hidden>`.
- Diagnosing why old() repopulation or the error class is not appearing — check `config/form.php`.

## Facades

This package exposes **no facades**. It is a pure Blade-component package. It also has no service container bindings, console commands, or middleware — only the four view components and a publishable config file.

(The package's `composer.json` requires `anourvalar/config-helper`, which provides the unrelated `ConfigHelper` facade used internally by the `select.blade.php` view via `ConfigHelper::toSelect(...)`. That facade is documented by its own package, not this one.)

## Services / Blade components

All four components extend `Illuminate\View\Component` and are registered in
`AnourValar\LaravelForm\Providers\LaravelFormServiceProvider::boot()` via
`loadViewComponentsAs(config('form.namespace'), [...])`. The default
`form.namespace` is an empty string, so the tags are `<x-input>`, `<x-select>`,
`<x-textarea>`, `<x-hidden>`. If you set `'namespace' => 'form'`, the tags
become `<x-form-input>`, etc.

### `AnourValar\LaravelForm\Components\Input` (`<x-input>`)

Renders `<input ... />` via `form::input`. Constructor props:

- `disabled` (mixed) — truthy forces `disabled` attribute.
- `readonly` (mixed) — truthy forces `readonly` attribute.
- `checked` (mixed) — initial checked state for checkbox/radio (overridden by `old()` when present).
- `checked-value` (mixed) — for `type="radio"`, the value that should be marked as checked when no old() exists.
- `merge` (array|mixed) — extra attributes merged onto the `<input>` (in addition to `config('form.default_attributes.input')`).
- `error` (mixed) — manual override of error state. If non-null, its truthiness wins over the auto-detected `$errors` lookup.

All other HTML attributes (`type`, `name`, `value`, `class`, ...) pass through `$attributes`.

Behavior of `calculate()`:
- For `type="radio"` with `old()` present, `checked` becomes `value == old`. Otherwise it compares `value` against `checkedValue`.
- For `type="checkbox"` with `old()` present, `checked` is taken from `old()`.
- For all other types, when `old()` is a scalar it replaces the rendered `value`.

```blade
{{-- text input, auto-repopulates old('email') --}}
<x-input type="text" name="email" value="{{ $user->email }}" />

{{-- checkbox --}}
<x-input type="checkbox" name="agree" value="1" :checked="$default" />

{{-- radio group --}}
<x-input type="radio" name="role" value="admin" checked-value="{{ $user->role }}" />
<x-input type="radio" name="role" value="user"  checked-value="{{ $user->role }}" />

{{-- force the error class on regardless of $errors --}}
<x-input type="text" name="foo" :error="true" />
```

### `AnourValar\LaravelForm\Components\Select` (`<x-select>`)

Renders `<select>...</select>` via `form::select`. Constructor props:

- `options` (mixed) — array passed to `ConfigHelper::toSelect($options, $selected, $conditions, $mapping)` to build `<option>` tags.
- `prepends` (mixed) — options rendered before `options` (no conditions/mapping applied).
- `appends` (mixed) — options rendered after `options`.
- `conditions` (mixed) — forwarded to `ConfigHelper::toSelect()`.
- `mapping` (mixed) — forwarded to `ConfigHelper::toSelect()`.
- `selected` (mixed) — initially selected value(s); overridden by `old()` when present.
- `disabled` / `readonly` / `merge` / `error` — same semantics as `Input`.

The `multiple` attribute on the `<select>` toggles whether old() is required as scalar (single) or may be an array (multi).

```blade
<x-select name="status" :options="[
    'draft'     => ['title' => 'Draft'],
    'published' => ['title' => 'Published'],
]" selected="draft" />

{{-- with a placeholder via prepends --}}
<x-select name="country_id"
    :prepends="['' => ['title' => '— select —']]"
    :options="$countries"
    :selected="$user->country_id" />

{{-- multi-select --}}
<x-select name="tags[]" multiple :options="$tags" :selected="$user->tag_ids" />
```

### `AnourValar\LaravelForm\Components\Textarea` (`<x-textarea>`)

Renders `<textarea>...</textarea>` via `form::textarea`. Constructor props:

- `value` (mixed) — initial body; HTML-escaped, concatenated with the slot. Overridden by `old()` when scalar.
- `disabled` / `readonly` / `merge` / `error` — same semantics as `Input`.

```blade
<x-textarea name="bio" :value="$user->bio" rows="5" />

{{-- slot is appended to $value, both escaped --}}
<x-textarea name="notes">{{ $defaultNotes }}</x-textarea>
```

### `AnourValar\LaravelForm\Components\Hidden` (`<x-hidden>`)

Renders one or more `<input type="hidden">` from a (possibly nested) associative array. Constructor props:

- `data` (?array) — key/value pairs (scalar values; nested arrays produce `name="parent[child]"` etc.).
- `prefix` (array|string|null) — name prefix applied to every emitted hidden input.

Empty-string scalar values (`mb_strlen($value) == 0`) are skipped. Names and values are escaped with `e()`. Unlike the other components, `Hidden` does **not** read `old()` or `$errors` — it is purely a writer.

```blade
<x-hidden :data="['return_to' => url()->previous(), 'token' => $token]" />

{{-- nested + prefix => name="filter[type]" etc. --}}
<x-hidden prefix="filter" :data="['type' => 'active', 'tags' => ['php', 'laravel']]" />
```

### `AnourValar\LaravelForm\Components\CalculateTrait`

Internal trait used by `Input`, `Select`, `Textarea`. Declares the
`abstract public function calculate(ViewErrorBag $errors, ?array $old, ?string $slot = null): array`
contract and provides `protected getBackground(...)` which performs the
`name` → dotted-key translation (e.g. `data[items][0][name]` →
`data.items.0.name`) used to look up errors and old() values. You normally
do **not** interact with this trait directly; you only need to know it
exists if you subclass one of the components.

## Configuration (`config/form.php`)

Publish with:

```bash
php artisan vendor:publish --provider="AnourValar\LaravelForm\Providers\LaravelFormServiceProvider" --tag=config
```

Keys (defaults shown):

```php
return [
    // Prefix for component tags. '' => <x-input>; 'form' => <x-form-input>.
    'namespace' => '',

    // CSS class added to elements whose name has errors. null disables the feature entirely.
    'error' => null,

    // Replace rendered value with old() after validation failure. true by default.
    'old' => true,

    // Default attributes merged into every rendered tag.
    'default_attributes' => [
        'input'    => [],
        'select'   => [],
        'textarea' => [],
    ],
];
```

Set `'error' => 'is-invalid'` (Bootstrap) or `'error' => 'border-red-500'` (Tailwind) to opt into the auto error class.

## Usage examples

```blade
{{-- resources/views/users/edit.blade.php --}}
<form method="POST" action="{{ route('users.update', $user) }}">
    @csrf
    @method('PUT')

    <x-hidden :data="['return_to' => url()->previous()]" />

    <div class="mb-3">
        <label for="name">Name</label>
        <x-input id="name" type="text" name="name"
            value="{{ $user->name }}" class="form-control" required />
    </div>

    <div class="mb-3">
        <label for="email">Email</label>
        <x-input id="email" type="email" name="email"
            value="{{ $user->email }}" class="form-control" />
    </div>

    <div class="mb-3">
        <label for="role">Role</label>
        <x-select id="role" name="role" class="form-select"
            :prepends="['' => ['title' => '— pick one —']]"
            :options="['admin' => ['title' => 'Admin'], 'user' => ['title' => 'User']]"
            :selected="$user->role" />
    </div>

    <div class="mb-3">
        <label>Subscribe?</label>
        <x-input type="checkbox" name="subscribe" value="1"
            :checked="$user->subscribe" />
    </div>

    <div class="mb-3">
        <label for="bio">Bio</label>
        <x-textarea id="bio" name="bio" rows="6"
            class="form-control" :value="$user->bio" />
    </div>

    <button type="submit">Save</button>
</form>
```

Pair with a standard validator + redirect-with-input in the controller — no extra work is needed for the components to repopulate:

```php
// app/Http/Controllers/UserController.php
public function update(Request $request, User $user)
{
    $data = $request->validate([
        'name'  => ['required', 'string', 'max:255'],
        'email' => ['required', 'email'],
        'role'  => ['required', 'in:admin,user'],
        'bio'   => ['nullable', 'string'],
    ]);

    $user->update($data);

    return redirect()->route('users.edit', $user);
}
```

On validation failure Laravel calls `->withInput()` automatically; the
components pull from `old()` and (if `config('form.error')` is set) add the
error class to fields whose names appear in `$errors`.

## Conventions / gotchas

- **No facades, no helpers, no commands.** The package is exclusively Blade components. Do not try `Form::open(...)` — that belongs to other packages.
- **`config('form.error')` is `null` by default**, which *disables* the error class entirely. If you expect a `is-invalid` class and don't see one, publish the config and set the `error` key.
- **`config('form.old')` is `true` by default.** Set it to `false` to opt out of `old()` repopulation globally.
- **The `error` prop wins over auto-detection.** Pass `:error="true|false"` to force the visual error state regardless of `$errors`.
- **The component tag prefix comes from `config('form.namespace')`.** Tags will be `<x-input>` with the default empty namespace; if you change it to `'form'` they become `<x-form-input>`, etc. Re-publish/clear view caches after changing.
- **`Hidden` skips empty scalars** (`mb_strlen($value) === 0`) and any non-scalar/non-array leaves silently — do not rely on it to emit `<input value="">`.
- **Nested error keys are matched both ways.** `getBackground()` translates `name="data[items][0][title]"` to dotted `data.items.0.title` and walks up the path, so an error on `data.items` or down on `data.items.0.title.sub` both light up the field.
- **`select.blade.php` calls `ConfigHelper::toSelect(...)`.** That facade is provided by the `anourvalar/config-helper` dependency. The shape of each option entry is `[$value => ['title' => '...', ...attributes]]`; see that package for `conditions` / `mapping` semantics.
- **`default_attributes` merge order:** the component's own `$attributes`, then `config('form.default_attributes.<tag>')`, then the per-call `merge` prop. Later `merge()` calls in the Blade view append CSS classes rather than replacing them.
- **Service provider auto-registers** via `extra.laravel.providers` in `composer.json`; no manual `config/app.php` edit is needed on Laravel 8+.
