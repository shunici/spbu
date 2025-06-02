import moment from "moment-hijri";
moment.locale('id');  
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
  "Zulhijjah",
];


export function getMasehiHijriah(timestamp = null) {


  // Pastikan Moment dapat membaca input, baik string format atau milidetik
  const date = timestamp ? moment(timestamp) : moment();

  if (!date.isValid()) {
    throw new Error("Format timestamp tidak valid.");
  }   

  return {
    mHari : date.format("dddd"),
    mTanggal : date.format("DD"),
    mBulan : date.format("MMMM"),
    mTahun : date.format("YYYY"),
    hTanggal : date.iDate(),
    hBulan : bulanHijriahIndonesia[date.iMonth()],
    hTahun : date.iYear()

  }
}
