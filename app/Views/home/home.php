 <?= $this->extend('layouts/home_layout') ?>

<?= $this->section('head') ?>
<title>Home</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<nav class="navbar navbar-expand-lg navbar-light bg-light p-3">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold text-body-emphasis" href="<?= base_url(); ?>" style="font-size: 20px;">E-<span class="text-primary">PERPUSJAR</span></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav" style="font-size: 17px;">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="#beranda">Beranda</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#daftar-buku">Daftar Buku</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#profil">Profil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#contact">Kontak Kami</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#visimisi">Visi Misi</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#alur">Alur Peminjaman</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<div class="px-4 pt-5 my-5 text-center border-bottom" id="beranda">
  <h1 class="display-4 fw-bold text-body-emphasis">E-<span class="text-primary">PERPUSJAR</span></h1>
  <div class="col-lg-6 mx-auto">
    <p class="lead mb-4">Temukan buku-buku menarik untuk memperluas pengetahuan dan imajinasi Anda.</p>
    <p class="lead mb-4">E-PERPUSJAR adalah teman setia pencinta buku dan pembelajar di mana saja, kapan saja dikelola oleh <br>Dinas Perpustakaan dan Kearsipan <br>Provinsi Kalimantan Selatan.</p>
    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mb-5">
      <a href="<?= base_url('login'); ?>" class="btn btn-primary btn-lg px-4 me-sm-3">Login</a>
      <a href="<?= base_url('book'); ?>" class="btn btn-outline-secondary btn-lg px-4">Daftar buku</a>
    </div>
  </div>
  <div class="overflow-hidden" style="max-height: 45vh;">
    <div class="container px-5">
      <img src="<?= base_url('assets/images/dashboard.png'); ?>" class="img-fluid border rounded-3 shadow-lg mb-4" alt="Example image" width="700" height="500" loading="lazy">
    </div>
  </div>
</div>
<div class="px-4 pt-5 my-5 text-center border-bottom" id="daftar-buku">
  <h1 class="display-6 fw-bold text-body-emphasis mb-5">Daftar Buku</h1>
    <div class="row">
      <?php if (empty($books)) : ?>
        <h4 class="text-center">Buku tidak ditemukan</h4>
      <?php endif; ?>
      <?php foreach ($books as $book) : ?>
        <?php
        $coverImageFilePath = BOOK_COVER_URI . $book['book_cover'];
        ?>
        <style>
          #coverBook<?= $book['id']; ?> {
            background-image: url('<?= base_url((!empty($book['book_cover']) && file_exists($coverImageFilePath)) ? $coverImageFilePath : BOOK_COVER_URI . DEFAULT_BOOK_COVER); ?>');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: top;
            height: 250px;
          }
        </style>
        <div class="col-sm-6 col-xl-3">
          <div class="card overflow-hidden rounded-2" style="height: 375px;">
            <div class="position-relative">
              <a href="<?= base_url("books/{$book['slug']}"); ?>">
                <div class="card-img-top rounded-0" id="coverBook<?= $book['id']; ?>">
                </div>
              </a>
            </div>
            <div class="card-body pt-3 p-4">
              <h6 class="fw-semibold fs-4">
                <?= substr($book['title'], 0, 64) . ((strlen($book['title']) > 64) ? '...'  : '') . " ({$book['year']})"; ?>
              </h6>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
      <div class="col-sm-12 text-center ">
        <a href="<?= base_url('book'); ?>" class="btn btn-primary" target="_blank">Lihat Semua Buku</a>
      </div>
    </div>
</div>
<div class="px-4 pt-5 my-5 text-center border-bottom" id="profil">
  <h1 class="display-6 fw-bold text-body-emphasis mb-3">Profil</h1>
  <p>Dinas Perpustakaan dan Kearsipan (DISPERSIP) Provinsi Kalimantan Selatan sebagai Lembaga Teknis Daerah Provinsi Kalimantan Selatan dibentuk dengan Peraturan Daerah Provinsi Kalimantan Selatan Nomor 6 Tahun 2008 Tanggal 15 April 2008 dan diundangkan dalam Lembaran Daerah Provinsi Kalimantan Selatan Tahun 2008 No.6 tanggal 16 April 2008 tentang Pembentukan Organisasi dan Tata Kerja Perangkat Daerah Provinsi Kalimantan Selatan.</p>
  <p>DISPERSIP Provinsi Kalimantan Selatan merupakan hasil integrasi eks Perpustakaan Nasional Provinsi Kalimantan Selatan (Instansi vertikal Perpusnas-RI) dengan Kantor Arsip Daerah Provinsi Kalimantan Selatan.</p>
  <p>DISPERSIP (Dinas Perpustakaan dan Kearsipan) adalah dinas di lingkungan Pemerintah Provinsi Kalimantan Selatan. DISPERSIP merupakan unsur pendukung tugas Gubernur, dipimpin oleh seorang Kepala Dinas yang berkedudukan di bawah dan bertanggungjawab langsung kepada Gubernur Provinsi Kalimantan Selatan melalui Sekretaris Daerah.</p>
  <p>DISPERSIP Provinsi Kalimantan Selatan merupakan Satuan Kerja Perangkat Daerah (SKPD) yang berada di dalam struktur Pemerintah Provinsi Kalimantan Selatan. Eselon IIa dibentuk berdasarkan Peraturan Daerah Nomor 6 Tahun 2008 tentang Pembentukan Organisasi dan Tata Kerja Perangkat Daerah Provinsi Kalimantan Selatan.</p>
