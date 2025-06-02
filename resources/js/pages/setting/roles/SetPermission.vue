<template>
    <div class="card">
        <div class="card-header">
  <button class="btn btn-primary btn-sm float-left" @click="$router.go(-1)"> <i class="fa fa-code"></i> Kembali  </button>
        </div>
        <div class="card-body row">
             <div class="col-md-5">
                <div class="panel">
                    <div class="panel-heading">
                        <h3 class="panel-title">Setting Role pada user</h3>
                    </div>
                    
                    <div class="panel-body">
                        <div class="alert alert-success" v-if="alert_role">Role berhasil ditambahkan </div>
                        <div class="form-group">
                            <label for="">Role</label>
                            <select class="form-control" v-model="role_user.role">
                                <option value="">Pilih</option>
                                <option v-for="(row, index) in roles" :value="{val :row.name, id_role: row.id}" :key="index">{{ row.name }} </option>
                            </select>
                            <p class="text-danger" v-if="errors.role_id">{{ errors.role_id[0] }}</p>
                        </div>
                        <div class="form-group">
                            <label for="">User</label>
                            <select class="form-control" v-model="role_user.user_id">
                                <option value="">Pilih</option>
                                <option v-for="(row, index) in users.data" :value="row.id" :key="index">{{ row.name }} ({{row.email}}) | {{row.role.name}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger btn-sm" @click="setRole">Set Role</button>
                        </div>
                    </div>
                </div>
            </div> <!-- col-md-5 -->
            <div class="col-md-7">
                <div class="panel">
                    <div class="panel-heading"><h3 class="panel-title">Set Hak Akses Pada Role</h3></div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label for="">Role</label>
                            <select class="form-control" v-model="role_selected" @change="checkPermission">
                                <option value="">Pilih</option>
                                <option v-for="(row, index) in roles" :value="row.id" :key="index">{{ row.name }}</option>
                            </select>
                            <p class="text-danger" v-if="errors.role_id">{{ errors.role_id[0] }}</p>
                        </div>
                        <div class="form-group">
                            <div class="alert alert-success" v-if="alert_permission">Hak Akses diperbaharui</div>
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab_1" data-toggle="tab">Permissions</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab_1">
                                        <template v-for="(row, index) in permissions">
                                            <input type="checkbox" 
                                                class="minimal-red" 
                                                :key="index"
                                                :value="row.name"
                                                :checked="role_permission.findIndex(x => x.name == row.name) != -1"
                                                @click="addPermission(row.name)"
                                                > {{ row.name }} <br :key="'row' + index">
                                            <br :key="'enter' + index" v-if="(index+1) %4 == 0">
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pull-right">
                            <button class="btn btn-primary btn-sm" @click="setPermission">
                                <i class="fa fa-send"></i> Set Permission
                            </button>
                        </div>
                        <!-- {{new_permission}} -->
                    </div>
                        <!-- {{new_permission}} <br> -->
                    
                </div>
            </div>
            <!-- {{role_user}} -->
        </div>    <!-- tutup card body -->
    </div>   <!-- tutup card -->
</template>
<script>
    import { mapActions, mapState, mapMutations } from 'vuex'
    
    export default {
        name: 'SetPermission',
        data() {
            return {
                role_user: {
                    role: '', // ini 2 data val dan id_role
                    user_id: '',
                },
                role_selected: '',
                new_permission: [],
               
                alert_permission: false,
                alert_role: false,
            }
        },
        created() {
            this.getRoles()
            this.getAllPermission()
            this.getUserLists()
             
        },
        computed: {
            ...mapState(['errors']),
            ...mapState('user', {
                users: state => state.users,
                roles: state => state.roles,
                permissions: state => state.permissions,
                role_permission: state => state.role_permission
            })
        },
        methods: {
            ...mapActions('user', [
                'getUserLists', 
                'getRoles', 
                'getAllPermission', 
                'getRolePermission', 
                'setRolePermission',
                'setRoleUser'
            ]),
            ...mapMutations('user', ['CLEAR_ROLE_PERMISSION']),
            setRole() {
                this.setRoleUser(this.role_user).then(() => {
                    this.alert_role = true
                    setTimeout(() => {
                        this.role_user = {
                            role: '',
                            user_id: ''
                        }                        
                        this.alert_role = false
                    }, 1000)
                })
            },
            addPermission(name) {              
                let index = this.new_permission.findIndex(x => x == name)
                if (index == -1) {
                    this.new_permission.push(name)
                } else {
                    this.new_permission.splice(index, 1)
                }
            },
            checkPermission() {               
                this.getRolePermission(this.role_selected).then(() => {                                                        
                    var valuesOnly = this.role_permission.map(function(item) {
                        return item.name;
                    });
                    this.new_permission = valuesOnly;
                })
            },
            setPermission() {
                this.setRolePermission({
                    role_id: this.role_selected,
                    permissions: this.new_permission
                }).then((res) => {
                    if (res.status == 'success') {                        
                        this.alert_permission = true
                        setTimeout(() => {
                            this.role_selected = ''
                            this.new_permission = []
                            this.loading = false
                            this.alert_permission = false
                            this.CLEAR_ROLE_PERMISSION()
                        }, 1000)
                    }
                })
            }
        }
    }
    // dibagian array set permission masih belum sempurna
</script>

<style type="text/css">
    .tab-pane{
        height:150px;
        overflow-y:scroll;
    }
</style>

