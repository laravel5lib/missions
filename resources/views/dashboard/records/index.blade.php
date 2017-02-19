@extends('dashboard.layouts.default')

@section('content')

@yield('header')
<hr class="divider inv lg">

<div class="container" v-tour-guide="">
    <div class="row">
        <div class="col-sm-4 tour-step-navigation">
            @include('dashboard.records.layouts.menu', [
            'links' => config('navigation.dashboard.records')
            ])
        </div>
        <div class="col-sm-8">
            @yield('tab')
        </div>
    </div>
</div>

@endsection

@section('tour')
    <script>
        window.pageSteps = [
            {
                id: 'navigation',
                title: 'Manage Documents',
                text: 'Find all your travel documents and browse by type using the menu.',
                attachTo: {
                    element: '.tour-step-navigation',
                    on: 'top'
                },
            },
            {
                id: 'add',
                title: 'Add Documents',
                text: 'Add a new document to your records.',
                attachTo: {
                    element: '.tour-step-add',
                    on: 'top'
                },
            },
            {
                id: 'view',
                title: 'View a Document',
                text: 'Select document cards that show up here to see more details.',
                attachTo: {
                    element: '.tour-step-view',
                    on: 'top'
                },
            }
        ];
    </script>
@endsection