</div>
<div class="px-4 pt-5 my-5 text-center border-bottom" id="contact">
  <h1 class="display-6 fw-bold text-body-emphasis mb-5">Kontak Kami</h1>
  <h5><b>Alamat :</b><br> Jl. A. Yani Km. 6,400 No. 6 Banjarmasin<br>Kalimantan Selatan 70249</h5><br>
  
  <h5><b>No Telp :</b><br> 0511-3256155<br>0511-3256154</h5><br>
  
  <h5><b>Email :</b><br> dispersip@kalselprov.go.id<br>ios.kalsel@gmail.com</h5>
</div>
<div class="px-4 pt-5 my-5 border-bottom" id="visimisi">
  <h1 class="display-6 fw-bold text-center text-body-emphasis mb-4">Visi Misi</h1>
  <div class="row">
      <div class="col-lg-6">
          <h1 class="fw-bold text-center text-body-emphasis"><span class="text-primary">Visi</span></h1>
          <p>Pemberdayaan Perpustakaan dalam rangka mencerdaskan masyarakat dan
arsip sebagai bukti pertanggungjawaban kepada generasi penerus. Dari visi ini
ingin dirumuskan cita-cita dan keinginan Dispersip Prov. Kalsel untuk
memberdayakan semua jenis perpustakaan yang ada di Kalimantan Selatan agar
terciptanya masyarakat yang senang belajar dan gemar membaca serta masyarakat
yang sadar akan arti pentingnya selembar arsip untuk dilestarikan dalam semua
19
aspek kehidupan masyarakat sehingga akan terkumpul menjadi bukti sejarah
perjalanan bangsa.</p>
      </div>
      <div class="col-lg-6">
          <h1 class="fw-bold text-center text-body-emphasis"><span class="text-primary">Misi</span></h1>
          <p>Untuk mewujudkan visi tersebut, Misi yang menjadi landasan semangat kerja Dispersip Prov. Kalsel adalah: :<br>
1. Melaksanakan pembinaan, pengembangan, dan pemberdayaan semua jenis
perpustakaan dan arsip.<br>
2. Melaksanakan pelayanan informasi ilmu pengetahuan, teknologi, dan
kebudayaan.<br>
3. Mengumpulkan dan melestarikan hasil budaya bangsa berupa karya cetak,
rekam, dan arsip statis.<br>
4. Meningkatkan budaya baca dan budaya arsip dengan melibatkan semua unsur
masyarakat.<br>
5. Pembentukan jaringan kerjasama perpustakaan, dokumentasi, informasi dan
kearsipan.<br>
6. Mengelola arsip in aktif yang berasal dari dinas, badan, kantor di lingkungan
Pemerintah Provinsi Kalimantan Selatan untuk khasanah daerah.<br>
7. Melaksanakan penyelamatan dan pelestarian arsip statis melalui kegiatan
akuisisi dan alih media.</p>
      </div>
  </div>
</div>
<div class="px-4 pt-5 my-5 text-center border-bottom" id="alur">
  <h1 class="display-6 fw-bold text-body-emphasis mb-3">Alur Peminjaman</h1>
    1. Peminjam Mendaftar Akun Diwebsite atau langsung mengunjungi kantor dispersip kalsel<br>
    2. Peminjam memilih buku yang dipinjam, lalu diverifikasi terlebih dahulu oleh petugas<br>
    3. Peminjam mengetahui kapan jadwal peminjaman dilakukan, dan jadwal pengembalian buku tersebut<br>
    4. Peminjam akan mendapat notifikasi Whatsapp H-1 ketika jadwal pengembalian<br>
    5. Peminjam mengembalikan buku yang dipinjam lalu diverifikasi kembali oleh Petugas<br>
    6. Peminjam bisa meminjam lagi buku yang lain, jika tidak maka peminjam bisa kembali pulang.
</div>
<?= $this->endSection() ?>