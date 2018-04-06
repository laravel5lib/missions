<template>
    <div>
        <i class="fa fa-clock-o"></i> {{ clock }}
    </div>
</template>

<script>
export default {

  props: {
    date: {
        type: String,
        required: true
    }
  },

  computed: {
    clock: function () {
        return this.now
                   .countdown(this.date, countdown.DAYS|countdown.HOURS|countdown.MINUTES|countdown.SECONDS)
                   .toString();
    }
  },

  data () {
    return {
        now: moment()
    };
  },

  mounted: function() {
        var self = this;
        window.setInterval(function () {
            console.log('updating ticker')
            self.$data.now = moment()
        }, 1000);
    }
};
</script>

<style lang="css" scoped>
</style>