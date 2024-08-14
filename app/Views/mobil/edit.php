<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Ubah Data Mobil</title>
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
    <h5 class="card-title fw-semibold">Edit Data Mobil</h5>
    <form action="<?= base_url('mobil/' . $mobil['id']); ?>" method="post">
      <?= csrf_field(); ?>
      <input type="hidden" name="_method" value="PUT">
      <div class="row">
        <div class="col-12 col-md-6">
          <div class="my-3">
            <label for="merk" class="form-label">Merk</label>
            <input type="text" class="form-control <?php if ($validation->hasError('merk')) : ?>is-invalid<?php endif ?>" id="merk" name="merk" value="<?= $oldInput['merk'] ?? $mobil['merk']; ?>" placeholder="Avanza, Xenia .." required>
            <div class="invalid-feedback">
              <?= $validation->getError('merk'); ?>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="my-3">
            <label for="model" class="form-label">Model</label>
            <input type="text" class="form-control <?php if ($validation->hasError('model')) : ?>is-invalid<?php endif ?>" id="model" name="model" value="<?= $oldInput['model'] ?? $mobil['model']; ?>" placeholder="SUV, Hatchback, Sedan .." required>
            <div class="invalid-feedback">
              <?= $validation->getError('model'); ?>
            </div>
          </div>
        </div>
      </div>
      <div class="mb-3">
        <label for="tahun" class="form-label">Tahun</label>
        <input type="text" class="form-control <?php if ($validation->hasError('tahun')) : ?>is-invalid<?php endif ?>" id="tahun" name="tahun" value="<?= $oldInput['tahun'] ?? $mobil['tahun']; ?>" placeholder="2020.." required>
        <div class="invalid-feedback">
          <?= $validation->getError('tahun'); ?>
        </div>
      </div>
      <div class="mb-3">
        <label for="warna" class="form-label">Warna</label>
        <input type="text" class="form-control <?php if ($validation->hasError('warna')) : ?>is-invalid<?php endif ?>" id="warna" name="warna" value="<?= $oldInput['warna'] ?? $mobil['warna']; ?>" placeholder="Hitam .." required>
        <div class="invalid-feedback">
          <?= $validation->getError('warna'); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-md-6">
          <div class="my-3">
            <label for="harga" class="form-label">Harga</label>
            <input type="text" class="form-control <?php if ($validation->hasError('harga')) : ?>is-invalid<?php endif ?>" id="harga" name="harga" value="<?= $oldInput['harga'] ?? $mobil['harga']; ?>" placeholder="400.000.000 .." required>
            <div class="invalid-feedback">
              <?= $validation->getError('harga'); ?>
            </div>
          </div>
        </div>

      <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
  </div>
</div>
<?= $this->endSection() ?>