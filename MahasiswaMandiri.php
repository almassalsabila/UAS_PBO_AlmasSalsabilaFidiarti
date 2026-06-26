<?php
require_once 'Mahasiswa.php';

class MahasiswaMandiri extends Mahasiswa {
    // Properti tambahan (spesifik)
    protected $golonganUkt;
    protected $namaWali;

    // Constructor untuk inisialisasi semua properti (termasuk milik parent)
    public function __construct($id_mahasiswa, $nama_mahasiswa, $nim, $semester, $tarifUktNominal, $golonganUkt, $namaWali) {
        parent::__construct($id_mahasiswa, $nama_mahasiswa, $nim, $semester, $tarifUktNominal);
        $this->golonganUkt = $golonganUkt;
        $this->namaWali = $namaWali;
    }

    // Implementasi Method Overriding: Mahasiswa Mandiri
    public function hitungTagihanSemester() {
        // total tagihan = tarifUktNominal + 100000
        $totalTagihan = $this->tarifUktNominal + 100000;
        return $totalTagihan;
    }

    // Implementasi metode tampilkanSpesifikasiAkademik
    public function tampilkanSpesifikasiAkademik() {
        return "Jenis Pembiayaan: Mandiri<br>" .
               "Nama Mahasiswa: {$this->nama_mahasiswa}<br>" .
               "NIM: {$this->nim}<br>" .
               "Semester: {$this->semester}<br>" .
               "Golongan UKT: {$this->golonganUkt}<br>" .
               "Nama Wali: {$this->namaWali}<br>";
    }

    /**
     * Method berisi query SELECT-WHERE untuk menyaring mahasiswa mandiri berdasarkan Golongan UKT tertentu
     */
    public function getQuerySelectByGolongan($golongan) {
        $golonganClean = intval($golongan);
        return "SELECT id_mahasiswa, nama_mahasiswa, nim, golongan_ukt, nama_wali 
                FROM tabel_mahasiswa 
                WHERE jenis_pembiayaan = 'mandiri' AND golongan_ukt = {$golonganClean};";
    }
}
?>