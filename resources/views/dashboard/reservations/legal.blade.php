@extends('dashboard.reservations.show')

@section('tab')
    <ul class="nav nav-tabs">
        <li role="presentation" class="active">
            <a href="#tos" data-toggle="pill">Terms of Service</a>
        </li>
        <li role="presentation">
            <a href="#roca" data-toggle="pill">Rules of Conduct</a>
        </li>
    </ul>
    
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Legal</h5>
        </div>
        <div class="panel-body">
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="tos">
                    @include('dashboard.reservations.tos', ['reservation' => $reservation])
                </div>
                <div role="tabpanel" class="tab-pane" id="roca">
                    @include('dashboard.reservations.rules', ['reservation' => $reservation])
                </div>
            </div>
        </div>
    </div>
    @endsection

    @section('tour')
    <script>
        window.pageSteps = [
            {
                id: 'legal',
                title: 'Legal Agreements',
                text: 'For your convience, you may review the terms of service and rules of conduct and other agreements here.'
            }
        ];
    </script>
@endsection