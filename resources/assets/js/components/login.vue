<template>
<div class="container">
  <div class="row">
    <hr class="divider inv lg">
    <div class="col-md-6 col-md-offset-3">
      <h5 class="text-uppercase text-center">Welcome Back To Missions.Me</h5>
      <hr class="divider inv">
      <div class="panel panel-default">
        <div class="panel-body">
          <validator name="validation1">
            <form class="form-horizontal" role="form" novalidate>
              <div class="form-group" v-bind:class="{ 'has-error': $validation1.email.invalid }">
                <div class="col-xs-10  col-xs-offset-1">
                  <label class="control-label">E-Mail Address</label>
                  <input type="email" 
                         class="form-control" 
                         v-model="user.email" 
                         v-validate:email="['required', 'validEmail']"
                         initial="off"
                         detect-change="off">
                  <span v-if="$validation1.email.invalid" class="help-block">A valid email is required.</span>
                </div><!-- end col -->
              </div><!-- end form-group -->
              <div class="form-group" v-bind:class="{ 'has-error': $validation1.password.invalid }">
                <div class="col-xs-10  col-xs-offset-1">
                  <label class="control-label">Password</label>
                  <input type="password" 
                         class="form-control" 
                         v-model="user.password" 
                         v-validate:password="['required']"
                         initial="off">
                  <span v-if="$validation1.password.required" class="help-block">A password is required.</span>
                </div><!-- end col -->
              </div><!-- end form-group -->
              <div class="form-group">
                <div class="col-xs-10  col-xs-offset-1">
                  <button type="submit" class="btn btn-primary btn-block" v-on:click="attempt">
                    Login
                  </button>
                  <a class="btn btn-block btn-link" v-link="{ path: '/forgot' }">Forgot Your Password?</a>
                </div><!-- end col -->
              </div><!-- end form-group -->
            </form><!-- end form -->
          </validator>
        </div><!-- end panel-body -->
      </div>
    </div><!-- end col -->
  </div><!-- end row -->
</div><!-- end container -->
</template>

<script>
import * as actions from 'src/vuex/actions'

export default {
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
  vuex: {
    actions
  },
  methods: {
    attempt: function (e) {
      e.preventDefault()
      var that = this

      that.$validate(true)

      if (that.$validation1.invalid) {
        return
      }

      that.$http.post('login', this.user).then(
        function (response) {
          console.log(response.data)
          that.$dispatch('userHasFetchedToken', response.data.token)
          that.getUserData()
          that.setMessages([{
            theme: 'success',
            closeBtn: false,
            message: 'You are now logged in!'
          }])
          that.$dispatch('sendMessages')
        },
        function (response) {
          that.messages = []
          if (response.status && response.status === 401) that.messages.push({theme: 'error', closeBtn: true, message: 'Sorry, we couldn\'t find an account that matches the email and password you provided.'})

          if (response.status && response.status === 422) {
            that.messages = []
            for (var key in response.data.errors) {
              that.messages.push({theme: 'error', closeBtn: true, message: response.data.errors[key]})
            }
          }
          that.setMessages(that.messages)
          that.$dispatch('sendMessages')
        }
      )
    },

    getUserData: function () {
      var that = this
      that.$http.get('users/me').then(
        function (response) {
          that.$dispatch('userHasLoggedIn', response.data.data)
          that.$route.router.go('/')
        },
        function (response) {
          console.log(response)
        }
      )
    }
  }
}
</script>
