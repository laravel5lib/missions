<div class="panel panel-default" v-tour-guide="">
    <div class="panel-heading">
        <h5>Description</h5>
    </div>
    <div class="panel-body">
        {!! Markdown::convertToHtml($trip->description) !!}
    </div>
</div>

@section('tour')
    <script>
        window.pageSteps = [
            {
                id: 'deadlines',
                title: 'Trip Description',
                text: 'A description and important details about the trip are placed here for convenience. Public trips have this information available before the trip registration form.'
            },
        ];
    </script>
@endsection