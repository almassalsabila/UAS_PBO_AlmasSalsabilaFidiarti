<?php
require_once 'Mahasiswa.php';

class MahasiswaPrestasi extends Mahasiswa {
    // Properti tambahan (spesifik)
    protected $namaInstansiBeasiswa;
    protected $minimalIpkSyarat;

    // Constructor untuk inisialisasi semua properti
    public function __construct($id_mahasiswa, $nama_mahasiswa, $nim, $semester, $tarifUktNominal, $namaInstansiBeasiswa, $minimalIpkSyarat) {
        parent::__construct($id_mahasiswa, $nama_mahasiswa, $nim, $semester, $tarifUktNominal);
        $this->namaInstansiBeasiswa = $namaInstansiBeasiswa;
        $this->minimalIpkSyarat = $minimalIpkSyarat;
    }

    // Implementasi metode hitungTagihanSemester
    public function hitungTagihanSemester() {
        // Misalkan skema mahasiswa prestasi mendapatkan potongan UKT 50%
        return $this->tarifUktNominal * 0.5;
    }

    // Implementasi metode tampilkanSpesifikasiAkademik
    public function tampilkanSpesifikasiAkademik() {
        return "Jenis Pembiayaan: Prestasi<br>" .
               "Nama Mahasiswa: {$this->nama_mahasiswa}<br>" .
               "NIM: {$this->nim}<br>" .
               "Semester: {$this->semester}<br>" .
               "Instansi Pemberi Beasiswa: {$this->namaInstansiBeasiswa}<br>" .
               "Minimal IPK Syarat: {$this->minimalIpkSyarat}<br>";
    }

    /**
     * Method berisi query SELECT-WHERE untuk menyaring mahasiswa prestasi berdasarkan nama Instansi Beasiswa
     */
    public function getQuerySelectByInstansi($instansi) {
        $instansiClean = addslashes($instansi);
        return "SELECT id_mahasiswa, nama_mahasiswa, nim, nama_instansi_beasiswa, minimal_ipk_syarat 
                FROM tabel_mahasiswa 
                WHERE jenis_pembiayaan = 'prestasi' AND nama_instansi_beasiswa LIKE '%{$instansiClean}%';";
    }
}
?>