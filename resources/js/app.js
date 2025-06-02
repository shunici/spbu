
// import '../sass/app.scss'
require('./bootstrap');
import Vue from "vue";

import App from './app.vue'
import router from './router.js'
import store from './store.js'

import Permissions from './mixins/Permission.js'
Vue.mixin(Permissions)





import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

import { BootstrapVue } from 'bootstrap-vue'
Vue.use(BootstrapVue)

//format uang
import VueCurrencyFilter from 'vue-currency-filter'
Vue.use(VueCurrencyFilter) 

Vue.use(VueCurrencyFilter,
  {
    symbol : 'Rp.',
    thousandsSeparator: '.',
    fractionCount: 0,
    fractionSeparator: ',',
    symbolPosition: 'front',
    symbolSpacing: true
  })

  

//   Vue.filter('terbilang', function (value) {
//     function terbilang(a){var c=" Satu Dua Tiga Empat Lima Enam Tujuh Delapan Sembilan Sepuluh Sebelas".split(" ");if(12>a)var b=c[a];else 20>a?b=c[a-10]+" Belas":100>a?(b=parseInt(String(a/10).substr(0,1)),b=c[b]+" Puluh "+c[a%10]):200>a?b="Seratus "+terbilang(a-100):1E3>a?(b=parseInt(String(a/100).substr(0,1)),b=c[b]+" Ratus "+terbilang(a%100)):2E3>a?b="Seribu "+terbilang(a-1E3):1E4>a?(b=parseInt(String(a/1E3).substr(0,1)),b=c[b]+" Ribu "+terbilang(a%1E3)):1E5>a?(b=parseInt(String(a/100).substr(0,2)),
//     a%=1E3,b=terbilang(b)+" Ribu "+terbilang(a)):1E6>a?(b=parseInt(String(a/1E3).substr(0,3)),a%=1E3,b=terbilang(b)+" Ribu "+terbilang(a)):1E8>a?(b=parseInt(String(a/1E6).substr(0,4)),a%=1E6,b=terbilang(b)+" Juta "+terbilang(a)):1E9>a?(b=parseInt(String(a/1E6).substr(0,4)),a%=1E6,b=terbilang(b)+" Juta "+terbilang(a)):1E10>a?(b=parseInt(String(a/1E9).substr(0,1)),a%=1E9,b=terbilang(b)+" Milyar "+terbilang(a)):1E11>a?(b=parseInt(String(a/1E9).substr(0,2)),a%=1E9,b=terbilang(b)+" Milyar "+terbilang(a)):
//     1E12>a?(b=parseInt(String(a/1E9).substr(0,3)),a%=1E9,b=terbilang(b)+" Milyar "+terbilang(a)):1E13>a?(b=parseInt(String(a/1E10).substr(0,1)),a%=1E10,b=terbilang(b)+" Triliun "+terbilang(a)):1E14>a?(b=parseInt(String(a/1E12).substr(0,2)),a%=1E12,b=terbilang(b)+" Triliun "+terbilang(a)):1E15>a?(b=parseInt(String(a/1E12).substr(0,3)),a%=1E12,b=terbilang(b)+" Triliun "+terbilang(a)):1E16>a&&(b=parseInt(String(a/1E15).substr(0,1)),a%=1E15,b=terbilang(b)+" Kuadriliun "+terbilang(a));a=b.split(" ");c=[];for(b=0;b<a.length;b++)""!=a[b]&&c.push(a[b]);return c.join(" ")};
//     return terbilang(value);
// });
import { terbilang } from './terbilang';
Vue.filter('terbilang', function (value) {
  return terbilang(value);
});




require('froala-editor/js/languages/id.js')
// froala
//Import third party plugins

import 'froala-editor/js/plugins/inline_style.min.js';
import 'froala-editor/js/third_party/font_awesome.min';
import 'froala-editor/js/plugins/font_family.min.js';
import 'froala-editor/js/plugins/font_size.min.js';
import 'froala-editor/js/plugins/table.min.js';
import 'froala-editor/css/plugins/table.min.css';
import 'froala-editor/js/plugins/lists.min.js';
import 'froala-editor/js/plugins/colors.min.js';
import 'froala-editor/js/plugins/paragraph_format.min.js';
import 'froala-editor/js/plugins/paragraph_style.min.js';
import 'froala-editor/js/plugins/align.min.js';
import 'froala-editor/js/third_party/embedly.min';
import 'froala-editor/js/third_party/spell_checker.min';
import 'froala-editor/js/plugins.pkgd.min.js';
import 'froala-editor/js/plugins/code_view.min.js';



import 'froala-editor/js/plugins/image.min.js';
import 'froala-editor/js/plugins/image_manager.min.js';

// Import Froala Editor css files.
import 'froala-editor/css/froala_editor.pkgd.min.css';

// Import and use Vue Froala lib.
import VueFroala from 'vue-froala-wysiwyg'
Vue.use(VueFroala)
// Vue.config.productionTip = false





const app = new Vue({
    el: '#app',   
    router, store,
    components : {
        App
    },
});
