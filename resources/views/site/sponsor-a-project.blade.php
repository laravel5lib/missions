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
				<h5 class="text-primary text-center text-uppercase">Choose A Cause To Support</h5>
				<hr class="divider inv lg">
					<validator name="CreateGroup">
		              	<form class="form-horizontal" novalidate>
		                  <div class="form-group">
		                      <div class="col-sm-6">
		                          <label for="name">Where do you want to sponsor a project?</label>
		                          	<select class="form-control">
			                            <option>Select A Location</option>
			                        </select>
		                      </div>
		                      <div class="col-sm-6">
		                          <label for="name">Which type of project do you want to start?</label>
		                          	<select class="form-control">
			                            <option>Select A Project</option>
			                        </select>
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
		                          <label for="name">Email</label>
		                          <input type="text" class="form-control" name="email" id="email" v-model="email">
		                      </div>
		                      <div class="col-sm-6">
		                          <label for="name">Phone</label>
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
		</div><!-- end row -->
	</div>
	</div><!-- end container -->
</div><!-- end white-bg -->
@endsection