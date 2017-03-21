 <div class="panel panel-default">
    <ul class="nav nav-pills nav-stacked">
        <li class="{{ $tab === 'details' || $tab === 'reservations' ? 'active' : '' }}"><a href="{{ url('dashboard/groups/'. $groupId .'/trips/' . $id) . '/details' }}">Details</a></li>
        <li class="{{ $tab === 'description' ? 'active' : '' }}"><a href="{{ url('dashboard/groups/'. $groupId .'/trips/' . $id) . '/description' }}">Description</a></li>
        <li class="{{ $tab === 'registration' ? 'active' : '' }}"><a href="{{ url('dashboard/groups/'. $groupId .'/trips/' . $id) . '/registration' }}">Registration</a></li>
        <li class="{{ $tab === 'pricing' ? 'active' : '' }}"><a href="{{ url('dashboard/groups/'. $groupId .'/trips/' . $id) . '/pricing' }}">Pricing</a></li>
        <li class="{{ $tab === 'requirements' ? 'active' : '' }}"><a href="{{ url('dashboard/groups/'. $groupId .'/trips/' . $id) . '/requirements' }}">Requirements</a></li>
        <li class="{{ $tab === 'deadlines' ? 'active' : '' }}"><a href="{{ url('dashboard/groups/'. $groupId .'/trips/' . $id) . '/deadlines' }}">Deadlines</a></li>
    </ul>
</div>
