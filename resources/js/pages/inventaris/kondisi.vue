<template>  
 <b-modal id="kondisi-inventaris" ref="kondisi-inventaris" title="Setting Kondisi Inventaris">
  
     

        <div class="form-group">
            <label for="nama" class="text-uppercase">Nama</label>
            <input disabled  v-on:keyup.enter="proses" type="text" id="nama" class="form-control" placeholder="Nama" v-model="inventaris.nama">
        <p class="text-danger" v-if="errors.nama"><i>{{ errors.nama[0] }}</i></p>
        </div>  
<div class="row">
    <div class="col-10">
        <div class="form-group">
            <label for="total" class="text-uppercase">total</label>
            <input   v-on:keyup.enter="proses" type="text" id="total" class="form-control" placeholder="kondisi baik" v-model="inventaris.total">
        <p class="text-danger" v-if="errors.total"><i>{{ errors.total[0] }}</i></p>
        </div>  

    </div>
    <div class="col-2 align-self-center">
         <label for="" class="text-uppercase mt-4">{{inventaris.satuan}}</label>
    </div>
</div>

<div class="row">

<div class="col-4">
        <div class="form-group">
            <label for="kondisi_baik" class="text-uppercase">kondisi baik</label>
            <input  v-on:keyup.enter="proses" type="text" id="kondisi_baik" class="form-control" placeholder="kondisi baik" v-model="inventaris.kondisi_baik">
        <p class="text-danger" v-if="errors.kondisi_baik"><i>{{ errors.kondisi_baik[0] }}</i></p>
        </div>  
</div>

<div class="col-2 align-self-center">
      <label for="" class="text-uppercase mt-4">{{inventaris.satuan}}</label>
</div>

<div class="col-4">
        <div class="form-group">
            <label for="kondisi_rusak" class="text-uppercase">kondisi rusak</label>
            <input  v-on:keyup.enter="proses" type="text" id="kondisi_rusak" class="form-control" placeholder="kondisi baik" v-model="inventaris.kondisi_rusak">
        <p class="text-danger" v-if="errors.kondisi_rusak"><i>{{ errors.kondisi_rusak[0] }}</i></p>
        </div>  
</div>

<div class="col-2 align-self-center">
      <label for="" class="text-uppercase mt-4">{{inventaris.satuan}}</label>
</div>


</div>

       <div v-if="errors.koneksi" class="alert alert-danger" role="alert">
           <strong>{{errors.koneksi}}</strong> 
        </div>   
           
  <template #modal-footer>
        <div class="w-100">         
          <b-button
            variant="primary"
            size="sm"
            class="float-right"
            @click="proses"   
          :disabled="pesan.sukses"           
          >                          
            <b-spinner small type="grow" v-if="pesan.sukses"></b-spinner>
             {{pesan.sukses ? "Sedang di proses tunggu sebentar..." : "Proses"}} 
          </b-button>
             <b-button
            variant="danger"
            size="sm"
            class="float-left"
            @click="$bvModal.hide('kondisi-inventaris')"
          >
            Tutup
          </b-button>
        </div>
      </template>
      

  </b-modal>
</template>

<script>
      import { mapActions, mapState, mapMutations } from 'vuex'
      export default {
          name : 'formkondisi',
          data () {
              return {

              }
          },
          computed : {
                ...mapState(['errors']),
                ...mapState('inventaris_stores', {                 
                    inventaris: state => state.inventaris,
                    pesan : state => state.pesan
                }),
          },
          methods : {
                ...mapMutations('inventaris_stores', ['CLEAR_FORM']),
                ...mapActions('inventaris_stores', ['update_inventaris', 'get_inventaris', 'inventaris_kondisi']),            
              proses (ev) {
                var baik = this.inventaris.kondisi_baik;
                 var rusak = this.inventaris.kondisi_rusak;     
                 var total = this.inventaris.total;      
                if( parseInt(baik) + parseInt(rusak) <= parseInt(total)) {                  
                       ev.preventDefault()
                        this.inventaris_kondisi().then(() => {
                                this.pesan.sukses = true
                          setTimeout(function () {
                              this.get_inventaris();
                              //close modal                   
                              this.$bvModal.hide('kondisi-inventaris')
                              this.CLEAR_FORM();
                              this.pesan.sukses = false
                          }.bind(this), 1700);
                        } ); //this submit
                } else {
                         this.$swal({
                            icon : 'error',
                            title: 'Cek Total Inventaris',
                            text: "Total kondisi tidak boleh melebihi total inventaris",                      
                        });
                } //else                                                   
             
              }
          }
      }
</script>

