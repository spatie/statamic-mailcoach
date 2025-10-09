@extends('statamic::layout')

@section('title', __('Mailcoach Settings'))

@section('content')
    <ui-publish-form
        title="Configuration"
        :blueprint="{{ Js::from($blueprint) }}"
        :initial-values="{{ Js::from($values) }}"
        :initial-meta="{{ Js::from($meta) }}"
        :submit-url="'{{ $route }}'"
    ></ui-publish-form>
@stop
