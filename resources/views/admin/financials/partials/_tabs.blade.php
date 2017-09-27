<ul class="nav nav-tabs">
    <li role="presentation" class="{{ set_active('admin/transactions') }}">
        <a href="{{ url('admin/transactions') }}"><i class="fa fa-usd"></i> Transactions</a>
    </li>

    @can('view', \App\Models\v1\Donor::class)
        <li role="presentation" class="{{ set_active('admin/donors') }}">
            <a href="{{ url('admin/donors') }}"><i class="fa fa-users"></i> Donors</a>
        </li>
    @endcan

    @can('view', \App\Models\v1\Fund::class)
        <li role="presentation" class="{{ set_active('admin/funds') }}">
            <a href="{{ url('admin/funds') }}"><i class="fa fa-pie-chart"></i> Funds</a>
        </li>
    @endcan
</ul>