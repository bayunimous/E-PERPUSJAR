<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Ubah Data Pengguna</title>
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
    <h5 class="card-title fw-semibold">Edit Data Pengguna</h5>
    <form action="<?= base_url('users/' . $users['id']); ?>" method="post">
      <?= csrf_field(); ?>
      <input type="hidden" name="_method" value="PUT">
      <div class="row">
        <div class="col-12 col-md-6">
          <div class="my-3">
            <label for="nip" class="form-label">NIP</label>
            <input type="number" class="form-control <?php if ($validation->hasError('nip')) : ?>is-invalid<?php endif ?>" id="nip" name="nip" value="<?= $oldInput['nip'] ?? $users['nip']; ?>" placeholder="1234567890" required>
            <div class="invalid-feedback">
              <?= $validation->getError('nip'); ?>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="my-3">
            <label for="email" class="form-label">Email</label>
            <input type="text" class="form-control <?php if ($validation->hasError('email')) : ?>is-invalid<?php endif ?>" id="email" name="email" value="<?= $oldInput['email'] ?? $users['email']; ?>" placeholder="johndoe@gmail.com" required>
            <div class="invalid-feedback">
              <?= $validation->getError('email'); ?>
            </div>
          </div>
        </div>
      </div>
      <div class="mb-3">
        <label for="phone" class="form-label">Nomor telepon</label>
        <input type="text" class="form-control <?php if ($validation->hasError('phone')) : ?>is-invalid<?php endif ?>" id="phone" name="phone" value="<?= $oldInput['phone'] ?? $users['phone']; ?>" placeholder="+628912345" required>
        <div class="invalid-feedback">
          <?= $validation->getError('phone'); ?>
        </div>
      </div>
      <div class="mb-3">
        <label for="full_name" class="form-label">Full Name</label>
        <input type="text" class="form-control <?php if ($validation->hasError('full_name')) : ?>is-invalid<?php endif ?>" id="full_name" name="full_name" value="<?= $oldInput['full_name'] ?? $users['full_name']; ?>" placeholder="John Doe" required>
        <div class="invalid-feedback">
          <?= $validation->getError('full_name'); ?>
        </div>
      </div>
      <div class="row">
        <div class="col-12 col-md-6">
          <div class="my-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control <?php if ($validation->hasError('username')) : ?>is-invalid<?php endif ?>" id="username" name="username" value="<?= $oldInput['username'] ?? $users['username']; ?>" placeholder="johndoe" required>
            <div class="invalid-feedback">
              <?= $validation->getError('username'); ?>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="my-3">
            <label for="password" class="form-label">Password baru</label>
            <input type="password" class="form-control <?php if ($validation->hasError('password')) : ?>is-invalid<?php endif ?>" id="password" name="password" value="<?= $oldInput['password'] ?? ''; ?>" placeholder="password">
            <div class="invalid-feedback">
              <?= $validation->getError('password'); ?>
            </div>
          </div>
        </div>
      </div>
      <div class="mb-3">
        <label for="role" class="form-label">Role</label>
        <select class="form-select <?php if ($validation->hasError('role')) : ?>is-invalid<?php endif ?>" id="role" name="role" required>
          <option selected disabled>- Pilih Role -</option>
          <option value="Administrator"<?= ($users['role'] == 'Administrator') ? ' selected' : ''; ?>>Administrator</option>
          <option value="Petugas"<?= ($users['role'] == 'Petugas') ? ' selected' : ''; ?>>Petugas</option>
          <option value="Kepala Dinas"<?= ($users['role'] == 'Kepala Dinas') ? ' selected' : ''; ?>>Kepala Dinas</option>
        </select>
        <div class="invalid-feedback">
          <?= $validation->getError('role'); ?>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
  </div>
</div>
<?= $this->endSection() ?>