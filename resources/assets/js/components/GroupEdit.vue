<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5>Update Logo</h5>
                    </div>
                    <div class="panel-body">
                        <div class="row form-group">
                            <div class="col-sm-6">
                                <label>Profile Photo (max file size:2mb)</label>
                                <h5>
                                    <a href="#">
                                        <img class="av-left img-circle img-md" :src="avatar + '?w=64&h=64'" :alt="group.name" width="64">
                                    </a>
                                    <button class="btn btn-primary btn-xs" type="button" data-toggle="collapse" data-target="#avatarCollapse" aria-expanded="false" aria-controls="avatarCollapse"><i class="fa fa-camera"></i> Upload</button>
                                </h5>
                            </div>
                            <hr class="divider inv visible-xs">
                            <div class="col-sm-12">
                                <div class="collapse" id="avatarCollapse">
                                    <div class="well">
                                        <upload-create-update 
                                            type="avatar" 
                                            :name="group.id" 
                                            lock-type ui-locked 
                                            :ui-selector="2" 
                                            is-child 
                                            :tags="['Group']" 
                                            :is-update="group.avatar_upload_id ? true : false" 
                                            :upload-id="group.avatar_upload_id"
                                            @uploads-complete="uploadsComplete"></upload-create-update>
                                    </div>
                                </div>
                                <hr class="divider inv" />
                            </div>
                        </div><!-- end row -->
                    </div>
                </div>
                
                <ajax-form method="put" :action="`groups/${group.id}`" :initial="group" :horizontal="true" ref="ajax">
                    <template slot-scope="{ form }">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5>Edit Organization Details</h5>
                        </div>
                        <div class="panel-body">
                            <input-text name="name" v-model="form.name" :horizontal="true" classes="col-md-8">
                                <label slot="label" class="control-label col-md-4">Organization Name</label>
                            </input-text>
                            
                            <input-select 
                                name="type" 
                                v-model="form.type" 
                                :horizontal="true" 
                                classes="col-md-8" 
                                :options="{
                                    'church': 'Church', 
                                    'business': 'Business',
                                    'independent': 'Independent', 
                                    'nonprofit': 'Non-profit',
                                    'school': 'School',
                                    'youth': 'Youth',
                                    'other': 'Other'
                                }"
                            >
                                <label slot="label" class="control-label col-md-4">Organization Type</label>
                            </input-select>

                            <input-text name="email" v-model="form.email" :horizontal="true" classes="col-md-8">
                                <label slot="label" class="control-label col-md-4">Email</label>
                            </input-text>

                            <input-phone name="phone_one" v-model="form.phone_one" :horizontal="true" classes="col-md-8">
                                <label slot="label" class="control-label col-md-4">Primary Phone</label>
                            </input-phone>

                            <input-phone name="phone_two" v-model="form.phone_two" :horizontal="true" classes="col-md-8">
                                <label slot="label" class="control-label col-md-4">Secondary Phone</label>
                            </input-phone>

                            <input-text name="address_one" v-model="form.address_one" :horizontal="true" classes="col-md-8" placeholder="Address line 1">
                                <label slot="label" class="control-label col-md-4">Mailing Address</label>
                            </input-text>
                            <input-text name="address_two" v-model="form.address_two" :horizontal="true" classes="col-md-8" placeholder="Address line 2">
                                <label slot="label" class="control-label col-md-4"></label>
                            </input-text>

                            <input-text name="city" v-model="form.city" :horizontal="true" classes="col-md-8">
                                <label slot="label" class="control-label col-md-4">City</label>
                            </input-text>

                            <input-text name="state" v-model="form.state" :horizontal="true" classes="col-md-8">
                                <label slot="label" class="control-label col-md-4">State/Providence</label>
                            </input-text>

                            <input-text name="zip" v-model="form.zip" :horizontal="true" classes="col-md-8">
                                <label slot="label" class="control-label col-md-4">Zip/Postal</label>
                            </input-text>

                            <select-country name="country_code" v-model="form.country_code" :horizontal="true" classes="col-md-8">
                                <label slot="label" class="control-label col-md-4">Country</label>
                            </select-country>    

                            <select-timezone name="timezone" v-model="form.timezone" :horizontal="true" classes="col-md-8">
                                <label slot="label" class="control-label col-md-4">Timezone</label>
                            </select-timezone> 
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5>Update Profile Banner</h5>
                        </div>
                        <div class="panel-body">
                            <div class="row form-group">
                                <div class="col-sm-6">
                                    <label>Cover Photo (max file size:2mb)</label>
                                    <h5>
                                        <a href="#">
                                            <img class="av-left img-rounded img-md"
                                                 :src="banner + '?w=64&h=64&fit=crop-center'"
                                                 :alt="group.name" width="64">
                                        </a>
                                        <button class="btn btn-primary btn-xs" type="button" data-toggle="collapse" data-target="#bannerCollapse" aria-expanded="false" aria-controls="bannerCollapse"><i class="fa fa-camera"></i> Cover</button>
                                    </h5>
                                </div><!-- end col -->
                                <div class="col-sm-12">
                                    <div class="collapse" id="bannerCollapse">
                                        <div class="well">
                                            <upload-create-update type="banner" :name="group.id" lock-type ui-locked :ui-selector="1" :per-page="6" is-child :tags="['Group']" @uploads-complete="uploadsComplete"></upload-create-update>
                                        </div>
                                    </div>
                                    <hr class="divider inv" />
                                </div>
                            </div><!-- end row -->
                        </div>
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5>Update Public Profile</h5>
                        </div>
                        <div class="panel-body">    
                
                            <input-radio-group 
                                name="public" 
                                v-model="form.public" 
                                :horizontal="true" 
                                classes="col-md-8" 
                                :options="[{value: true, label: 'Public'}, {value: false, label: 'Private'}]">
                                <label slot="label" class="control-label col-md-4">Visibility</label>
                            </input-radio-group>

                            <input-textarea name="description" v-model="form.description" :horizontal="true" classes="col-md-8">
                                <label slot="label" class="control-label col-md-4">Profile Description</label>
                            </input-textarea>

                            <input-text name="url" v-model="form.url" :horizontal="true" classes="col-md-8">
                                <span slot="prefix" class="input-group-addon">missions.me/</span>
                                <label slot="label" class="control-label col-md-4">Profile Link</label>
                            </input-text>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 text-right">
                            <a :href="`/admin/organizations/${group.id}`" class="btn btn-link">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                    </template>
                </ajax-form>

            </div>
        </div>
    </div>
</template>

<script>
export default {

  name: 'GroupEdit',

  props: {
    group: {
        type: Object,
        required: true
    }
  },

  data() {
    return {
        avatar: this.group.avatar_url,
        banner: this.group.banner_url,
        selectedAvatar: null,
        avatar_upload_id: null,
        selectedBanner: null,
        banner_upload_id: null
    }
  },

  methods: {
    uploadsComplete(data){
        switch(data.type){
            case 'avatar':
                this.selectedAvatar = data;
                this.avatar_upload_id = data.id;
                this.$refs.ajax.form.avatar_upload_id = data.id;
                this.$forceUpdate()
                $('#avatarCollapse').collapse('hide');
                break;
            case 'banner':
                this.selectedBanner = data;
                this.banner_upload_id = data.id;
                this.$refs.ajax.form.banner_upload_id = data.id;
                this.$forceUpdate()
                $('#bannerCollapse').collapse('hide');
                break;
        }
        this.$refs.ajax.onSubmit();
    }
  }
}
</script>

<style lang="css" scoped>
</style>