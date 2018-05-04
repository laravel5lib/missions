<template>
<div>
<div class="panel panel-default">
    <div class="panel-heading">
        <h5>Add Group</h5>
    </div>
    <div class="panel-body">
        <ajax-form method="post" 
                   :action="'/campaigns/'+campaignId+'/groups'"
                   :horizontal="true"
                   :initial="defaultData"
                   @form:success="updateList">
            <template slot-scope="{ form }">

                <select-group name="group_id" 
                              v-model="form.group_id" 
                              placeholder="Search organizations by name"
                              classes="col-sm-9"
                              :horizontal="true">
                    <label slot="label" class="control-label col-sm-3">Organization</label>
                </select-group>

                <input-select name="status_id" 
                            :options="statusOptions" 
                            v-model="form.status_id"
                            classes="col-sm-9"
                            :horizontal="true">
                    <label slot="label" class="control-label col-sm-3">Status</label>
                </input-select>

                <div class="form-group">
                    <div class="col-sm-12 text-right">
                        <hr class="divider">
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </div>
            </template>
        </ajax-form>
    </div>
</div>

<fetch-json :url="'/campaigns/'+campaignId+'/groups'" ref="groupList">
    <div class="panel panel-default" style="border-top: 5px solid #f6323e" slot-scope="{ json:groups, loading, pagination, filters, addFilter }">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-6">
                    <h5>Participating Groups</h5>
                </div>
                <div class="col-xs-6 text-right">
                    
                </div>
            </div>
        </div>
        <div class="panel-body">
            <span class="label label-default" v-for="(filter, key) in filters" :key="key" style="padding: 0.5em; margin-right: 1em">
                {{ key | capitalize }}: "{{ filter }}"
                <a role="button" style="color: white; margin-left: 0.5em;" @click="removeFilter({name: 'Neil'})">
                    <i class="fa fa-times"></i>
                </a>
            </span>
            <div class="btn-group">
                <button type="button" class="btn btn-link btn-sm dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-plus"></i> Add a Filter
                </button>
                <ul class="dropdown-menu">
                    <li><a role="button" @click="addFilter('name')">Name</a></li>
                    <li><a role="button" @click="addFilter('status')">Status</a></li>
                </ul>
            </div>
        </div>
        <table class="table" v-if="groups && groups.length">
            <thead>
            <tr class="active">
                <th>#</th>
                <th>Name</th>
                <th>Trips</th>
                <th>Status</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="loading"><td>Loading...</td></tr>
            <tr v-for="(group, index) in groups" :key="group.id" v-else>
                <td>{{ index+1 }}</td>
                <td>
                    <strong><a :href="'/admin/groups/' + group.group_id">{{ group.name }}</a></strong>
                </td>
                <td class="col-sm-1 text-right">
                    <strong>0</strong>
                </td>
                <td>
                    {{ group.status }}
                </td>
            </tr>
            </tbody>
        </table>
        <div class="panel-body text-center" v-else>
            <span class="lead">No Groups</span>
            <p>Add a group to this campaign to get started.</p>
        </div>
        <div class="panel-footer" v-if="pagination.total > pagination.per_page">
            <pager :pagination="pagination"></pager>
        </div>
    </div>
</fetch-json>

<alert-error>
    <template slot="title">Oops!</template>
    <template slot="message">Please check the form for errors and try again.</template>
</alert-error>

<alert-success :timer="3000">
    <template slot="title">Nice Work!</template>
    <template slot="message">A group was added.</template>
</alert-success>

</div>
</template>
<script>
export default {
  props: ['campaignId'],

  data() {
      return {
          defaultData: {
              'status_id': '1',
              'group_id': null
          },
          statusOptions: {1:'Pending', 2:'Committed', 3:'Ready to Launch', 4:'Launched'}
      }
  },

  methods: {
      updateList() {
          console.log('form success!');
          this.$refs.groupList.fetch();
      }
  }
}
</script>
