<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Ubah Data Penggunaan Fasilitas</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= previous_url() ?>" class="btn btn-outline-primary mb-3">
  <i class="ti ti-arrow-left"></i>
  Kembali
</a>

<?php if (session()->getFlashdata('msg')) : ?>
  <div class="pb-2">
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('msg') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
<?php endif; ?>

<div class="card">
  <div class="card-body">
    <h5 class="card-title fw-semibold">Edit Data Penggunaan Fasilitas</h5>
    <form action="<?= base_url('facilitys/' . $facilitys['id']); ?>" method="post">
      <?= csrf_field(); ?>
      <input type="hidden" name="_method" value="PUT">
      <div class="my-3">
        <label for="title" class="form-label">Fasilitas</label>
        <input type="text" class="form-control <?php if ($validation->hasError('title')) : ?>is-invalid<?php endif ?>" id="title" name="title" value="<?= $oldInput['title'] ?? $facilitys['title']; ?>" placeholder="AC, Komputer, Meja" required>
        <div class="invalid-feedback">
          <?= $validation->getError('title'); ?>
        </div>
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Deskripsi</label>
        <textarea class="form-control <?php if ($validation->hasError('description')) : ?>is-invalid<?php endif ?>" id="description" name="description" placeholder="AC Mengalami kerusakan biaya yang dikeluarkan 200rb" rows="5" required><?= $oldInput['description'] ?? $facilitys['description']; ?></textarea>
        <div class="invalid-feedback">
          <?= $validation->getError('description'); ?>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
  </div>
</div>
<?= $this->endSection() ?>