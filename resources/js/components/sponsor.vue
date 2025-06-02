<template>
      <aside class="control-sidebar bg-navy control-sidebar-dark no-print" style="z-index : 1039; position : fixed; top : 0px">
    <!-- Control sidebar content goes here -->
    <div class="card-header">
      <div class="card-title"> Special thanks to :</div> 
        <div class="float-right btn btn-danger btn-sm" data-widget="control-sidebar" data-slide="true" href="#" role="button">
            <i class="fa fa-times"></i>
        </div>
    
  
    </div>    

      
      <div  class="logo-container">
        <div class="logo" v-for="(sponsor, index) in sponsors" :key="index + 'sponsor'">
            <img :src="'/storage/sponsor/'+sponsor.logo" :alt="sponsor.nama"  @click="show(sponsor.id)">         
        </div>
            
        </div>
         
  </aside>
</template>


<script>
import {mapState, mapActions } from 'vuex';
export default {
  name : 'sponsorComp',
  computed : {
      ...mapState('sponsor_stores', {
                sponsors: state => state.sponsors_sidebar,                      
            }),
  },
  methods : {
    ...mapActions('sponsor_stores', ['show_sponsor']),                   
            show(param){            
               this.show_sponsor(param)                       
                this.$router.push({
                  name : 'show.sponsor',
                  params : {id : param}
              })             
            }, 

  }
    
}
</script>

<style>
    .logo-container {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .logo {
            width: 100px; /* Atur ukuran seragam */
            height: 100px; 
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f0f0f0; /* Background placeholder */
            border-radius: 10px;
            overflow: hidden;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain; /* Agar logo tidak terpotong */
        }
        .control-sidebar {

        }
</style>
    


