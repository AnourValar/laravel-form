@props(['value', 'time' => true, 'default' => null, 'timezoneClient' => null])
@inject('dateHelper', AnourValar\LaravelAtom\Helpers\DateHelper::class)

{{ $dateHelper->formatDate($value, $time, $default, $timezoneClient) }}{{-- NO EMPTY LINE --}}