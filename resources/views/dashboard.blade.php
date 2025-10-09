@extends('statamic::layout')

@section('content')
    <header class="py-8 mt-4">
        <div class="flex items-center">
            <h1 class="flex-1 flex items-center">
                @include('statamic-mailcoach::partials.mailcoachIcon', ['width' => '8'])
                Mailcoach
            </h1>
            <ui-button :href="'{{ $baseUrl }}/dashboard'" variant="primary" target="_blank">
                <span class="mr-1">Mailcoach</span>
                <svg class="w-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M23.251 7.498V.748h-6.75m6.75 0l-15 15m3-10.5h-9a1.5 1.5 0 0 0-1.5 1.5v15a1.5 1.5 0 0 0 1.5 1.5h15a1.5 1.5 0 0 0 1.5-1.5v-9"/></svg>
            </ui-button>
        </div>
    </header>
    <section>
        @include('statamic-mailcoach::partials.campaigns')

        @include('statamic-mailcoach::partials.lists')
    </section>
@endsection
