<template>

                            
  <!-- Main content -->


      <div class="row">
        <div class="col-12">
           <router-view></router-view>
     </div>
     </div>



      
</template>
<script>
import Breadcrumb from '../../components/breadcrumb'
export default {
    nama : 'IndexSetting',
    components : {
        'breadcrumb' : Breadcrumb
    }
}
</script>