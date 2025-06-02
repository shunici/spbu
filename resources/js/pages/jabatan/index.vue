<template>

<div class="row">
    <div class="col-md-12">  
            <data-comp></data-comp>           
    </div> 
    
</div>
      

</template>
<script>

import dataComp from './data.vue';



        export default {
            name : 'index-jabatan',
      data() {
          return {
            
          }
      },
      components : {
          dataComp
      }
    }
</script>
