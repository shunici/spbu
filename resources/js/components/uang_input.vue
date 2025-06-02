<template>
 
        <input :placeholder="placeholder" class="form-control" type="string" v-model="currentValue" @input="handleInput"  />

</template>
<script>
    export default {
        name : 'uangInput',
            props: {
                    value: {
                    type: [String, Number],
                    default: ""
                   },
                    placeholder : {
                        type : String
                    }
        },
        data: () => ({
            currentValue: '',        
        }),
        watch: {
            value: {
            handler(after) {
                this.currentValue = this.format(after)
            },
            immediate: true
            }
        },
        methods: {
            format: value => (value + '').replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, "."),
            
            handleInput() {
            this.currentValue =  this.format(this.currentValue)
            this.$emit('input', (this.currentValue + '').replace(/[^0-9]/g, ""))
            }
        }
    }
</script>