<template>

<div class="row">
    <div class="col-md-3 pl-0 pr-0 p-md-3">  
            <card-comp></card-comp>           

    </div> 
    <div class="col-md-9 pl-0 pr-0 p-md-3">
            <form-comp></form-comp>
    </div>
</div>
      

</template>
<script>

import cardComp from './card.vue';
import formComp from './form.vue';

        export default {
            name : 'profile-index',
      data() {
          return {
            
          }
      },
      components : {
          cardComp, formComp
      }
    }
</script>
