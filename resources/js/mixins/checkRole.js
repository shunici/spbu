export  function checkRole( param) {
     let superadmin = this.$store.state.user.authenticated.role_id; 
    return param === superadmin;
  }
  