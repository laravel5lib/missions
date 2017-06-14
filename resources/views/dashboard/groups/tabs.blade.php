<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <ul class="nav nav-tabs">
                <li role="presentation" @if($active == 'details') class="active" @endif>
                    <a href="{{ url('/dashboard/groups/' . $group->id) }}">Details</a>
                </li>
                <li role="presentation tour-step-teams" @if($active == 'teams') class="active" @endif>
                    <a href="{{ url('/dashboard/groups/' . $group->id) }}/teams">Squads</a>
                </li>
                <li role="presentation" @if($active == 'rooming') class="active" @endif>
                    <a href="{{ url('/dashboard/groups/' . $group->id) }}/rooms">Rooming</a>
                </li>
            </ul>
        </div>
    </div>
</div>