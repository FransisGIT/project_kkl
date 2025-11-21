// Mendapatkan elemen DOM
const tanggalElement = document.getElementById('tanggal');
const jamElement = document.getElementById('jam');

// Konstanta untuk nama hari dan bulan
const HARI_INDONESIA = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
const BULAN_INDONESIA = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
];

// Fungsi utilitas
const padZero = (angka) => (angka < 10 ? '0' : '') + angka;
const getHariIndonesia = (hari) => HARI_INDONESIA[hari];
const getBulanIndonesia = (bulan) => BULAN_INDONESIA[bulan - 1];

// Fungsi utama untuk memperbarui waktu
function updateWaktu() {
    const sekarang = new Date();

    // Ekstrak komponen waktu
    const hari = sekarang.getDay();
    const tanggal = sekarang.getDate();
    const bulan = sekarang.getMonth() + 1;
    const tahun = sekarang.getFullYear();
    const jam = padZero(sekarang.getHours());
    const menit = padZero(sekarang.getMinutes());
    const detik = padZero(sekarang.getSeconds());

    // Format tampilan
    const formatHari = getHariIndonesia(hari);
    const formatTanggal = `${formatHari}, ${tanggal} ${getBulanIndonesia(bulan)} ${tahun}`;
    const formatJam = `${jam}:${menit}:${detik}`;

    // Simpan ke localStorage
    localStorage.setItem('tanggal', formatTanggal);
    localStorage.setItem('jam', formatJam);

    // Update DOM
    tanggalElement.textContent = formatTanggal;
    jamElement.textContent = formatJam;
}

// Inisialisasi saat halaman dimuat
document.addEventListener("DOMContentLoaded", () => {
    const storedTanggal = localStorage.getItem('tanggal');
    const storedJam = localStorage.getItem('jam');

    if (storedTanggal && storedJam) {
        tanggalElement.textContent = storedTanggal;
        jamElement.textContent = storedJam;
    }

    // Jalankan segera dan set interval
    updateWaktu();
    setInterval(updateWaktu, 1000);
});
