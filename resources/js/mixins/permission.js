export default {
    methods: {
        $can(permissionName) {            
            let Permission = this.$store.state.user.authenticated.permission
            if (typeof Permission != 'undefined') {
                return Permission.indexOf(permissionName) !== -1;
            }
           
        },
        $role(param) {            
            let superadmin = this.$store.state.user.authenticated.role_id;                  
            if(param == superadmin) {
                return true
            }else{
                return false
            }
                                                   
        },
    },
};

// cara gunakan di tag html {{$role(1)}}

//untuk mengatur role dan can yang tersabung pada api
// role itu mengambil data user yang login yang diambil dari app.js dan user.js
//permissin diambil di userController sedangkan role mengambil di role permission 