@extends('site.layouts.default')

@section('content')
<div class="content-page-header">
    <img class="img-responsive" src="images/speakers/speaker-header.jpg" alt="Speakers">
    <div class="c-page-header-text">
    	<h1 class="text-uppercase dash-trailing">Speakers</h1>
    </div><!-- end c-page-header-content -->
</div><!-- end c-page-header -->
<div class="white-bg">
	<div class="container">
	<div class="content-section">
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2">
				<h1 class="text-primary text-center">Serving The Local Church</h1>
				<p class="text-center">At Missions.Me, we believe the local church is the hope of the world. All of the Missions.Me speakers are passionate about serving the local church and its specific vision to impact their community. Our speakers are known for bringing life-changing messages that awaken destiny, passion, and deep spiritual commitment. If you are interested in having one of our speakers come serve your church, school, or conference, simply fill out the form below and we'll get back to you within 5 business days to start the conversation.</p>
				<hr class="divider inv">
				<div class="row">
					<div class="col-xs-6 col-sm-4 text-center">
						<div class="panel panel-default">
							<img class="img-responsive" src="images/speakers/dom.jpg" alt="Dominic Russo">
							<div class="panel-body">
								<h5>Dominic Russo</h5>
								<ul class="social-network social-circle">
									<li><a href="https://instagram.com/dominicjrusso" title="Instagram"><i class="fa fa-instagram"></i></a></li>
			                        <li><a href="https://www.facebook.com/Dominic-Russo-129305547108336/?fref=ts" title="Facebook"><i class="fa fa-facebook"></i></a></li>
			                        <li><a href="https://twitter.com/dominicrusso" title="Twitter"><i class="fa fa-twitter"></i></a></li>
			                    </ul>
							</div><!-- end panel-body -->
						</div><!-- end panel -->
					</div><!-- end col -->
					<div class="col-xs-6 col-sm-4 text-center">
						<div class="panel panel-default">
							<img class="img-responsive" src="images/speakers/jed.jpg" alt="Jedidiah Thurner">
							<div class="panel-body">
								<h5>Jedidiah Thurner</h5>
								<ul class="social-network social-circle">
									<li><a href="https://instagram.com/jedidiahthurner" title="Instagram"><i class="fa fa-instagram"></i></a></li>
			                        <li><a href="https://www.facebook.com/jedidiahthurner" title="Facebook"><i class="fa fa-facebook"></i></a></li>
			                        <li><a href="https://twitter.com/jedidiahthurner" title="Twitter"><i class="fa fa-twitter"></i></a></li>
			                    </ul>
							</div><!-- end panel-body -->
						</div><!-- end panel -->
					</div><!-- end col -->
					<div class="col-xs-6 col-sm-4 text-center">
						<div class="panel panel-default">
							<img class="img-responsive" src="images/speakers/gabe.jpg" alt="Gabe Bahlhorn">
							<div class="panel-body">
								<h5>Gabe Bahlhorn</h5>
								<ul class="social-network social-circle">
									<li><a href="https://instagram.com/bahlhorn" title="Instagram"><i class="fa fa-instagram"></i></a></li>
			                        <li><a href="https://www.facebook.com/gabebahlhorn?fref=ts" title="Facebook"><i class="fa fa-facebook"></i></a></li>
			                        <li><a href="https://twitter.com/bahlhorn" title="Twitter"><i class="fa fa-twitter"></i></a></li>
			                    </ul>
							</div><!-- end panel-body -->
						</div><!-- end panel -->
					</div><!-- end col -->
				</div><!-- end row -->
				<p class="text-center"><a class="btn btn-primary btn-lg" role="button" data-toggle="collapse" href="#collapseContact" aria-expanded="false" aria-controls="collapseContact">Request A Speaker</a></p>
				<div class="collapse" id="collapseContact">
					<hr class="divider inv xlg">
					<validator name="CreateGroup">
		              <form class="form-horizontal" novalidate>
		                  <div class="form-group">
		                      <div class="col-sm-6">
		                          <label for="name">Name</label>
		                          <input type="text" class="form-control" name="name" id="name" v-model="name"
		                                 placeholder="John Smith" v-validate:name="{ required: true, minlength:1, maxlength:100 }"
		                                 maxlength="100" minlength="1" required>
		                      </div>
		                      <div class="col-sm-6">
		                          <label for="name">Your Church/Organization</label>
		                          <input type="text" class="form-control" name="name" id="name" v-model="name"
		                                 placeholder="Church Name" v-validate:name="{ required: true, minlength:1, maxlength:100 }"
		                                 maxlength="100" minlength="1" required>
		                      </div>
		                  </div>

						  <div class="form-group">
		                      <div class="col-sm-6">
		                          <label for="infoPhone">Phone 1</label>
		                          <input type="text" class="form-control" v-model="phone_one | phone" id="infoPhone" placeholder="123-456-7890">
		                      </div>
		                      <div class="col-sm-6">
		                          <label for="name">Email</label>
		                          <input type="text" class="form-control" name="email" id="email" v-model="email">
		                      </div>
		                  </div>

		                  <div class="form-group">
		                      <div class="col-sm-6">
		                          <label for="infoAddress">Address 1</label>
		                          <input type="text" class="form-control" v-model="address_one" id="infoAddress" placeholder="Street Address 1">
		                      </div>
		                      <div class="col-sm-6">
		                          <label for="infoAddress2">Address 2</label>
		                          <input type="text" class="form-control" v-model="address_two" id="infoAddress2" placeholder="Street Address 2">
		                      </div>
		                  </div>

		                  <div class="row form-group col-sm-offset-2">
		                      <div class="col-sm-4">
		                              <label for="infoCity">City</label>
		                              <input type="text" class="form-control" v-model="city" id="infoCity" placeholder="City">
		                      </div>
		                      <div class="col-sm-4">
		                              <label for="infoState">State/Prov.</label>
		                              <input type="text" class="form-control" v-model="state" id="infoState" placeholder="State/Province">
		                      </div>
		                      <div class="col-sm-4">
		                              <label for="infoZip">ZIP/Postal Code</label>
		                              <input type="text" class="form-control" v-model="zip" id="infoZip" placeholder="12345">
		                      </div>
		                  </div>

		                  <div class="form-group">
		                      <div class="col-sm-12">
		                      	<label for="name">Questions, Comments, or Ideas</label>
		                      	<textarea type="text" class="form-control" name="comments" id="comments" v-model="comments"></textarea>
		                      </div>
		                  </div>
		                  <div class="form-group">
		                      <div class="col-sm-12 text-center">
		                          <a @click="submit()" class="btn btn-primary">Send Request</a>
		                      </div>
		                  </div>
		              </form>
		          </validator>
				</div>
			</div><!-- end col -->
		</div><!-- end row -->
	</div>
	</div><!-- end container -->
</div><!-- end white-bg -->
@endsection