<template>
    <div>




        <div class="form-group">
          <label for="judul_laporan" class="text-uppercase">Judul Laporan</label>
          <input type="text"
            class="form-control" name="judul_laporan" id="judul_laporan"  placeholder="" v-model="laporan.judul">
          <small  class="form-text  text-danger" v-if="errors.judul">  <i> {{ errors.judul[0] }}</i> </small>
        </div> 

        <div class="form-group">          
            <label for="jenis_laporan" class="text-uppercase">jenis laporan</label>
            <select class="form-control" v-model="laporan.jenis_laporan" > 
              <option value="">PILIH JENIS LAPORAN</option>                                               
                <option v-for="(item, index) in jenis_laporan" :key="index">
                  {{item}}
                </option>                                                                                                                       
            </select>  
            <small  class="form-text  text-danger" v-if="errors.jenis_laporan"> <i> {{ errors.jenis_laporan[0] }} </i></small>
        </div>
        

<div class="form-group">
<label for="" class="text-uppercase">template</label>
  <SwiperComponent :items="templates" :cardWidth="100">
      <template #default="{ item }">         
    <button class="btn btn-default btn-xs m-1"    :key="item + 'sd'" @click="pakai(item)">
    {{item.judul}}
    </button>          
      </template>
    </SwiperComponent>
</div>
        <froala :tag="'textarea'" :config="config" v-model="laporan.uraian" style="font-size: 30px;"></froala>


 <div v-if="Object.keys(errors).length">
      <ul>
        <li v-for="(messages, field) in errors" :key="field">
          <span v-for="(message, index) in messages" :key="index">
            {{ message }}
          </span>
        </li>
      </ul>
    </div>


    </div>
</template>

<script>
import SwiperComponent from '../../components/swipperComponent.vue';
      import { mapActions, mapState, mapMutations } from 'vuex'
      export default {
          name : 'formAdd',
          data () {
              return {
      
                
              }
          },
        components : {
            SwiperComponent
        },
          computed : {
                ...mapState(['errors']),
                ...mapState('laporan_stores', {                 
                    laporan: state => state.laporan,
                    templates : state => state.template.data,
                    pesan : state => state.pesan,
                      jenis_laporan : state => state.jenis_laporan,    
                            config : state => state.config,                  
                }),
          }, 
          methods : {         
             ...mapMutations('laporan_stores', ['CLEAR_FORM', 'SET_ID_UPDATE']),           
              ...mapActions('laporan_stores', ['submit_laporan', 'update_laporan']),            
              submit () {       
              
              if(this.$route.name == 'add.laporan') {              
                  this.submit_laporan().then(() => {
                        this.pesan.sukses = true
                     setTimeout(function () {                                                                             
                         this.pesan.sukses = false
                        this.$router.push({ name: 'data.laporan' })
                        this.CLEAR_FORM();
                    }.bind(this), 1700);
                  } ); //this submit 
                }else {
                  this.update_laporan().then(() => {
                        this.pesan.sukses = true
                     setTimeout(function () {                                                                             
                         this.pesan.sukses = false
                        this.$router.push({ name: 'data.laporan' })
                        this.CLEAR_FORM();
                        this.SET_ID_UPDATE('')
                    }.bind(this), 1700);
                  } ); //this submit 
                }
                    // if(this.$route.name == 'add.laporan'){                                                                        
              },
              pakai (param) {                                                    
                // this.laporan.judul = param.judul;
                this.laporan.jenis_laporan = param.jenis_laporan;
                this.laporan.uraian = param.uraian
              }
          }, //methode
      }
      
</script>