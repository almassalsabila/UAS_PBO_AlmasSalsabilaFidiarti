<?php
require_once 'Mahasiswa.php';

class MahasiswaBidikMisi extends Mahasiswa {
    // Properti tambahan (spesifik)
    protected $nomorKipKuliah;
    protected $danaSakuSubsidi;

    // Constructor untuk inisialisasi semua properti
    public function __construct($id_mahasiswa, $nama_mahasiswa, $nim, $semester, $tarifUktNominal, $nomorKipKuliah, $danaSakuSubsidi) {
        parent::__construct($id_mahasiswa, $nama_mahasiswa, $nim, $semester, $tarifUktNominal);
        $this->nomorKipKuliah = $nomorKipKuliah;
        $this->danaSakuSubsidi = $danaSakuSubsidi;
    }

    // Implementasi Method Overriding: Mahasiswa Bidikmisi
    public function hitungTagihanSemester() {
        // total tagihan = 0
        return 0;
    }

    // Implementasi metode tampilkanSpesifikasiAkademik
    public function tampilkanSpesifikasiAkademik() {
        $danaSakuFormat = number_format($this->danaSakuSubsidi, 2, ',', '.');
        return "Jenis Pembiayaan: Bidikmisi<br>" .
               "Nama Mahasiswa: {$this->nama_mahasiswa}<br>" .
               "NIM: {$this->nim}<br>" .
               "Semester: {$this->semester}<br>" .
               "Nomor KIP Kuliah: {$this->nomorKipKuliah}<br>" .
               "Dana Saku Subsidi: Rp {$danaSakuFormat}<br>";
    }

    /**
     * Method berisi query SELECT-WHERE untuk mencari mahasiswa bidikmisi berdasarkan Nomor KIP Kuliah
     */
    public function getQuerySelectByNoKip($noKip) {
        // Menghindari SQL Injection sederhana untuk contoh query string
        $noKipClean = addslashes($noKip); 
        return "SELECT id_mahasiswa, nama_mahasiswa, nim, nomor_kip_kuliah, dana_saku_subsidi 
                FROM tabel_mahasiswa 
                WHERE jenis_pembiayaan = 'bidikmisi' AND nomor_kip_kuliah = '{$noKipClean}';";
    }
}
?>