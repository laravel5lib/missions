<template>
    <div class="panel panel-default" style="border-top: 5px solid #f6323e">
        <div class="panel-heading">
            <ul class="nav nav-pills nav-justified">
                <li :class="{ 'active' : activeTab === 'squad-member-list'}">
                    <a role="button" @click="activeTab = 'squad-member-list'">
                        Members <span class="badge">{{ counts.members }}</span> 
                    </a>
                </li>
                <li :class="{ 'active' : activeTab === 'squad-list'}">
                    <a role="button" @click="activeTab = 'squad-list'">
                        Squads <span class="badge">{{ counts.squads }}</span> 
                    </a>
                </li>
                <li :class="{ 'active' : activeTab === 'region-list'}">
                    <a role="button" @click="activeTab = 'region-list'">
                        Regions <span class="badge">{{ counts.regions }}</span> 
                    </a>
                </li>
                <li :class="{ 'active' : activeTab === 'needs-squad-list'}">
                    <a role="button" @click="activeTab = 'needs-squad-list'">
                        Unassigned <span class="badge">{{ counts.unassigned }}</span> 
                    </a>
                </li>
            </ul>
        </div>

        <component 
            :is="activeTab" 
            :campaign-id="campaignId" 
            @update:membersTotal="updateMembersCount"
            @update:unassignedTotal="updateUnassignedCount"
            @tab:change="changeTab"
            ref="component"
        ></component>

    </div>
</template>

<script>
import state from '../state.mixin';
import SquadList from './SquadList';
import RegionList from './RegionList';
import SquadMemberList from './SquadMemberList';
import NeedsSquadList from './NeedsSquadList';
export default {
    props: {
        campaignId: {
            type: String,
            required: true
        },
        totals: Object
    },

    components: {
        'squad-list': SquadList,
        'region-list': RegionList,
        'squad-member-list': SquadMemberList,
        'needs-squad-list': NeedsSquadList
    },

    mixins: [state],

    data() {
        return {
            activeTab: 'squad-member-list',
            counts: {
                members: this.totals ? this.totals.members : 0,
                squads: this.totals ? this.totals.squads : 0,
                regions: this.totals ? this.totals.regions : 0,
                unassigned: this.totals ? this.totals.unassigned : 0
            }
        }
    },

    watch: {
        activeTab() {
            this.saveState(['activeTab']);
        }
    },

    methods: {
        updateMembersCount(total) {
            this.counts.members = this.counts.members + total;
        },
        updateUnassignedCount(total) {
            this.counts.unassigned = this.counts.unassigned + total;
        },
        changeTab(tab) {
            this.activeTab = tab;
        }
    },

    mounted() {
        var previousState = this.restoreState();
        if (previousState) {
            this.activeTab = previousState.activeTab;
        }
    }
}
</script>

<style>
    tr.selected, tr:hover {
        background-color: #fcf8e3 !important;
    }
    th, td {
        white-space: nowrap;
    }
    .panel-heading {
        border-color: #e6e6e6;
    }
    table.table {
        margin-bottom: 0;
    }
</style>
