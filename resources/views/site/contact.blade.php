@extends('site.layouts.default')

@section('content')
<div class="content-page-header">
    <img class="img-responsive" src="images/contact/contact-header.jpg" alt="Contact Us">
    <div class="c-page-header-text">
    	<h1 class="text-uppercase dash-trailing">Contact Us</h1>
    </div><!-- end c-page-header-content -->
</div><!-- end c-page-header -->
<div class="white-bg">
	<div class="container">
	<div class="content-section">
		<div class="row">
			<div class="col-sm-4 text-center">
				<div class="panel panel-default">
					<div class="panel-body">
						<h5>Mailing Address</h5>
						<p>145 S. Livernois Rd.<br>
							Suite 308<br>
							Rochester Hills, MI 48307</p>
						<hr class="divider lg">
						<h5>Physical Address</h5>
						<p>612 W. University Dr.<br>
							Rochester, MI 48307</p>
						<a href="https://goo.gl/maps/5wYwywWEwZK2" class="btn btn-primary btn-sm">Get Directions</a>
						<hr class="divider lg">
						<h5>Phone</h5>
						<p>(877) 369-4532</p>
						<h5>Fax</h5>
						<p>(248) 247-1295</p>
						<hr class="divider lg">
						<h5>Email</h5>
						<p class="small">Email us at <a href="mailto:go@missions.me" target="_blank">go@missions.me</a> or fill out the form to have a Missions.Me representative contact you! Please allow 1-2 business days for phone or email contact.</p>
					</div><!-- end panel-body -->
				</div><!-- end panel -->
			</div><!-- end col -->
			<div class="col-sm-8">
				<div class="col-sm-10 col-sm-offset-1">
					<p class="text-center">Day or night, Missions.Me is ready for you. Whether it be a question, comment, concern, or encouraging word, please do not hesitate to email or call! You can contact us by using the form below, or by any of these other means:</p>
					<hr class="divider inv">
					<div>
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
			                      <div class="col-sm-12">
			                      	<label for="name">Questions, Comments, or Ideas</label>
			                      	<textarea type="text" class="form-control" name="comments" id="comments" v-model="comments"></textarea>
			                      </div>
			                  </div>
			                  <div class="form-group">
			                      <div class="col-sm-12 text-center">
			                          <a @click="submit()" class="btn btn-primary">Submit</a>
			                      </div>
			                  </div>
			              </form>
			          </validator>
					</div>
				</div>
			</div><!-- end col -->
		</div><!-- end row -->
	</div>
	</div><!-- end container -->
</div><!-- end white-bg -->
@endsection