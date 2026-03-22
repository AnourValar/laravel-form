@props(['value', 'default' => null, 'timezoneClient' => null])
@inject('dateHelper', AnourValar\LaravelAtom\Helpers\DateHelper::class)

{{ $dateHelper->formatDateTime($value, $default, $timezoneClient) }}{{-- NO EMPTY LINE --}}