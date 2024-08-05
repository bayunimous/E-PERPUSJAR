<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Data Cetak Pengguna</title>
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
    <div class="row mb-2">
      <div class="col-12 col-lg-5">
        <h5 class="card-title fw-semibold mb-4">Data Cetak Pengguna</h5>
      </div>
      <div class="col-12 col-lg-7">
        <div class="d-flex gap-2 justify-content-md-end">
          <div>
            <form action="" method="get">
              <div class="row mb-3">
                <div class="col-md-12">
                  <input type="text" class="form-control" name="search" value="<?= $search ?? ''; ?>" placeholder="Cari cetak pengguna" aria-label="Cari cetak pengguna" aria-describedby="searchButton">
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-6 mb-3">
                  <label for="add_date_from">Tanggal Dibuat (Dari):</label>
                  <input type="date" class="form-control" id="add_date_from" name="add_date_from" value="<?= old('add_date_from', $addDateFrom) ?>">
                </div>
                <div class="col-md-6 mb-3">
                  <label for="add_date_to">Tanggal Dibuat (Sampai):</label>
                  <input type="date" class="form-control" id="add_date_to" name="add_date_to" value="<?= old('add_date_to', $addDateTo) ?>">
                </div>
                <div class="col-md-6 d-grid">
                  <button class="btn btn-outline-secondary" type="submit" id="searchButton">Cari</button>
                </div>
                <div class="col-md-6 d-grid">
                  <a href="<?= base_url('reports/print_users') . '?add_date_from=' . $addDateFrom . '&add_date_to=' . $addDateTo; ?>" class="btn btn-primary py-2">
                    <i class="ti ti-print"></i>
                    Cetak
                  </a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <table class="table table-hover table-striped">
      <thead class="table-light">
        <tr>
          <th scope="col">#</th>
          <th scope="col">NIP</th>
          <th scope="col">Nama lengkap</th>
          <th scope="col">Email</th>
          <th scope="col">Phone</th>
          <th scope="col">Username</th>
          <th scope="col">Tanggal dibuat</th>
          <th scope="col" class="text-center">Role</th>
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
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?= $pager->links('users', 'my_pager'); ?>
  </div>
</div>
<?= $this->endSection() ?>