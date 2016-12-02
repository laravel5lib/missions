@extends('site.layouts.default')

@section('content')
<div class="content-page-header">
    <img class="img-responsive" src="images/sponsor/sponsor-header.jpg" alt="Sponsor">
    <div class="c-page-header-text">
    	<h1 class="text-uppercase dash-trailing">Become A Sponsor</h1>
    </div><!-- end c-page-header-content -->
</div><!-- end c-page-header -->
<div class="white-bg">
	<div class="container">
	<div class="content-section">
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2">
				<hr class="divider inv">
					<validator name="CreateGroup">
		              	<form class="form-horizontal" novalidate>
		              		<div class="form-group">
		                      <div class="col-sm-6">
		                          <label for="name">Choose a cause to support</label>
		                          	<select class="form-control">
			                            <option>Select A Location</option>
			                        </select>
		                      </div>
		                      <div class="col-sm-6">
		                          <label for="name">Select a location</label>
		                          	<select class="form-control">
			                            <option>Select A Location</option>
			                        </select>
		                      </div>
		                  	</div>
		                  	<div class="form-group">
		                      <div class="col-sm-6">
		                          <label for="name">Select a type</label>
		                          	<select class="form-control">
			                            <option>Select A Project</option>
			                        </select>
		                      </div>
		                      <div class="col-sm-6">
								<label for="name">Desired Completion Date</label>
								<select class="form-control">
			                        <option>Select A Date</option>
			                    </select>
		                      </div>
		                  	</div>
		                  	<div class="form-group">
		                      <div class="col-sm-6">
		                            <label for="name">Project Description</label>
		                            <p>Lorem ipsum dolor sit amet, justo imperdiet, vel vel sed dolor in risus ac, lobortis ligula cras sed, diam felis phasellus. Venenatis turpis habitant ut. Tempus felis et duis blandit sit, est metus vestibulum.</p>
		                      </div>
		                      <div class="col-sm-6 text-center">
									<label for="name">Cost Starting At</label>
									<h1 class="text-success">$10,000.00</h1>
		                      </div>
		                  	</div>

						  	<div class="form-group">
		                      <div class="col-sm-6">
		                          <label for="name">Project Name</label>
		                          <input type="text" class="form-control" name="name" id="name" v-model="name"
		                                 placeholder="Annie's Water Well" v-validate:name="{ required: true, minlength:1, maxlength:100 }"
		                                 maxlength="100" minlength="1" required>
		                      </div>
		                      <div class="col-sm-6">
		                      	  <label for="name">Your Name</label>
		                          <input type="text" class="form-control" name="name" id="name" v-model="name"
		                                 placeholder="Annie Smith" v-validate:name="{ required: true, minlength:1, maxlength:100 }"
		                                 maxlength="100" minlength="1" required>
		                      </div>
		                  	</div>
		            
		                  <div class="row form-group">
		                      <div class="col-sm-6">
		                          <label for="name">Your Email</label>
		                          <input type="text" class="form-control" name="email" id="email" v-model="email">
		                      </div>
		                      <div class="col-sm-6">
		                          <label for="name">Your Phone</label>
		                      	  <input type="text" class="form-control" v-model="phone_one | phone" id="infoPhone" placeholder="123-456-7890">
		                      </div>
		                  </div>

		                  <div class="form-group">
		                      <div class="col-sm-12 text-center">
		                          <a @click="submit()" class="btn btn-primary">Send Request</a>
		                      </div>
		                  </div>
			            </form>
			        </validator>
			</div><!-- end col -->
		</div><!-- end row -->
	</div>
	</div><!-- end container -->
</div><!-- end white-bg -->
@endsection