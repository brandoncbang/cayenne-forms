<x-mail::message :subcopy="'[Manage notification settings]('.route('forms.edit', ['form' => $entry->form]).')'">
# {{ __('New form entry from ":form"', ['form' => escapeMarkdown($entry->form->name)]) }}

<x-mail::panel>
@foreach($entry->data as $key => $value)
**{{ escapeMarkdown(Str::headline($key)) }}:**  
{{ escapeMarkdown($value) }}

@endforeach
</x-mail::panel>

<x-mail::button :url="route('forms.entries.index', ['form' => $entry->form])">
{{ __('View recent entries') }}
</x-mail::button>
</x-mail::message>
