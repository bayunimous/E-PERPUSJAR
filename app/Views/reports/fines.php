<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Data Cetak Denda</title>
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
        <h5 class="card-title fw-semibold mb-4">Data Cetak Denda</h5>
      </div>
      <div class="col-12 col-lg-7">
        <div class="d-flex gap-2 justify-content-md-end">
          <div>
            <form action="" method="get">
              <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" value="<?= $search ?? ''; ?>" placeholder="Cari cetak denda" aria-label="Cari cetak denda" aria-describedby="searchButton">
                <button class="btn btn-outline-secondary" type="submit" id="searchButton">Cari</button>
              </div>
            </form>
          </div>
          <div>
            <a href="<?= base_url('reports/report_fines') . '?search=' . $keyword; ?>" class="btn btn-primary py-2">
                <i class="ti ti-print"></i>
                Cetak
            </a>
          </div>
        </div>
      </div>
    </div>
    <table class="table table-hover table-striped">
      <thead class="table-light">
        <tr>
          <th scope="col">No</th>
          <th scope="col">Nama Peminjam</th>
          <th scope="col">Judul Buku</th>
          <th scope="col">Denda Dibayar</th>
          <th scope="col">Jumlah Denda</th>
        </tr>
      </thead>
      <tbody class="table-group-divider">
        <?php $i = 1 + ($itemPerPage * ($currentPage - 1)) ?>
        <?php if (empty($fines)) : ?>
          <tr>
            <td class="text-center" colspan="7"><b>Tidak ada data</b></td>
          </tr>
        <?php endif; ?>
        <?php foreach ($fines as $key => $fine) : ?>
          <tr>
            <th scope="row"><?= $i++; ?></th>
            <td><?= $fine['first_name'] . ' ' . $fine['last_name']; ?></td>
            <td><?= $fine['title']; ?></td>
            <td>Rp<?= $fine['amount_paid'] ?? 0 ?></td>
            <td>Rp<?= $fine['fine_amount'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?= $pager->links('loans', 'my_pager'); ?>
  </div>
</div>
<?= $this->endSection() ?>