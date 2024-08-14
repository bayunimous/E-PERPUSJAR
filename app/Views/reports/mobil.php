<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Data Mobil</title>
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
        <h5 class="card-title fw-semibold mb-4">Data Mobil</h5>
      </div>
      <div class="col-12 col-lg-7">
        <div class="d-flex gap-2 justify-content-md-end">
          <div>
            <form action="" method="get">
              <div class="row mb-3">
                <div class="col-md-12">
                  <input type="text" class="form-control" name="search" value="<?= $search ?? ''; ?>" placeholder="Cari mobil" aria-label="Cari mobil" aria-describedby="searchButton">
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-md-6 mb-3">
                  <label for="add_date_from">Tanggal Ditambah (Dari):</label>
                  <input type="date" class="form-control" id="add_date_from" name="add_date_from" value="<?= old('add_date_from', $addDateFrom) ?>">
                </div>
                <div class="col-md-6 mb-3">
                  <label for="add_date_to">Tanggal Ditambah (Sampai):</label>
                  <input type="date" class="form-control" id="add_date_to" name="add_date_to" value="<?= old('add_date_to', $addDateTo) ?>">
                </div>
                <div class="col-md-6 d-grid">
                  <button class="btn btn-outline-secondary" type="submit" id="searchButton">Cari</button>
                </div>
                <div class="col-md-6 d-grid">
                  <a href="<?= base_url('reports/report_mobil') . '?add_date_from=' . $addDateFrom . '&add_date_to=' . $addDateTo; ?>" class="btn btn-primary py-2">
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
    <div class="table-responsive">
    <table class="table table-hover table-striped">
      <thead class="table-light">
        <tr>
          <th scope="col">No</th>
          <th scope="col">Merk</th>
          <th scope="col">Model</th>
          <th scope="col">Tahun</th>
          <th scope="col">Warna</th>
          <th scope="col">Harga</th>
          <th scope="col">Tanggal ditambah</th>
        </tr>
      </thead>
      <tbody class="table-group-divider">
        <?php $i = 1 + ($itemPerPage * ($currentPage - 1)) ?>
        <?php if (empty($mobil)) : ?>
          <tr>
            <td class="text-center" colspan="8"><b>Tidak ada data</b></td>
          </tr>
        <?php endif; ?>
        <?php foreach ($mobil as $key => $mobils) : ?>
          <tr>
            <th scope="row"><?= $i++; ?></th>
            <td>
              <b><?= $mobils['merk']; ?></b>
            </td>
            <td>
              <?= $mobils['model']; ?>
            </td>
            <td>
              <?= $mobils['tahun']; ?>
            </td>
            <td>
              <?= $mobils['warna']; ?>
            </td>
            <td>
              <?= $mobils['harga']; ?>
            </td>
            <td>
              <?= $mobils['created_at']; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?= $pager->links('mobil', 'my_pager'); ?>
    </div>
  </div>
</div>
<?= $this->endSection() ?>