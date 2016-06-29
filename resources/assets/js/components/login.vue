<template>
      <div class="panel panel-default">
        <div class="panel-body">
          <form class="form-horizontal" role="form">
            <div id="alerts" v-if="messages.length > 0">
              <div v-for="message in messages" class="alert alert-{{ message.type }} alert-dismissible" role="alert">
                {{ message.message }}
              </div>
            </div><!-- end alert -->
            <div class="form-group">
              <div class="col-xs-10  col-xs-offset-1">
                <label class="control-label">E-Mail Address</label>
                <input type="email" class="form-control" v-model="user.email">
              </div><!-- end col -->
            </div><!-- end form-group -->
            <div class="form-group">
              <div class="col-xs-10  col-xs-offset-1">
                <label class="control-label">Password</label>
                <input type="password" class="form-control" v-model="user.password">
              </div><!-- end col -->
            </div><!-- end form-group -->
            <div class="form-group">
              <div class="col-xs-10  col-xs-offset-1">
                <button type="submit" class="btn btn-primary btn-block" v-on:click="attempt">
                  Login
                </button>
                <a class="btn btn-block btn-link" href="#">Forgot Your Password?</a>
              </div><!-- end col -->
            </div><!-- end form-group -->
          </form><!-- end form -->
        </div><!-- end panel-body -->
</template>

<script>
module.exports = {
  name: 'login',

  data: function () {
    return {
      user: {
        email: null,
        password: null,
        _token: $('meta[name="csrf-token"]').attr('content')
      },
      messages: [],

      // component vars
      isChildComponent: false,
      userData: null
    }
  },

  methods: {
    attempt: function (e) {
      e.preventDefault()
      var that = this
      that.$http.post('/login', this.user).then(
        function (response) {
          that.getUserData(response.data.redirect_to);
        },
        function (response) {
          that.messages = []
          if (response.status && response.status === 401) that.messages.push({
            type: 'danger', 
            message: 'Sorry, we couldn\'t find an account that matches the email and password you provided.'
          })

          if (response.status && response.status === 422) {
            that.messages = [{
              type: 'danger',
              message: 'Please enter a valid email and password.'
            }]
          }
        }
      )
    },

    getUserData: function (redirectTo) {
      var that = this;
      var deferred = $.Deferred();
      that.$http.get('/api/users/me').then(
        function (response) {
          that.$dispatch('userHasLoggedIn', response.data.data);

          if (that.isChildComponent) {
            that.userData = response.data.data;
            deferred.resolve(response.data.data);
          } else {
            location.href=redirectTo;
          }

        },
        function (response) {
          console.log(response);
          deferred.resolve(response.data.data);
        }
      )
      return deferred.promise();
    }
  },
  activate: function (done) {
    // Enable child component behavior
    if(this.$parent != this.$root) {
      this.isChildComponent = true;
    }
    done();
  },
  ready: function () {
    if(this.isChildComponent) {
      // Check if user is logged in
      this.getUserData(false).then(function (response) {
      });
    }
  }
}
</script>

