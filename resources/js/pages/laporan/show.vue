<template>  
 <div class="card">  
 
<div class="card-header no-print">
  <button class="btn btn-outline-primary  no-print mr-2" @click="$router.go(-1)"> <i class="fa fa-code"></i> Kembali  </button>
  
 <button  type="button" class="btn btn-outline-primary  no-print" @click="print">
                        <i class="fa fa-print mr-2"></i>   PRINT
                </button> 
</div>

<div class="pl-5 pr-5">
  <div v-html="laporan.uraian"> </div>      
</div>


  </div>
</template>

<script>

      import { mapActions, mapMutations, mapState } from 'vuex'
      export default {
          name : 'forShow',
          data () {
              return {

              }
          },
          components : {
            
          },
          mounted () {                    
               
                setTimeout(() => {  
                    if(this.laporan == "") {
                 let path = window.location.pathname;
                 let slug = path.split('/').slice(-1)[0];
                 this.show_laporan(slug);
                    }
                }, 3500);
                
               
        
           // Ambil bagian terakhir
          },
          computed : {
                ...mapState(['errors']),
                ...mapState('laporan_stores', {                 
                    laporan: state => state.laporan_show,
                    pesan : state => state.pesan
                }),
                  
          },
          methods : {  
            ...mapMutations('laporan_stores', ['CLEAR_FORM']),
                ...mapActions('laporan_stores', ['submit_laporan', 'get_laporan' ,'show_laporan']),            
              proses () {                              
                  this.$refs.proses.submit()
             
              },
                print() {          
                window.print();            
                // window.location.reload(); // Mengembalikan halaman setelah print
                },                
               updateWindowWidth() {
                this.windowWidth = window.innerWidth;
              },   
          },          
       beforeDestroy() {
            // Hapus event listener saat komponen dihancurkan
            window.removeEventListener("resize", this.updateWindowWidth);
          },
          destroyed() {
            this.CLEAR_FORM()
          }
      }
</script>

