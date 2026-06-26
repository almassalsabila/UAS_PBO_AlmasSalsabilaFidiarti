<?php

abstract class Mahasiswa {
    // Properti terenkapsulasi (protected) yang dipetakan dari kolom tabel database
    protected $id_mahasiswa;
    protected $nama_mahasiswa;
    protected $nim;
    protected $semester;
    protected $tarifUktNominal; // Representasi dari kolom tarif_ukt_nominal

    /**
     * Constructor untuk memudahkan pemetaan data (mapping) dari database ke dalam objek.
     */
    public function __construct($id_mahasiswa, $nama_mahasiswa, $nim, $semester, $tarifUktNominal) {
        $this->id_mahasiswa = $id_mahasiswa;
        $this->nama_mahasiswa = $nama_mahasiswa;
        $this->nim = $nim;
        $this->semester = $semester;
        $this->tarifUktNominal = $tarifUktNominal;
    }

    // Metode abstrak (tanpa body) yang wajib diimplementasikan oleh kelas turunannya (child class)
    
    /**
     * Metode untuk menghitung total tagihan semester mahasiswa.
     * Logikanya akan berbeda tergantung jenis pembiayaan (Mandiri, Bidikmisi, Prestasi).
     */
    abstract public function hitungTagihanSemester();

    /**
     * Metode untuk menampilkan detail spesifikasi akademik mahasiswa.
     */
    abstract public function tampilkanSpesifikasiAkademik();
}

?>