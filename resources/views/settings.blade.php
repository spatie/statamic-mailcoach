@extends('statamic::layout')

@section('title', __('Mailcoach Settings'))

@section('content')
    <publish-form
            title='Configuration'
            action={{ $route }}
        :blueprint='@json($blueprint)'
            :meta='@json($meta)'
            :values='@json($values)'
    ></publish-form>
@stop
