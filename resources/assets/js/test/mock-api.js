/**
 * Created by jerezb on 2017-02-24.
 */
let Settings = {
    delay: 0
};

export default {

    // Get api token
    ['POST */login'] (pathMatch, query, request) {
        // before respond, you can check the path and query parameters with `pathMatch` & `query`
        // powered by 'url-pattern' & 'qs'
        // https://www.npmjs.com/package/url-pattern
        // https://www.npmjs.com/package/qs
        let body = {
            // data: {
                api_token: 'test',
                redirect_to: null,
                ignore_redirect: true

            // }
        };
        return {
            body: body,
            status: 200,
            statusText: 'OK',
            headers: {/*headers*/},
            delay: Settings.delay, // millisecond
        }
    },

    // basic mock
    ['GET *users/me'] (pathMatch, query, request) {
        let obj = {
            zip: "90344",
            country_code: "la",
            email: "admin@admin.com",
            alt_email: null,
            created_at: "2017-02-20 22:00:08",
            banner: "https://missions.dev/api/images/banners/gen-ban-2-2560x800.jpg",
            birthday: "1978-12-30",
            avatar: "https://missions.dev/api/images/avatars/112d15e5-c447-4c9e-bf25-b4cdb450c6a2_1487658721.jpg",
            bio: "test",
            country_name: "Lao, People's Democratic Republic",
            city: "Port Loraine",
            phone_one: null,
            id: "112d15e5-c447-4c9e-bf25-b4cdb450c6a2",
            gender: "Male",
            public: false,
            state: "Idaho",
            phone_two: null,
            name: "Administrator",
            links: [
                {
                    rel: "self",
                    uri: "/users/112d15e5-c447-4c9e-bf25-b4cdb450c6a2"
                }
            ],
            password: "$2y$10$qBd9LjcDtM4MZlAvgIR9H.43NiD0OPPVpvSiE/YaOLqqBvk2kCSpO",
            status: "Married",
            street: "7872 Lang Wall",
            timezone: "Asia/Kolkata",
            updated_at: "2017-02-24 01:11:05",
            url: "administrator",
        };

        if (query.hasOwnProperty('include')) {
            let params = query.include.split(',');
            if (params.includes('abilities')) {
                obj.abilities = {
                    "data": [{
                        "id": 1,
                        "display_name": "Access Dashboard",
                        "name": "access-dashboard",
                        "slug": "access-dashboard",
                        "entity_id": null,
                        "entity_type": null
                    }, {
                        "id": 2,
                        "display_name": "Access Admin",
                        "name": "access-admin",
                        "slug": "access-admin",
                        "entity_id": null,
                        "entity_type": null
                    }, {
                        "id": 3,
                        "display_name": "View Users",
                        "name": "view",
                        "slug": "view-users",
                        "entity_id": null,
                        "entity_type": "users"
                    }, {
                        "id": 4,
                        "display_name": "Create Users",
                        "name": "create",
                        "slug": "create-users",
                        "entity_id": null,
                        "entity_type": "users"
                    }, {
                        "id": 5,
                        "display_name": "Edit Users",
                        "name": "edit",
                        "slug": "edit-users",
                        "entity_id": null,
                        "entity_type": "users"
                    }, {
                        "id": 6,
                        "display_name": "Delete Users",
                        "name": "delete",
                        "slug": "delete-users",
                        "entity_id": null,
                        "entity_type": "users"
                    }, {
                        "id": 7,
                        "display_name": "View Groups",
                        "name": "view",
                        "slug": "view-groups",
                        "entity_id": null,
                        "entity_type": "groups"
                    }, {
                        "id": 8,
                        "display_name": "Create Groups",
                        "name": "create",
                        "slug": "create-groups",
                        "entity_id": null,
                        "entity_type": "groups"
                    }, {
                        "id": 9,
                        "display_name": "Edit Groups",
                        "name": "edit",
                        "slug": "edit-groups",
                        "entity_id": null,
                        "entity_type": "groups"
                    }, {
                        "id": 10,
                        "display_name": "Delete Groups",
                        "name": "delete",
                        "slug": "delete-groups",
                        "entity_id": null,
                        "entity_type": "groups"
                    }, {
                        "id": 11,
                        "display_name": "View Campaigns",
                        "name": "view",
                        "slug": "view-campaigns",
                        "entity_id": null,
                        "entity_type": "campaigns"
                    }, {
                        "id": 12,
                        "display_name": "Create Campaigns",
                        "name": "create",
                        "slug": "create-campaigns",
                        "entity_id": null,
                        "entity_type": "campaigns"
                    }, {
                        "id": 13,
                        "display_name": "Edit Campaigns",
                        "name": "edit",
                        "slug": "edit-campaigns",
                        "entity_id": null,
                        "entity_type": "campaigns"
                    }, {
                        "id": 14,
                        "display_name": "Delete Campaigns",
                        "name": "delete",
                        "slug": "delete-campaigns",
                        "entity_id": null,
                        "entity_type": "campaigns"
                    }, {
                        "id": 15,
                        "display_name": "View Trips",
                        "name": "view",
                        "slug": "view-trips",
                        "entity_id": null,
                        "entity_type": "trips"
                    }, {
                        "id": 16,
                        "display_name": "Create Trips",
                        "name": "create",
                        "slug": "create-trips",
                        "entity_id": null,
                        "entity_type": "trips"
                    }, {
                        "id": 17,
                        "display_name": "Edit Trips",
                        "name": "edit",
                        "slug": "edit-trips",
                        "entity_id": null,
                        "entity_type": "trips"
                    }, {
                        "id": 18,
                        "display_name": "Delete Trips",
                        "name": "delete",
                        "slug": "delete-trips",
                        "entity_id": null,
                        "entity_type": "trips"
                    }, {
                        "id": 19,
                        "display_name": "View Reservations",
                        "name": "view",
                        "slug": "view-reservations",
                        "entity_id": null,
                        "entity_type": "reservations"
                    }, {
                        "id": 20,
                        "display_name": "Create Reservations",
                        "name": "create",
                        "slug": "create-reservations",
                        "entity_id": null,
                        "entity_type": "reservations"
                    }, {
                        "id": 21,
                        "display_name": "Edit Reservations",
                        "name": "edit",
                        "slug": "edit-reservations",
                        "entity_id": null,
                        "entity_type": "reservations"
                    }, {
                        "id": 22,
                        "display_name": "Delete Reservations",
                        "name": "delete",
                        "slug": "delete-reservations",
                        "entity_id": null,
                        "entity_type": "reservations"
                    }, {
                        "id": 23,
                        "display_name": "Manage Costs Reservations",
                        "name": "manage costs",
                        "slug": "manage costs-reservations",
                        "entity_id": null,
                        "entity_type": "reservations"
                    }, {
                        "id": 24,
                        "display_name": "Manage Payments Reservations",
                        "name": "manage payments",
                        "slug": "manage payments-reservations",
                        "entity_id": null,
                        "entity_type": "reservations"
                    }, {
                        "id": 25,
                        "display_name": "Manage Requirements Reservations",
                        "name": "manage requirements",
                        "slug": "manage requirements-reservations",
                        "entity_id": null,
                        "entity_type": "reservations"
                    }, {
                        "id": 26,
                        "display_name": "View Projects",
                        "name": "view",
                        "slug": "view-projects",
                        "entity_id": null,
                        "entity_type": "projects"
                    }, {
                        "id": 27,
                        "display_name": "Create Projects",
                        "name": "create",
                        "slug": "create-projects",
                        "entity_id": null,
                        "entity_type": "projects"
                    }, {
                        "id": 28,
                        "display_name": "Edit Projects",
                        "name": "edit",
                        "slug": "edit-projects",
                        "entity_id": null,
                        "entity_type": "projects"
                    }, {
                        "id": 29,
                        "display_name": "Delete Projects",
                        "name": "delete",
                        "slug": "delete-projects",
                        "entity_id": null,
                        "entity_type": "projects"
                    }, {
                        "id": 30,
                        "display_name": "View App\\models\\v1\\cost",
                        "name": "view",
                        "slug": "view-app\\models\\v1\\cost",
                        "entity_id": null,
                        "entity_type": "App\\Models\\v1\\Cost"
                    }, {
                        "id": 31,
                        "display_name": "Create App\\models\\v1\\cost",
                        "name": "create",
                        "slug": "create-app\\models\\v1\\cost",
                        "entity_id": null,
                        "entity_type": "App\\Models\\v1\\Cost"
                    }, {
                        "id": 32,
                        "display_name": "Edit App\\models\\v1\\cost",
                        "name": "edit",
                        "slug": "edit-app\\models\\v1\\cost",
                        "entity_id": null,
                        "entity_type": "App\\Models\\v1\\Cost"
                    }, {
                        "id": 33,
                        "display_name": "Delete App\\models\\v1\\cost",
                        "name": "delete",
                        "slug": "delete-app\\models\\v1\\cost",
                        "entity_id": null,
                        "entity_type": "App\\Models\\v1\\Cost"
                    }, {
                        "id": 34,
                        "display_name": "View Project_causes",
                        "name": "view",
                        "slug": "view-project_causes",
                        "entity_id": null,
                        "entity_type": "project_causes"
                    }, {
                        "id": 35,
                        "display_name": "Create Project_causes",
                        "name": "create",
                        "slug": "create-project_causes",
                        "entity_id": null,
                        "entity_type": "project_causes"
                    }, {
                        "id": 36,
                        "display_name": "Edit Project_causes",
                        "name": "edit",
                        "slug": "edit-project_causes",
                        "entity_id": null,
                        "entity_type": "project_causes"
                    }, {
                        "id": 37,
                        "display_name": "Delete Project_causes",
                        "name": "delete",
                        "slug": "delete-project_causes",
                        "entity_id": null,
                        "entity_type": "project_causes"
                    }, {
                        "id": 38,
                        "display_name": "View Transactions",
                        "name": "view",
                        "slug": "view-transactions",
                        "entity_id": null,
                        "entity_type": "transactions"
                    }, {
                        "id": 39,
                        "display_name": "Create Transactions",
                        "name": "create",
                        "slug": "create-transactions",
                        "entity_id": null,
                        "entity_type": "transactions"
                    }, {
                        "id": 40,
                        "display_name": "Edit Transactions",
                        "name": "edit",
                        "slug": "edit-transactions",
                        "entity_id": null,
                        "entity_type": "transactions"
                    }, {
                        "id": 41,
                        "display_name": "Delete Transactions",
                        "name": "delete",
                        "slug": "delete-transactions",
                        "entity_id": null,
                        "entity_type": "transactions"
                    }, {
                        "id": 42,
                        "display_name": "View Funds",
                        "name": "view",
                        "slug": "view-funds",
                        "entity_id": null,
                        "entity_type": "funds"
                    }, {
                        "id": 43,
                        "display_name": "Create Funds",
                        "name": "create",
                        "slug": "create-funds",
                        "entity_id": null,
                        "entity_type": "funds"
                    }, {
                        "id": 44,
                        "display_name": "Edit Funds",
                        "name": "edit",
                        "slug": "edit-funds",
                        "entity_id": null,
                        "entity_type": "funds"
                    }, {
                        "id": 45,
                        "display_name": "View Donors",
                        "name": "view",
                        "slug": "view-donors",
                        "entity_id": null,
                        "entity_type": "donors"
                    }, {
                        "id": 46,
                        "display_name": "Create Donors",
                        "name": "create",
                        "slug": "create-donors",
                        "entity_id": null,
                        "entity_type": "donors"
                    }, {
                        "id": 47,
                        "display_name": "Edit Donors",
                        "name": "edit",
                        "slug": "edit-donors",
                        "entity_id": null,
                        "entity_type": "donors"
                    }, {
                        "id": 48,
                        "display_name": "Delete Donors",
                        "name": "delete",
                        "slug": "delete-donors",
                        "entity_id": null,
                        "entity_type": "donors"
                    }, {
                        "id": 49,
                        "display_name": "Modify Todos",
                        "name": "modify",
                        "slug": "modify-todos",
                        "entity_id": null,
                        "entity_type": "todos"
                    }, {
                        "id": 50,
                        "display_name": "Modify Notes",
                        "name": "modify",
                        "slug": "modify-notes",
                        "entity_id": null,
                        "entity_type": "notes"
                    }, {
                        "id": 51,
                        "display_name": "View Uploads",
                        "name": "view",
                        "slug": "view-uploads",
                        "entity_id": null,
                        "entity_type": "uploads"
                    }, {
                        "id": 52,
                        "display_name": "Create Uploads",
                        "name": "create",
                        "slug": "create-uploads",
                        "entity_id": null,
                        "entity_type": "uploads"
                    }, {
                        "id": 53,
                        "display_name": "Edit Uploads",
                        "name": "edit",
                        "slug": "edit-uploads",
                        "entity_id": null,
                        "entity_type": "uploads"
                    }, {
                        "id": 54,
                        "display_name": "Delete Uploads",
                        "name": "delete",
                        "slug": "delete-uploads",
                        "entity_id": null,
                        "entity_type": "uploads"
                    }]
                }
            }
            if (params.includes('abilities')) {
                obj.roles = {
                    "data": [{
                        "id": 1,
                        "name": "member",
                        "abilities": {
                            "data": [{
                                "id": 1,
                                "display_name": "Access Dashboard",
                                "name": "access-dashboard",
                                "slug": "access-dashboard",
                                "entity_id": null,
                                "entity_type": null
                            }]
                        }
                    }, {
                        "id": 3,
                        "name": "admin",
                        "abilities": {
                            "data": [{
                                "id": 1,
                                "display_name": "Access Dashboard",
                                "name": "access-dashboard",
                                "slug": "access-dashboard",
                                "entity_id": null,
                                "entity_type": null
                            }, {
                                "id": 2,
                                "display_name": "Access Admin",
                                "name": "access-admin",
                                "slug": "access-admin",
                                "entity_id": null,
                                "entity_type": null
                            }, {
                                "id": 3,
                                "display_name": "View Users",
                                "name": "view",
                                "slug": "view-users",
                                "entity_id": null,
                                "entity_type": "users"
                            }, {
                                "id": 4,
                                "display_name": "Create Users",
                                "name": "create",
                                "slug": "create-users",
                                "entity_id": null,
                                "entity_type": "users"
                            }, {
                                "id": 5,
                                "display_name": "Edit Users",
                                "name": "edit",
                                "slug": "edit-users",
                                "entity_id": null,
                                "entity_type": "users"
                            }, {
                                "id": 6,
                                "display_name": "Delete Users",
                                "name": "delete",
                                "slug": "delete-users",
                                "entity_id": null,
                                "entity_type": "users"
                            }, {
                                "id": 7,
                                "display_name": "View Groups",
                                "name": "view",
                                "slug": "view-groups",
                                "entity_id": null,
                                "entity_type": "groups"
                            }, {
                                "id": 8,
                                "display_name": "Create Groups",
                                "name": "create",
                                "slug": "create-groups",
                                "entity_id": null,
                                "entity_type": "groups"
                            }, {
                                "id": 9,
                                "display_name": "Edit Groups",
                                "name": "edit",
                                "slug": "edit-groups",
                                "entity_id": null,
                                "entity_type": "groups"
                            }, {
                                "id": 10,
                                "display_name": "Delete Groups",
                                "name": "delete",
                                "slug": "delete-groups",
                                "entity_id": null,
                                "entity_type": "groups"
                            }, {
                                "id": 11,
                                "display_name": "View Campaigns",
                                "name": "view",
                                "slug": "view-campaigns",
                                "entity_id": null,
                                "entity_type": "campaigns"
                            }, {
                                "id": 12,
                                "display_name": "Create Campaigns",
                                "name": "create",
                                "slug": "create-campaigns",
                                "entity_id": null,
                                "entity_type": "campaigns"
                            }, {
                                "id": 13,
                                "display_name": "Edit Campaigns",
                                "name": "edit",
                                "slug": "edit-campaigns",
                                "entity_id": null,
                                "entity_type": "campaigns"
                            }, {
                                "id": 14,
                                "display_name": "Delete Campaigns",
                                "name": "delete",
                                "slug": "delete-campaigns",
                                "entity_id": null,
                                "entity_type": "campaigns"
                            }, {
                                "id": 15,
                                "display_name": "View Trips",
                                "name": "view",
                                "slug": "view-trips",
                                "entity_id": null,
                                "entity_type": "trips"
                            }, {
                                "id": 16,
                                "display_name": "Create Trips",
                                "name": "create",
                                "slug": "create-trips",
                                "entity_id": null,
                                "entity_type": "trips"
                            }, {
                                "id": 17,
                                "display_name": "Edit Trips",
                                "name": "edit",
                                "slug": "edit-trips",
                                "entity_id": null,
                                "entity_type": "trips"
                            }, {
                                "id": 18,
                                "display_name": "Delete Trips",
                                "name": "delete",
                                "slug": "delete-trips",
                                "entity_id": null,
                                "entity_type": "trips"
                            }, {
                                "id": 19,
                                "display_name": "View Reservations",
                                "name": "view",
                                "slug": "view-reservations",
                                "entity_id": null,
                                "entity_type": "reservations"
                            }, {
                                "id": 20,
                                "display_name": "Create Reservations",
                                "name": "create",
                                "slug": "create-reservations",
                                "entity_id": null,
                                "entity_type": "reservations"
                            }, {
                                "id": 21,
                                "display_name": "Edit Reservations",
                                "name": "edit",
                                "slug": "edit-reservations",
                                "entity_id": null,
                                "entity_type": "reservations"
                            }, {
                                "id": 22,
                                "display_name": "Delete Reservations",
                                "name": "delete",
                                "slug": "delete-reservations",
                                "entity_id": null,
                                "entity_type": "reservations"
                            }, {
                                "id": 23,
                                "display_name": "Manage Costs Reservations",
                                "name": "manage costs",
                                "slug": "manage costs-reservations",
                                "entity_id": null,
                                "entity_type": "reservations"
                            }, {
                                "id": 24,
                                "display_name": "Manage Payments Reservations",
                                "name": "manage payments",
                                "slug": "manage payments-reservations",
                                "entity_id": null,
                                "entity_type": "reservations"
                            }, {
                                "id": 25,
                                "display_name": "Manage Requirements Reservations",
                                "name": "manage requirements",
                                "slug": "manage requirements-reservations",
                                "entity_id": null,
                                "entity_type": "reservations"
                            }, {
                                "id": 26,
                                "display_name": "View Projects",
                                "name": "view",
                                "slug": "view-projects",
                                "entity_id": null,
                                "entity_type": "projects"
                            }, {
                                "id": 27,
                                "display_name": "Create Projects",
                                "name": "create",
                                "slug": "create-projects",
                                "entity_id": null,
                                "entity_type": "projects"
                            }, {
                                "id": 28,
                                "display_name": "Edit Projects",
                                "name": "edit",
                                "slug": "edit-projects",
                                "entity_id": null,
                                "entity_type": "projects"
                            }, {
                                "id": 29,
                                "display_name": "Delete Projects",
                                "name": "delete",
                                "slug": "delete-projects",
                                "entity_id": null,
                                "entity_type": "projects"
                            }, {
                                "id": 30,
                                "display_name": "View App\\models\\v1\\cost",
                                "name": "view",
                                "slug": "view-app\\models\\v1\\cost",
                                "entity_id": null,
                                "entity_type": "App\\Models\\v1\\Cost"
                            }, {
                                "id": 31,
                                "display_name": "Create App\\models\\v1\\cost",
                                "name": "create",
                                "slug": "create-app\\models\\v1\\cost",
                                "entity_id": null,
                                "entity_type": "App\\Models\\v1\\Cost"
                            }, {
                                "id": 32,
                                "display_name": "Edit App\\models\\v1\\cost",
                                "name": "edit",
                                "slug": "edit-app\\models\\v1\\cost",
                                "entity_id": null,
                                "entity_type": "App\\Models\\v1\\Cost"
                            }, {
                                "id": 33,
                                "display_name": "Delete App\\models\\v1\\cost",
                                "name": "delete",
                                "slug": "delete-app\\models\\v1\\cost",
                                "entity_id": null,
                                "entity_type": "App\\Models\\v1\\Cost"
                            }, {
                                "id": 34,
                                "display_name": "View Project_causes",
                                "name": "view",
                                "slug": "view-project_causes",
                                "entity_id": null,
                                "entity_type": "project_causes"
                            }, {
                                "id": 35,
                                "display_name": "Create Project_causes",
                                "name": "create",
                                "slug": "create-project_causes",
                                "entity_id": null,
                                "entity_type": "project_causes"
                            }, {
                                "id": 36,
                                "display_name": "Edit Project_causes",
                                "name": "edit",
                                "slug": "edit-project_causes",
                                "entity_id": null,
                                "entity_type": "project_causes"
                            }, {
                                "id": 37,
                                "display_name": "Delete Project_causes",
                                "name": "delete",
                                "slug": "delete-project_causes",
                                "entity_id": null,
                                "entity_type": "project_causes"
                            }, {
                                "id": 38,
                                "display_name": "View Transactions",
                                "name": "view",
                                "slug": "view-transactions",
                                "entity_id": null,
                                "entity_type": "transactions"
                            }, {
                                "id": 39,
                                "display_name": "Create Transactions",
                                "name": "create",
                                "slug": "create-transactions",
                                "entity_id": null,
                                "entity_type": "transactions"
                            }, {
                                "id": 40,
                                "display_name": "Edit Transactions",
                                "name": "edit",
                                "slug": "edit-transactions",
                                "entity_id": null,
                                "entity_type": "transactions"
                            }, {
                                "id": 41,
                                "display_name": "Delete Transactions",
                                "name": "delete",
                                "slug": "delete-transactions",
                                "entity_id": null,
                                "entity_type": "transactions"
                            }, {
                                "id": 42,
                                "display_name": "View Funds",
                                "name": "view",
                                "slug": "view-funds",
                                "entity_id": null,
                                "entity_type": "funds"
                            }, {
                                "id": 43,
                                "display_name": "Create Funds",
                                "name": "create",
                                "slug": "create-funds",
                                "entity_id": null,
                                "entity_type": "funds"
                            }, {
                                "id": 44,
                                "display_name": "Edit Funds",
                                "name": "edit",
                                "slug": "edit-funds",
                                "entity_id": null,
                                "entity_type": "funds"
                            }, {
                                "id": 45,
                                "display_name": "View Donors",
                                "name": "view",
                                "slug": "view-donors",
                                "entity_id": null,
                                "entity_type": "donors"
                            }, {
                                "id": 46,
                                "display_name": "Create Donors",
                                "name": "create",
                                "slug": "create-donors",
                                "entity_id": null,
                                "entity_type": "donors"
                            }, {
                                "id": 47,
                                "display_name": "Edit Donors",
                                "name": "edit",
                                "slug": "edit-donors",
                                "entity_id": null,
                                "entity_type": "donors"
                            }, {
                                "id": 48,
                                "display_name": "Delete Donors",
                                "name": "delete",
                                "slug": "delete-donors",
                                "entity_id": null,
                                "entity_type": "donors"
                            }, {
                                "id": 49,
                                "display_name": "Modify Todos",
                                "name": "modify",
                                "slug": "modify-todos",
                                "entity_id": null,
                                "entity_type": "todos"
                            }, {
                                "id": 50,
                                "display_name": "Modify Notes",
                                "name": "modify",
                                "slug": "modify-notes",
                                "entity_id": null,
                                "entity_type": "notes"
                            }, {
                                "id": 51,
                                "display_name": "View Uploads",
                                "name": "view",
                                "slug": "view-uploads",
                                "entity_id": null,
                                "entity_type": "uploads"
                            }, {
                                "id": 52,
                                "display_name": "Create Uploads",
                                "name": "create",
                                "slug": "create-uploads",
                                "entity_id": null,
                                "entity_type": "uploads"
                            }, {
                                "id": 53,
                                "display_name": "Edit Uploads",
                                "name": "edit",
                                "slug": "edit-uploads",
                                "entity_id": null,
                                "entity_type": "uploads"
                            }, {
                                "id": 54,
                                "display_name": "Delete Uploads",
                                "name": "delete",
                                "slug": "delete-uploads",
                                "entity_id": null,
                                "entity_type": "uploads"
                            }]
                        }
                    }]
                }
            }
        }

        let body = {
            data: obj
        };

        return {
            body: body,
            status: 200,
            statusText: 'OK',
            headers: {/*headers*/},
            delay: Settings.delay, // millisecond
        }
    },

    // basic mock
    ['GET *users/:id?include=:include'] (pathMatch, query, request) {
        // before respond, you can check the path and query parameters with `pathMatch` & `query`
        // powered by 'url-pattern' & 'qs'
        // https://www.npmjs.com/package/url-pattern
        // https://www.npmjs.com/package/qs
        let body = {
            "data": {
                "id": "112d15e5-c447-4c9e-bf25-b4cdb450c6a2",
                "name": "Administrator",
                "email": "admin@admin.com",
                "password": "$2y$10$qBd9LjcDtM4MZlAvgIR9H.43NiD0OPPVpvSiE\/YaOLqqBvk2kCSpO",
                "alt_email": null,
                "gender": "Male",
                "status": "Married",
                "birthday": "1978-12-30",
                "phone_one": null,
                "phone_two": null,
                "street": "7872 Lang Wall",
                "city": "Port Loraine",
                "state": "Idaho",
                "zip": "90344",
                "country_code": "la",
                "country_name": "Lao, People's Democratic Republic",
                "timezone": "Asia\/Kolkata",
                "bio": "test",
                "url": "administrator",
                "avatar": "https:\/\/missions.dev\/api\/images\/avatars\/112d15e5-c447-4c9e-bf25-b4cdb450c6a2_1487658721.jpg",
                "banner": "https:\/\/missions.dev\/api\/images\/banners\/gen-ban-2-2560x800.jpg",
                "public": false,
                "created_at": "2017-02-20 22:00:08",
                "updated_at": "2017-02-24 12:53:22",
                "links": [{"rel": "self", "uri": "\/users\/112d15e5-c447-4c9e-bf25-b4cdb450c6a2"}],
                "roles": {
                    "data": [{
                        "id": 1,
                        "name": "member",
                        "abilities": {
                            "data": [{
                                "id": 1,
                                "display_name": "Access Dashboard",
                                "name": "access-dashboard",
                                "slug": "access-dashboard",
                                "entity_id": null,
                                "entity_type": null
                            }]
                        }
                    }, {
                        "id": 3,
                        "name": "admin",
                        "abilities": {
                            "data": [{
                                "id": 1,
                                "display_name": "Access Dashboard",
                                "name": "access-dashboard",
                                "slug": "access-dashboard",
                                "entity_id": null,
                                "entity_type": null
                            }, {
                                "id": 2,
                                "display_name": "Access Admin",
                                "name": "access-admin",
                                "slug": "access-admin",
                                "entity_id": null,
                                "entity_type": null
                            }, {
                                "id": 3,
                                "display_name": "View Users",
                                "name": "view",
                                "slug": "view-users",
                                "entity_id": null,
                                "entity_type": "users"
                            }, {
                                "id": 4,
                                "display_name": "Create Users",
                                "name": "create",
                                "slug": "create-users",
                                "entity_id": null,
                                "entity_type": "users"
                            }, {
                                "id": 5,
                                "display_name": "Edit Users",
                                "name": "edit",
                                "slug": "edit-users",
                                "entity_id": null,
                                "entity_type": "users"
                            }, {
                                "id": 6,
                                "display_name": "Delete Users",
                                "name": "delete",
                                "slug": "delete-users",
                                "entity_id": null,
                                "entity_type": "users"
                            }, {
                                "id": 7,
                                "display_name": "View Groups",
                                "name": "view",
                                "slug": "view-groups",
                                "entity_id": null,
                                "entity_type": "groups"
                            }, {
                                "id": 8,
                                "display_name": "Create Groups",
                                "name": "create",
                                "slug": "create-groups",
                                "entity_id": null,
                                "entity_type": "groups"
                            }, {
                                "id": 9,
                                "display_name": "Edit Groups",
                                "name": "edit",
                                "slug": "edit-groups",
                                "entity_id": null,
                                "entity_type": "groups"
                            }, {
                                "id": 10,
                                "display_name": "Delete Groups",
                                "name": "delete",
                                "slug": "delete-groups",
                                "entity_id": null,
                                "entity_type": "groups"
                            }, {
                                "id": 11,
                                "display_name": "View Campaigns",
                                "name": "view",
                                "slug": "view-campaigns",
                                "entity_id": null,
                                "entity_type": "campaigns"
                            }, {
                                "id": 12,
                                "display_name": "Create Campaigns",
                                "name": "create",
                                "slug": "create-campaigns",
                                "entity_id": null,
                                "entity_type": "campaigns"
                            }, {
                                "id": 13,
                                "display_name": "Edit Campaigns",
                                "name": "edit",
                                "slug": "edit-campaigns",
                                "entity_id": null,
                                "entity_type": "campaigns"
                            }, {
                                "id": 14,
                                "display_name": "Delete Campaigns",
                                "name": "delete",
                                "slug": "delete-campaigns",
                                "entity_id": null,
                                "entity_type": "campaigns"
                            }, {
                                "id": 15,
                                "display_name": "View Trips",
                                "name": "view",
                                "slug": "view-trips",
                                "entity_id": null,
                                "entity_type": "trips"
                            }, {
                                "id": 16,
                                "display_name": "Create Trips",
                                "name": "create",
                                "slug": "create-trips",
                                "entity_id": null,
                                "entity_type": "trips"
                            }, {
                                "id": 17,
                                "display_name": "Edit Trips",
                                "name": "edit",
                                "slug": "edit-trips",
                                "entity_id": null,
                                "entity_type": "trips"
                            }, {
                                "id": 18,
                                "display_name": "Delete Trips",
                                "name": "delete",
                                "slug": "delete-trips",
                                "entity_id": null,
                                "entity_type": "trips"
                            }, {
                                "id": 19,
                                "display_name": "View Reservations",
                                "name": "view",
                                "slug": "view-reservations",
                                "entity_id": null,
                                "entity_type": "reservations"
                            }, {
                                "id": 20,
                                "display_name": "Create Reservations",
                                "name": "create",
                                "slug": "create-reservations",
                                "entity_id": null,
                                "entity_type": "reservations"
                            }, {
                                "id": 21,
                                "display_name": "Edit Reservations",
                                "name": "edit",
                                "slug": "edit-reservations",
                                "entity_id": null,
                                "entity_type": "reservations"
                            }, {
                                "id": 22,
                                "display_name": "Delete Reservations",
                                "name": "delete",
                                "slug": "delete-reservations",
                                "entity_id": null,
                                "entity_type": "reservations"
                            }, {
                                "id": 23,
                                "display_name": "Manage Costs Reservations",
                                "name": "manage costs",
                                "slug": "manage costs-reservations",
                                "entity_id": null,
                                "entity_type": "reservations"
                            }, {
                                "id": 24,
                                "display_name": "Manage Payments Reservations",
                                "name": "manage payments",
                                "slug": "manage payments-reservations",
                                "entity_id": null,
                                "entity_type": "reservations"
                            }, {
                                "id": 25,
                                "display_name": "Manage Requirements Reservations",
                                "name": "manage requirements",
                                "slug": "manage requirements-reservations",
                                "entity_id": null,
                                "entity_type": "reservations"
                            }, {
                                "id": 26,
                                "display_name": "View Projects",
                                "name": "view",
                                "slug": "view-projects",
                                "entity_id": null,
                                "entity_type": "projects"
                            }, {
                                "id": 27,
                                "display_name": "Create Projects",
                                "name": "create",
                                "slug": "create-projects",
                                "entity_id": null,
                                "entity_type": "projects"
                            }, {
                                "id": 28,
                                "display_name": "Edit Projects",
                                "name": "edit",
                                "slug": "edit-projects",
                                "entity_id": null,
                                "entity_type": "projects"
                            }, {
                                "id": 29,
                                "display_name": "Delete Projects",
                                "name": "delete",
                                "slug": "delete-projects",
                                "entity_id": null,
                                "entity_type": "projects"
                            }, {
                                "id": 30,
                                "display_name": "View App\\models\\v1\\cost",
                                "name": "view",
                                "slug": "view-app\\models\\v1\\cost",
                                "entity_id": null,
                                "entity_type": "App\\Models\\v1\\Cost"
                            }, {
                                "id": 31,
                                "display_name": "Create App\\models\\v1\\cost",
                                "name": "create",
                                "slug": "create-app\\models\\v1\\cost",
                                "entity_id": null,
                                "entity_type": "App\\Models\\v1\\Cost"
                            }, {
                                "id": 32,
                                "display_name": "Edit App\\models\\v1\\cost",
                                "name": "edit",
                                "slug": "edit-app\\models\\v1\\cost",
                                "entity_id": null,
                                "entity_type": "App\\Models\\v1\\Cost"
                            }, {
                                "id": 33,
                                "display_name": "Delete App\\models\\v1\\cost",
                                "name": "delete",
                                "slug": "delete-app\\models\\v1\\cost",
                                "entity_id": null,
                                "entity_type": "App\\Models\\v1\\Cost"
                            }, {
                                "id": 34,
                                "display_name": "View Project_causes",
                                "name": "view",
                                "slug": "view-project_causes",
                                "entity_id": null,
                                "entity_type": "project_causes"
                            }, {
                                "id": 35,
                                "display_name": "Create Project_causes",
                                "name": "create",
                                "slug": "create-project_causes",
                                "entity_id": null,
                                "entity_type": "project_causes"
                            }, {
                                "id": 36,
                                "display_name": "Edit Project_causes",
                                "name": "edit",
                                "slug": "edit-project_causes",
                                "entity_id": null,
                                "entity_type": "project_causes"
                            }, {
                                "id": 37,
                                "display_name": "Delete Project_causes",
                                "name": "delete",
                                "slug": "delete-project_causes",
                                "entity_id": null,
                                "entity_type": "project_causes"
                            }, {
                                "id": 38,
                                "display_name": "View Transactions",
                                "name": "view",
                                "slug": "view-transactions",
                                "entity_id": null,
                                "entity_type": "transactions"
                            }, {
                                "id": 39,
                                "display_name": "Create Transactions",
                                "name": "create",
                                "slug": "create-transactions",
                                "entity_id": null,
                                "entity_type": "transactions"
                            }, {
                                "id": 40,
                                "display_name": "Edit Transactions",
                                "name": "edit",
                                "slug": "edit-transactions",
                                "entity_id": null,
                                "entity_type": "transactions"
                            }, {
                                "id": 41,
                                "display_name": "Delete Transactions",
                                "name": "delete",
                                "slug": "delete-transactions",
                                "entity_id": null,
                                "entity_type": "transactions"
                            }, {
                                "id": 42,
                                "display_name": "View Funds",
                                "name": "view",
                                "slug": "view-funds",
                                "entity_id": null,
                                "entity_type": "funds"
                            }, {
                                "id": 43,
                                "display_name": "Create Funds",
                                "name": "create",
                                "slug": "create-funds",
                                "entity_id": null,
                                "entity_type": "funds"
                            }, {
                                "id": 44,
                                "display_name": "Edit Funds",
                                "name": "edit",
                                "slug": "edit-funds",
                                "entity_id": null,
                                "entity_type": "funds"
                            }, {
                                "id": 45,
                                "display_name": "View Donors",
                                "name": "view",
                                "slug": "view-donors",
                                "entity_id": null,
                                "entity_type": "donors"
                            }, {
                                "id": 46,
                                "display_name": "Create Donors",
                                "name": "create",
                                "slug": "create-donors",
                                "entity_id": null,
                                "entity_type": "donors"
                            }, {
                                "id": 47,
                                "display_name": "Edit Donors",
                                "name": "edit",
                                "slug": "edit-donors",
                                "entity_id": null,
                                "entity_type": "donors"
                            }, {
                                "id": 48,
                                "display_name": "Delete Donors",
                                "name": "delete",
                                "slug": "delete-donors",
                                "entity_id": null,
                                "entity_type": "donors"
                            }, {
                                "id": 49,
                                "display_name": "Modify Todos",
                                "name": "modify",
                                "slug": "modify-todos",
                                "entity_id": null,
                                "entity_type": "todos"
                            }, {
                                "id": 50,
                                "display_name": "Modify Notes",
                                "name": "modify",
                                "slug": "modify-notes",
                                "entity_id": null,
                                "entity_type": "notes"
                            }, {
                                "id": 51,
                                "display_name": "View Uploads",
                                "name": "view",
                                "slug": "view-uploads",
                                "entity_id": null,
                                "entity_type": "uploads"
                            }, {
                                "id": 52,
                                "display_name": "Create Uploads",
                                "name": "create",
                                "slug": "create-uploads",
                                "entity_id": null,
                                "entity_type": "uploads"
                            }, {
                                "id": 53,
                                "display_name": "Edit Uploads",
                                "name": "edit",
                                "slug": "edit-uploads",
                                "entity_id": null,
                                "entity_type": "uploads"
                            }, {
                                "id": 54,
                                "display_name": "Delete Uploads",
                                "name": "delete",
                                "slug": "delete-uploads",
                                "entity_id": null,
                                "entity_type": "uploads"
                            }]
                        }
                    }]
                },
                "abilities": {
                    "data": [{
                        "id": 1,
                        "display_name": "Access Dashboard",
                        "name": "access-dashboard",
                        "slug": "access-dashboard",
                        "entity_id": null,
                        "entity_type": null
                    }, {
                        "id": 2,
                        "display_name": "Access Admin",
                        "name": "access-admin",
                        "slug": "access-admin",
                        "entity_id": null,
                        "entity_type": null
                    }, {
                        "id": 3,
                        "display_name": "View Users",
                        "name": "view",
                        "slug": "view-users",
                        "entity_id": null,
                        "entity_type": "users"
                    }, {
                        "id": 4,
                        "display_name": "Create Users",
                        "name": "create",
                        "slug": "create-users",
                        "entity_id": null,
                        "entity_type": "users"
                    }, {
                        "id": 5,
                        "display_name": "Edit Users",
                        "name": "edit",
                        "slug": "edit-users",
                        "entity_id": null,
                        "entity_type": "users"
                    }, {
                        "id": 6,
                        "display_name": "Delete Users",
                        "name": "delete",
                        "slug": "delete-users",
                        "entity_id": null,
                        "entity_type": "users"
                    }, {
                        "id": 7,
                        "display_name": "View Groups",
                        "name": "view",
                        "slug": "view-groups",
                        "entity_id": null,
                        "entity_type": "groups"
                    }, {
                        "id": 8,
                        "display_name": "Create Groups",
                        "name": "create",
                        "slug": "create-groups",
                        "entity_id": null,
                        "entity_type": "groups"
                    }, {
                        "id": 9,
                        "display_name": "Edit Groups",
                        "name": "edit",
                        "slug": "edit-groups",
                        "entity_id": null,
                        "entity_type": "groups"
                    }, {
                        "id": 10,
                        "display_name": "Delete Groups",
                        "name": "delete",
                        "slug": "delete-groups",
                        "entity_id": null,
                        "entity_type": "groups"
                    }, {
                        "id": 11,
                        "display_name": "View Campaigns",
                        "name": "view",
                        "slug": "view-campaigns",
                        "entity_id": null,
                        "entity_type": "campaigns"
                    }, {
                        "id": 12,
                        "display_name": "Create Campaigns",
                        "name": "create",
                        "slug": "create-campaigns",
                        "entity_id": null,
                        "entity_type": "campaigns"
                    }, {
                        "id": 13,
                        "display_name": "Edit Campaigns",
                        "name": "edit",
                        "slug": "edit-campaigns",
                        "entity_id": null,
                        "entity_type": "campaigns"
                    }, {
                        "id": 14,
                        "display_name": "Delete Campaigns",
                        "name": "delete",
                        "slug": "delete-campaigns",
                        "entity_id": null,
                        "entity_type": "campaigns"
                    }, {
                        "id": 15,
                        "display_name": "View Trips",
                        "name": "view",
                        "slug": "view-trips",
                        "entity_id": null,
                        "entity_type": "trips"
                    }, {
                        "id": 16,
                        "display_name": "Create Trips",
                        "name": "create",
                        "slug": "create-trips",
                        "entity_id": null,
                        "entity_type": "trips"
                    }, {
                        "id": 17,
                        "display_name": "Edit Trips",
                        "name": "edit",
                        "slug": "edit-trips",
                        "entity_id": null,
                        "entity_type": "trips"
                    }, {
                        "id": 18,
                        "display_name": "Delete Trips",
                        "name": "delete",
                        "slug": "delete-trips",
                        "entity_id": null,
                        "entity_type": "trips"
                    }, {
                        "id": 19,
                        "display_name": "View Reservations",
                        "name": "view",
                        "slug": "view-reservations",
                        "entity_id": null,
                        "entity_type": "reservations"
                    }, {
                        "id": 20,
                        "display_name": "Create Reservations",
                        "name": "create",
                        "slug": "create-reservations",
                        "entity_id": null,
                        "entity_type": "reservations"
                    }, {
                        "id": 21,
                        "display_name": "Edit Reservations",
                        "name": "edit",
                        "slug": "edit-reservations",
                        "entity_id": null,
                        "entity_type": "reservations"
                    }, {
                        "id": 22,
                        "display_name": "Delete Reservations",
                        "name": "delete",
                        "slug": "delete-reservations",
                        "entity_id": null,
                        "entity_type": "reservations"
                    }, {
                        "id": 23,
                        "display_name": "Manage Costs Reservations",
                        "name": "manage costs",
                        "slug": "manage costs-reservations",
                        "entity_id": null,
                        "entity_type": "reservations"
                    }, {
                        "id": 24,
                        "display_name": "Manage Payments Reservations",
                        "name": "manage payments",
                        "slug": "manage payments-reservations",
                        "entity_id": null,
                        "entity_type": "reservations"
                    }, {
                        "id": 25,
                        "display_name": "Manage Requirements Reservations",
                        "name": "manage requirements",
                        "slug": "manage requirements-reservations",
                        "entity_id": null,
                        "entity_type": "reservations"
                    }, {
                        "id": 26,
                        "display_name": "View Projects",
                        "name": "view",
                        "slug": "view-projects",
                        "entity_id": null,
                        "entity_type": "projects"
                    }, {
                        "id": 27,
                        "display_name": "Create Projects",
                        "name": "create",
                        "slug": "create-projects",
                        "entity_id": null,
                        "entity_type": "projects"
                    }, {
                        "id": 28,
                        "display_name": "Edit Projects",
                        "name": "edit",
                        "slug": "edit-projects",
                        "entity_id": null,
                        "entity_type": "projects"
                    }, {
                        "id": 29,
                        "display_name": "Delete Projects",
                        "name": "delete",
                        "slug": "delete-projects",
                        "entity_id": null,
                        "entity_type": "projects"
                    }, {
                        "id": 30,
                        "display_name": "View App\\models\\v1\\cost",
                        "name": "view",
                        "slug": "view-app\\models\\v1\\cost",
                        "entity_id": null,
                        "entity_type": "App\\Models\\v1\\Cost"
                    }, {
                        "id": 31,
                        "display_name": "Create App\\models\\v1\\cost",
                        "name": "create",
                        "slug": "create-app\\models\\v1\\cost",
                        "entity_id": null,
                        "entity_type": "App\\Models\\v1\\Cost"
                    }, {
                        "id": 32,
                        "display_name": "Edit App\\models\\v1\\cost",
                        "name": "edit",
                        "slug": "edit-app\\models\\v1\\cost",
                        "entity_id": null,
                        "entity_type": "App\\Models\\v1\\Cost"
                    }, {
                        "id": 33,
                        "display_name": "Delete App\\models\\v1\\cost",
                        "name": "delete",
                        "slug": "delete-app\\models\\v1\\cost",
                        "entity_id": null,
                        "entity_type": "App\\Models\\v1\\Cost"
                    }, {
                        "id": 34,
                        "display_name": "View Project_causes",
                        "name": "view",
                        "slug": "view-project_causes",
                        "entity_id": null,
                        "entity_type": "project_causes"
                    }, {
                        "id": 35,
                        "display_name": "Create Project_causes",
                        "name": "create",
                        "slug": "create-project_causes",
                        "entity_id": null,
                        "entity_type": "project_causes"
                    }, {
                        "id": 36,
                        "display_name": "Edit Project_causes",
                        "name": "edit",
                        "slug": "edit-project_causes",
                        "entity_id": null,
                        "entity_type": "project_causes"
                    }, {
                        "id": 37,
                        "display_name": "Delete Project_causes",
                        "name": "delete",
                        "slug": "delete-project_causes",
                        "entity_id": null,
                        "entity_type": "project_causes"
                    }, {
                        "id": 38,
                        "display_name": "View Transactions",
                        "name": "view",
                        "slug": "view-transactions",
                        "entity_id": null,
                        "entity_type": "transactions"
                    }, {
                        "id": 39,
                        "display_name": "Create Transactions",
                        "name": "create",
                        "slug": "create-transactions",
                        "entity_id": null,
                        "entity_type": "transactions"
                    }, {
                        "id": 40,
                        "display_name": "Edit Transactions",
                        "name": "edit",
                        "slug": "edit-transactions",
                        "entity_id": null,
                        "entity_type": "transactions"
                    }, {
                        "id": 41,
                        "display_name": "Delete Transactions",
                        "name": "delete",
                        "slug": "delete-transactions",
                        "entity_id": null,
                        "entity_type": "transactions"
                    }, {
                        "id": 42,
                        "display_name": "View Funds",
                        "name": "view",
                        "slug": "view-funds",
                        "entity_id": null,
                        "entity_type": "funds"
                    }, {
                        "id": 43,
                        "display_name": "Create Funds",
                        "name": "create",
                        "slug": "create-funds",
                        "entity_id": null,
                        "entity_type": "funds"
                    }, {
                        "id": 44,
                        "display_name": "Edit Funds",
                        "name": "edit",
                        "slug": "edit-funds",
                        "entity_id": null,
                        "entity_type": "funds"
                    }, {
                        "id": 45,
                        "display_name": "View Donors",
                        "name": "view",
                        "slug": "view-donors",
                        "entity_id": null,
                        "entity_type": "donors"
                    }, {
                        "id": 46,
                        "display_name": "Create Donors",
                        "name": "create",
                        "slug": "create-donors",
                        "entity_id": null,
                        "entity_type": "donors"
                    }, {
                        "id": 47,
                        "display_name": "Edit Donors",
                        "name": "edit",
                        "slug": "edit-donors",
                        "entity_id": null,
                        "entity_type": "donors"
                    }, {
                        "id": 48,
                        "display_name": "Delete Donors",
                        "name": "delete",
                        "slug": "delete-donors",
                        "entity_id": null,
                        "entity_type": "donors"
                    }, {
                        "id": 49,
                        "display_name": "Modify Todos",
                        "name": "modify",
                        "slug": "modify-todos",
                        "entity_id": null,
                        "entity_type": "todos"
                    }, {
                        "id": 50,
                        "display_name": "Modify Notes",
                        "name": "modify",
                        "slug": "modify-notes",
                        "entity_id": null,
                        "entity_type": "notes"
                    }, {
                        "id": 51,
                        "display_name": "View Uploads",
                        "name": "view",
                        "slug": "view-uploads",
                        "entity_id": null,
                        "entity_type": "uploads"
                    }, {
                        "id": 52,
                        "display_name": "Create Uploads",
                        "name": "create",
                        "slug": "create-uploads",
                        "entity_id": null,
                        "entity_type": "uploads"
                    }, {
                        "id": 53,
                        "display_name": "Edit Uploads",
                        "name": "edit",
                        "slug": "edit-uploads",
                        "entity_id": null,
                        "entity_type": "uploads"
                    }, {
                        "id": 54,
                        "display_name": "Delete Uploads",
                        "name": "delete",
                        "slug": "delete-uploads",
                        "entity_id": null,
                        "entity_type": "uploads"
                    }]
                }
            }
        };
        return {
            body: body,
            status: 200,
            statusText: 'OK',
            headers: {/*headers*/},
            delay: Settings.delay, // millisecond
        }
    },

    // Teams API

    ['GET *teams'](pathMatch, query, request) {
        let body = {
            "data": [
                {
                    "id": "ad417b30-51b1-48f4-b26d-0b6ed956c4d3",
                    "callsign": "Team #1",
                    "created_at": "2017-04-25 16:26:15",
                    "updated_at": "2017-04-25 16:26:15",
                    "deleted_at": null,
                    "links": [
                        {
                            "rel": "self",
                            "uri": "api/teams/ad417b30-51b1-48f4-b26d-0b6ed956c4d3"
                        }
                    ]
                }
            ],
            "meta": {
                "pagination": {
                    "total": 1,
                    "count": 1,
                    "per_page": 10,
                    "current_page": 1,
                    "total_pages": 1,
                    "links": []
                }
            }
        };

        return {
            body: body,
            status: 200,
            statusText: 'OK',
            headers: {/*headers*/},
            delay: Settings.delay, // millisecond
        }

    },
    ['GET *teams(/:team)(/:path)(/:pathId)'](pathMatch, query, request) {
        let body = {
            "data": [
                {
                    "id": "ad417b30-51b1-48f4-b26d-0b6ed956c4d3",
                    "callsign": "Team #1",
                    "created_at": "2017-04-25 16:26:15",
                    "updated_at": "2017-04-25 16:26:15",
                    "deleted_at": null,
                    "links": [
                        {
                            "rel": "self",
                            "uri": "api/teams/ad417b30-51b1-48f4-b26d-0b6ed956c4d3"
                        }
                    ]
                }
            ],
            "meta": {
                "pagination": {
                    "total": 1,
                    "count": 1,
                    "per_page": 10,
                    "current_page": 1,
                    "total_pages": 1,
                    "links": []
                }
            }
        };

        return {
            body: body,
            status: 200,
            statusText: 'OK',
            headers: {/*headers*/},
            delay: Settings.delay, // millisecond
        }

    },

    // mock influencer

}