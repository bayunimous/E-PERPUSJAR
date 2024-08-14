<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Mobil Baru</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= base_url('mobil'); ?>" class="btn btn-outline-primary mb-3">
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

        <?php if (session()->getFlashdata('errors')): ?>
          <div class="pb-2">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <ul>
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                  <li>- <?= esc($error) ?></li>
                <?php endforeach; ?>
              </ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          </div>
        <?php endif; ?>

<div class="card">
  <div class="card-body">
    <h5 class="card-title fw-semibold">Form Mobil Baru</h5>
    <form action="<?= base_url('mobil'); ?>" method="post">
      <?= csrf_field(); ?>
      <div class="row mt-3">
        <div class="col-12 col-md-6 mb-3">
          <label for="merk" class="form-label">Merk</label>
          <input type="text" class="form-control <?php if ($validation->hasError('merk')) : ?>is-invalid<?php endif ?>" id="merk" name="merk" value="<?= $oldInput['merk'] ?? ''; ?>" placeholder="Avanza,Xenia" required>
          <div class="invalid-feedback">
            <?= $validation->getError('merk'); ?>
          </div>
        </div>
        <div class="col-12 col-md-6 mb-3">
          <label for="model" class="form-label">Model</label>
          <input type="text" class="form-control <?php if ($validation->hasError('model')) : ?>is-invalid<?php endif ?>" id="model" name="model" value="<?= $oldInput['model'] ?? ''; ?>" placeholder="SUV, Hatchback, Sedan.." required>
          <div class="invalid-feedback">
            <?= $validation->getError('model'); ?>
          </div>
        </div>
      </div>
      <div class="mb-3">
        <label for="tahun" class="form-label">Tahun</label>
        <input type="text" class="form-control <?php if ($validation->hasError('tahun')) : ?>is-invalid<?php endif ?>" id="tahun" name="tahun" value="<?= $oldInput['tahun'] ?? ''; ?>" placeholder="2020, 2021, 2022.." required>
        <div class="invalid-feedback">
          <?= $validation->getError('tahun'); ?>
        </div>
      </div>
      <div class="mb-3">
        <label for="warna" class="form-label">Warna</label>
        <input type="text" class="form-control <?php if ($validation->hasError('warna')) : ?>is-invalid<?php endif ?>" id="warna" name="warna" value="<?= $oldInput['warna'] ?? ''; ?>" placeholder="Hitam, Putih.." required>
        <div class="invalid-feedback">
          <?= $validation->getError('warna'); ?>
        </div>
      </div>
      <div class="row mt-3">
        <div class="col-12 col-md-6 mb-3">
          <label for="harga" class="form-label">Harga</label>
          <input type="text" class="form-control <?php if ($validation->hasError('harga')) : ?>is-invalid<?php endif ?>" id="harga" name="harga" value="<?= $oldInput['harga'] ?? ''; ?>" placeholder="150.000.000 .." required>
          <div class="invalid-feedback">
            <?= $validation->getError('harga'); ?>
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-primary mt-2">Simpan</button>
    </form>
  </div>
</div>
<?= $this->endSection() ?>