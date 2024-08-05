<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Data Pengguna</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php if (session()->getFlashdata('msg')) : ?>
  <div class="pb-2">
    <div class="alert <?= (session()->getFlashdata('error') ?? false) ? 'alert-danger' : 'alert-success'; ?> alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('msg') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
<?php endif; ?>

<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-2">
      <h5 class="card-title fw-semibold mb-4">Data Pengguna</h5>
      <div>
        <?php if ($user['role'] !== 'Kepala Dinas') : ?>
        <a href="<?= base_url('users/new'); ?>" class="btn btn-primary">
          <i class="ti ti-plus"></i>
          Tambah Pengguna Baru
        </a>
        <?php endif; ?>
      </div>
    </div>
    <div class="table-responsive">
    <table class="table table-hover table-striped">
      <thead class="table-light">
        <tr>
          <th scope="col">No</th>
          <th scope="col">NIP</th>
          <th scope="col">Nama lengkap</th>
          <th scope="col">Email</th>
          <th scope="col">Phone</th>
          <th scope="col">Username</th>
          <th scope="col">Tanggal dibuat</th>
          <th scope="col" class="text-center">Role</th>
          <?php if ($user['role'] !== 'Kepala Dinas') : ?>
          <th scope="col" class="text-center">Aksi</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody class="table-group-divider">
        <?php $i = 1 + ($itemPerPage * ($currentPage - 1)) ?>
        <?php if (empty($users)) : ?>
          <tr>
            <td class="text-center" colspan="7"><b>Tidak ada data</b></td>
          </tr>
        <?php endif; ?>
        <?php foreach ($users as $key => $userr) : ?>
          <tr>
            <th scope="row"><?= $i++; ?></th>
            <td>
              <b><?= $userr['nip']; ?></b>
            </td>
            <td>
              <?= $userr['full_name']; ?>
            </td>
            <td>
              <?= $userr['email']; ?>
            </td>
            <td>
              <?= $userr['phone']; ?>
            </td>
            <td>
              <?= $userr['username']; ?>
            </td>
            <td>
              <?= $userr['created_at']; ?>
            </td>
            <td class="text-center">
              <?php if ($userr['role'] === 'Administrator') : ?>
                <span class="badge bg-success rounded-3 fw-semibold text-black"><?= $userr['role']; ?></span>
              <?php elseif ($userr['role'] === 'Petugas') : ?>
                <span class="badge bg-primary rounded-3 fw-semibold"><?= $userr['role']; ?></span>
              <?php elseif ($userr['role'] === 'Kepala Dinas') : ?>
                <span class="badge bg-info rounded-3 fw-semibold"><?= $userr['role']; ?></span>
              <?php else : ?>
                <span class="badge bg-black rounded-3 fw-semibold"><?= $userr['role']; ?></span>
              <?php endif; ?>
            </td>
            <?php if ($user['role'] !== 'Kepala Dinas') : ?>
            <td>
              <div class="d-flex justify-content-center gap-2">
                <a href="<?= base_url("users/{$userr['id']}/edit"); ?>" class="btn btn-primary mb-2">
                  <i class="ti ti-edit"></i>
                  Edit
                </a>
                <form action="<?= base_url("users/{$userr['id']}"); ?>" method="post">
                  <?= csrf_field(); ?>
                  <input type="hidden" name="_method" value="DELETE">
                  <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?');">
                    <i class="ti ti-trash"></i>
                    Delete
                  </button>
                </form>
              </div>
            </td>
            <?php endif; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?= $pager->links('users', 'my_pager'); ?>
    </div>
  </div>
</div>
<?= $this->endSection() ?>