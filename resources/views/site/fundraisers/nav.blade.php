<ul class="nav nav-pills nav-stacked hidden-sm hidden-xs">
    <li>
        <a href="{{ url($fundraiser->url) }}">
            <i class="fa fa-window-maximize" style="padding-right: 1em"></i> View Fundraiser
        </a>
    </li>
    <li class="{{ request()->is(request()->segment(1).'/fundraisers/*/edit') ? 'active' : '' }}">
        <a href="{{ url(request()->segment(1).'/fundraisers/'.$fundraiser->uuid.'/edit') }}">
            <i class="fa fa-edit" style="padding-right: 1em"></i> Edit Fundraiser
        </a>
    </li>
    <li class="{{ request()->is(request()->segment(1).'/fundraisers/*/sharing') ? 'active' : '' }}">
        <a href="{{ url(request()->segment(1).'/fundraisers/'.$fundraiser->uuid.'/sharing') }}">
            <i class="fa fa-share-square-o" style="padding-right: 1em"></i> Sharing Options
        </a>
    </li>
    <li class="{{ request()->is(request()->segment(1).'/fundraisers/*/updates') ? 'active' : '' }}">
        <a href="{{ url(request()->segment(1).'/fundraisers/'.$fundraiser->uuid.'/updates') }}">
            <i class="fa fa-newspaper-o" style="padding-right: 1em"></i> Post an Update
        </a>
    </li>
    <li class="{{ request()->is(request()->segment(1).'/fundraisers/*/donations') ? 'active' : '' }}">
        <a href="{{ url(request()->segment(1).'/fundraisers/'.$fundraiser->uuid.'/donations') }}">
            <i class="fa fa-money" style="padding-right: 1em"></i> View Donations
        </a>
    </li>
    <li>
        <a href="{{ url('fundraisers#ideas') }}">
            <i class="fa fa-lightbulb-o" style="padding-right: 1.5em"></i> Fundraising Ideas
        </a>
    </li>
    <li>
        <a href="{{ url(request()->segment(1).'/'.$fundraiser->fund->fundable_type.'/'.$fundraiser->fund->fundable_id)}}">
            <i class="fa fa-arrow-left" style="padding-right: 1em"></i> 
            {{ ucwords(substr($fundraiser->fund->fundable_type, 0, -1)) }}
        </a>
    </li>
</ul>
<div class="dropdown visible-sm visible-xs">
  <button class="btn btn-default-hollow dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"> Menu
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
    <li>
        <a href="{{ url($fundraiser->url) }}">
            <i class="fa fa-window-maximize" style="padding-right: 1em"></i> View Fundraiser
        </a>
    </li>
    <li class="{{ request()->is(request()->segment(1).'/fundraisers/*/edit') ? 'active' : '' }}">
        <a href="{{ url(request()->segment(1).'/fundraisers/'.$fundraiser->uuid.'/edit') }}">
            <i class="fa fa-edit" style="padding-right: 1em"></i> Edit Fundraiser
        </a>
    </li>
    <li class="{{ request()->is(request()->segment(1).'/fundraisers/*/sharing') ? 'active' : '' }}">
        <a href="{{ url(request()->segment(1).'/fundraisers/'.$fundraiser->uuid.'/sharing') }}">
            <i class="fa fa-share-square-o" style="padding-right: 1em"></i> Sharing Options
        </a>
    </li>
    <li class="{{ request()->is(request()->segment(1).'/fundraisers/*/updates') ? 'active' : '' }}">
        <a href="{{ url(request()->segment(1).'/fundraisers/'.$fundraiser->uuid.'/updates') }}">
            <i class="fa fa-newspaper-o" style="padding-right: 1em"></i> Post an Update
        </a>
    </li>
    <li class="{{ request()->is(request()->segment(1).'/fundraisers/*/donations') ? 'active' : '' }}">
        <a href="{{ url('fundraisers/'.$fundraiser->uuid.'/donations') }}">
            <i class="fa fa-money" style="padding-right: 1em"></i> View Donations
        </a>
    </li>
    <li>
        <a href="{{ url('fundraisers#ideas') }}">
            <i class="fa fa-lightbulb-o" style="padding-right: 1.5em"></i> Fundraising Ideas
        </a>
    </li>
    <li>
        <a href="{{ url(request()->segment(1).'/'.$fundraiser->fund->fundable_type.'/'.$fundraiser->fund->fundable_id)}}">
            <i class="fa fa-arrow-left" style="padding-right: 1em"></i> 
            {{ ucwords(substr($fundraiser->fund->fundable_type, 0, -1)) }}
        </a>
    </li>
  </ul>
</div>
<hr class="divider inv">