<template>
  <div>
    {{ masehiDate }} M / {{ hijriDateIndonesia }} H
  </div>
</template>

<script>
import moment from "moment-hijri";
moment.locale('id'); 
export default {
  props: {
    timestamp: {
      type: Number, // Atau gunakan String jika timestamp Anda berupa string
      required: true,
    },
  },
  methods: {
    // Konversi timestamp ke tanggal Masehi
    masehiDate() {
      return moment(this.timestamp).format("dddd, DD MMMM YYYY");
    },
    // Konversi timestamp ke tanggal Hijriah dengan nama bulan dalam bahasa Indonesia
    hijriDateIndonesia() {
      const bulanHijriahIndonesia = [
        "Muharram",
        "Safar",
        "Rabiul Awal",
        "Rabiul Akhir",
        "Jumadil Awal",
        "Jumadil Akhir",
        "Rajab",
        "Sya'ban",
        "Ramadhan",
        "Syawal",
        "Zulqaidah",
        "Zulhijjah"
      ];

      const hijriDate = moment(this.timestamp); // Tanggal dari timestamp
      const tanggal = hijriDate.iDate(); // Hari Hijriah
      const bulan = bulanHijriahIndonesia[hijriDate.iMonth()]; // Nama bulan Indonesia
      const tahun = hijriDate.iYear(); // Tahun Hijriah

      return `${tanggal} ${bulan} ${tahun}`;
    },
  },
};
</script>
