<template>
<div class="container">
  <div class="row">
    <hr class="divider inv lg">
    <div class="col-md-6 col-md-offset-3">
      <h5 class="text-uppercase text-center">Welcome Back To Missions.Me</h5>
      <hr class="divider inv">
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
      </div>
    </div><!-- end col -->
  </div><!-- end row -->
</div><!-- end container --></template>

<script>
module.exports = {
  name: 'login',

  data: function () {
    return {
      user: {
        email: null,
        password: null
      },
      messages: []
    }
  },

  methods: {
    attempt: function (e) {
      e.preventDefault()
      var that = this
      that.$http.post('login', this.user).then(
        function (response) {
          // that.$dispatch('userHasFetchedToken', response.token)
          that.getUserData()
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

    getUserData: function () {
      var that = this
      that.$http.get('/users/me').then(
        function (response) {
          that.$dispatch('userHasLoggedIn', response.data.data)
          // that.$route.router.go('/dashboard/settings/account')
        },
        function (response) {
          console.log(response)
        }
      )
    }
  }
}
</script>

