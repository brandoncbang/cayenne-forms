<x-mail::message>
# {{ __('You have been invited to manage your website\'s forms on :app!', ['app' => config('app.name')]) }}

<x-mail::button :url="route('invites.show', ['invite' => $invite])">
{{ __('Accept Invite') }}
</x-mail::button>

{{ __('If you did not expect to receive an invite, you may discard this email.') }}
</x-mail::message>
