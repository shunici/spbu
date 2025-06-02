<template>

    <!-- Tombol untuk memulai voice recognition -->
    <button  class="btn btn-info btn-xs" @click="startListening">
        <i class="fa fa-microphone"> </i>     
      {{ listening ? 'Sedang Mendengarkan..' : 'Deteksi Suara' }}
    </button>
 

</template>

<script>
export default {
  data() {
    return {
      listening: false, // Status apakah sedang mendengarkan atau tidak
      recognition: null, // Objek SpeechRecognition
    };
  },
  mounted() {
    this.initializeSpeechRecognition();
  },
  methods: {
    // Inisialisasi Web Speech API
    initializeSpeechRecognition() {
      const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
      // if (!SpeechRecognition) {
      //   alert("Browser Anda tidak mendukung Web Speech API.");
      //   return;
      // }

      this.recognition = new SpeechRecognition();
      this.recognition.lang = 'id-ID'; // Set bahasa ke Indonesia
      this.recognition.interimResults = false; // Hanya hasil akhir
      this.recognition.maxAlternatives = 1; // Hanya satu alternatif hasil

      // Event ketika speech recognition selesai
      this.recognition.onresult = (event) => {
        const transcript = event.results[0][0].transcript.toLowerCase();
        console.info("Teks yang diterima dari suara:", transcript); // Debug
        this.processTranscript(transcript); // Proses hasil suara
        this.listening = false;
      };

      // Event ketika terjadi error
      this.recognition.onerror = (event) => {
        console.error("Error occurred in recognition: ", event.error);
        this.listening = false;
      };
    },
    // Mulai mendengarkan
    startListening() {
      if (this.recognition) {
        this.recognition.start();
        this.listening = true;
      }
    },
    // Proses hasil suara
    processTranscript(transcript) {
      let kategori = '';
      let total = 0;

      // Pisahkan kategori dan total dari transcript
      if (transcript.includes("kategori")) {
        const kategoriMatch = transcript.match(/kategori (.+?) total/);
        if (kategoriMatch) {
          kategori = kategoriMatch[1].trim();
          console.info("Kategori yang ditemukan:", kategori); // Debug
        }
      }

      if (transcript.includes("total")) {
        const totalMatch = transcript.match(/total (.+)/);
        if (totalMatch) {
          const totalText = totalMatch[1].trim();
          console.info("Total yang ditemukan (teks):", totalText); // Debug
          total = this.convertTextToNumber(totalText);
          console.info("Total yang dikonversi (angka):", total); // Debug
        }
      }

      // Kirim hasil ke parent component (Page A)
      this.$emit('processed', { kategori, total });
    },
    // Fungsi untuk mengonversi teks ke angka
    convertTextToNumber(text) {
  const numberWords = {
    'nol': 0,
    'satu': 1,
    'dua': 2,
    'tiga': 3,
    'empat': 4,
    'lima': 5,
    'enam': 6,
    'tujuh': 7,
    'delapan': 8,
    'sembilan': 9,
    'sepuluh': 10,
    'sebelas': 11,
    'dua belas': 12,
    'tiga belas': 13,
    'empat belas': 14,
    'lima belas': 15,
    'enam belas': 16,
    'tujuh belas': 17,
    'delapan belas': 18,
    'sembilan belas': 19,
    'dua puluh': 20,
    'tiga puluh': 30,
    'empat puluh': 40,
    'lima puluh': 50,
    'enam puluh': 60,
    'tujuh puluh': 70,
    'delapan puluh': 80,
    'sembilan puluh': 90,
    'seratus': 100,
    'dua ratus': 200,
    'tiga ratus': 300,
    'empat ratus': 400,
    'lima ratus': 500,
    'enam ratus': 600,
    'tujuh ratus': 700,
    'delapan ratus': 800,
    'sembilan ratus': 900,
    'seribu': 1000,
    'ribu': 1000,
    'juta': 1000000,
  };

  // Jika teks adalah angka dalam bentuk digit (misalnya, "150"), langsung konversi ke angka
  if (!isNaN(text.replace(/[^0-9]/g, ''))) {
    console.log(`Teks "${text}" adalah angka dalam bentuk digit`);
    let number = parseInt(text.replace(/[^0-9]/g, ''), 10);

    // Jika teks mengandung kata "ribu" atau "juta", kalikan angka dengan faktor pengali
    if (text.includes("ribu")) {
      console.log(`Teks mengandung "ribu", dikalikan 1000`);
      number *= 1000;
    } else if (text.includes("juta")) {
      console.log(`Teks mengandung "juta", dikalikan 1000000`);
      number *= 1000000;
    }

    return number;
  }

  // Jika teks mengandung kata-kata angka, proses seperti biasa
  const words = text.split(' ');
  let total = 0;
  let temp = 0;

  words.forEach(word => {
    if (numberWords[word]) {
      console.log(`Kata "${word}" dikenali sebagai ${numberWords[word]}`);
      if (word === 'ribu' || word === 'juta') {
        temp *= numberWords[word];
        total += temp;
        temp = 0;
      } else {
        temp += numberWords[word];
      }
    } else {
      console.log(`Kata "${word}" TIDAK dikenali`);
    }
  });

  return total + temp;
}
  },
};
</script>

