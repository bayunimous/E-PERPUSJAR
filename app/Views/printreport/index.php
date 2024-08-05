<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Laporan Yang Dicetak</title>
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
      <h5 class="card-title fw-semibold mb-4">Data Laporan Yang Dicetak</h5>
      <div class="col-12 col-lg-7">
        <div class="d-flex gap-2 justify-content-md-end">
          <div>
            <form action="" method="get">
              <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" value="<?= $search ?? ''; ?>" placeholder="Cari laporan yang dicetak" aria-label="Cari laporan yang dicetak" aria-describedby="searchButton">
                <button class="btn btn-outline-secondary" type="submit" id="searchButton">Cari</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <table class="table table-hover table-striped">
      <thead class="table-light">
        <tr>
          <th scope="col">No</th>
          <th scope="col">Nama lengkap</th>
          <th scope="col">Deskripsi</th>
          <th scope="col">Tanggal dicetak</th>
          <th scope="col" class="text-center">Role</th>
        </tr>
      </thead>
      <tbody class="table-group-divider">
        <?php $i = 1 + ($itemPerPage * ($currentPage - 1)) ?>
        <?php if (empty($reports)) : ?>
          <tr>
            <td class="text-center" colspan="5"><b>Tidak ada data</b></td>
          </tr>
        <?php endif; ?>
        <?php foreach ($reports as $key => $report) : ?>
          <tr>
            <th scope="row"><?= $i++; ?></th>
            <td>
              <b><?= $report['full_name']; ?></b>
            </td>
            <td>
              <?= $report['description']; ?>
            </td>
            <td>
              <?= $report['created_at']; ?>
            </td>
            <td class="text-center">
              <?php if ($report['role'] === 'Administrator') : ?>
                <span class="badge bg-success rounded-3 fw-semibold text-black"><?= $report['role']; ?></span>
              <?php elseif ($report['role'] === 'Petugas') : ?>
                <span class="badge bg-primary rounded-3 fw-semibold"><?= $report['role']; ?></span>
              <?php elseif ($report['role'] === 'Kepala Dinas') : ?>
                <span class="badge bg-info rounded-3 fw-semibold"><?= $report['role']; ?></span>
              <?php else : ?>
                <span class="badge bg-black rounded-3 fw-semibold"><?= $report['role']; ?></span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?= $pager->links('reports', 'my_pager'); ?>
  </div>
</div>
<?= $this->endSection() ?>