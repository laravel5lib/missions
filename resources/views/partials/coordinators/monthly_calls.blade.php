<div class="row">
  <div class="col-xs-10 col-xs-offset-1">
    <h2 class="text-center">Monthly Coordinator Calls</h2>
    <hr class="divider red-small xlg">
    <div class="row">
      <div class="col-xs-8 col-xs-offset-2">
        <p class="text-center">Each month 1Nation1Day Coordinators from around the world will join together as one global team.  Led by the Missions.Me team, you’ll get updates on the campaign, new media to share with your team, opportunities to answer questions live and exchange ideas with other coordinators.</p> 
      </div><!-- end col -->
    </div><!-- end row -->
    <hr class="divider inv lg">
    <div class="panel panel-default">
      <div class="panel-heading text-center" style="background:#2073d9;padding:40px;">
        <h4 class="text-white" style="display:inline-block;margin-right:10px;">Connect to the Video Conference on meeting dates</h4> <a class="btn btn-white-hollow btn-md" href="https://zoom.us/j/582208588"><i class="fa fa-play icon-left"></i> Connect</a>
      </div><!-- end panel-title -->
      <div class="panel-body" style="padding:40px;">
        <h6 class="text-uppercase text-center">Video Conference Call Dates</h6>
        <hr class="divider lg" style="width:50px;">
        <div class="row">

          @php
            $calls = collect(
              json_decode(
                file_get_contents(
                  resource_path('assets/js/data/coordinators/calls.json')
                ), 
                true
              )
            )
          @endphp

          @foreach($calls->chunk(5) as $chunk)
            <div class="col-md-4 col-md-offset-0 col-sm-12 col-sm-offset-0">
              <ul class="list-unstyled">
                @foreach($chunk as $call)
                  <li>
                    <img src="/images/coordinators/chat-icon.png" style="margin-right:5px;" width="25px" height="25px"> 
                    <h6 style="display:inline-block;margin-bottom:0;" 
                        @isset($call['mute']) class="text-muted" @endif
                    >
                      {{ $call['date'] }}
                    </h6>

                    @isset($call['url'])
                      <p class="text-uppercase" style="font-size:10px;margin-left:35px;">
                        <a href="{{ $call['url'] }}" target="_blank">Watch Meeting</a>
                      </p>
                    @endif

                  </li>
                @endforeach
              </ul>
            </div>
          @endforeach
        </div><!-- end row -->
        
        <hr class="divider inv">
        <p class="small text-italic">* Note, you’ll need to download the FREE Zoom application on a computer or mobile device to connect to the meeting. Please have this installed before tuning into your first meeting.</p>
      </div><!-- end panel-body -->
    </div><!-- end panel -->
  </div>
</div>