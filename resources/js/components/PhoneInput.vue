<template> 
    <input
    class="form-control"
      :id="id"
      type="text"
      v-model="formattedPhone"
      @input="handleInput"
      :placeholder="placeholder"
    />
</template>

<script>
export default {
  name: "PhoneInput",
  props: {
    value: {
      type: String,
      default: "" // Nilai awal input (jika ada)
    },
    id: {
      type: String,
      default: "phone-input" // ID default input
    },
    placeholder: {
      type: String,
      default: "62813-XXXX-XXXX" // Placeholder default
    }
  },
  data() {
    return {
      formattedPhone: "" // Format tampilan nomor telepon
    };
  },
  watch: {
    // Sinkronisasi nilai awal saat komponen di-mount
    value: {
      immediate: true,
      handler(newValue) {
        this.formattedPhone = this.formatPhone(newValue || "");
      }
    }
  },
  methods: {
    handleInput() {
      // Hapus semua karakter non-angka
      let rawValue = this.formattedPhone.replace(/\D/g, "");

      // Jika dimulai dengan 08, ubah menjadi 628
      if (rawValue.startsWith("08")) {
        rawValue = "628" + rawValue.slice(2);
      }

      // Emit data mentah ke parent
      this.$emit("input", rawValue);

      // Perbarui tampilan dengan format
      this.formattedPhone = this.formatPhone(rawValue);
    },
    formatPhone(rawValue) {
      // Format nomor menjadi grup angka
      const prefix = rawValue.slice(0, 5); // Ambil prefix awal
      const remaining = rawValue.slice(5); // Sisanya

      // Pecah menjadi grup 4 angka
      const chunks = remaining.match(/.{1,4}/g) || [];
      return `${prefix}${chunks.length ? '-' : ''}${chunks.join('-')}`;
    }
  }
};
</script>
