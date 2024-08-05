<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Detail Pengembalian</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
  #qr-code {
    background-image: url(<?= base_url(LOANS_QR_CODE_URI . $loan['loan_qr_code']); ?>);
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
    max-width: 500px;
    height: 300px;
  }
</style>
<?php

use CodeIgniter\I18n\Time;

$now = Time::now(locale: 'id');
$loanDate = Time::parse($loan['loan_date'], locale: 'id');
$dueDate = Time::parse($loan['due_date'], locale: 'id');
$returnDate = Time::parse($loan['return_date'], locale: 'id');

if (session()->getFlashdata('msg')) : ?>
  <div class="pb-2">
    <div class="alert <?= (session()->getFlashdata('error') ?? false) ? 'alert-danger' : 'alert-success'; ?> alert-dismissible fade show" role="alert">
      <?= session()->getFlashdata('msg') ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  </div>
<?php endif; ?>
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-4">
      <div>
        <a href="<?= previous_url() ?>" class="btn btn-outline-primary">
          <i class="ti ti-arrow-left"></i>
          Kembali
        </a>
      </div>
    </div>
    <h5 class="card-title fw-semibold mb-4">Detail Pengembalian</h5>
    <?php
    $memberData = [
      'Nama Lengkap'  => [$loan['first_name'] . ' ' . $loan['last_name']],
      'Email'         => $loan['email'],
      'Nomor telepon' => $loan['phone'],
      'Alamat'        => $loan['address'],
    ];

    $bookData = [
      'Judul buku'    => [$loan['title']],
      'Pengarang'     => $loan['author'],
      'Penerbit'      => $loan['publisher'],
      'Rak'           => $loan['rack'],
    ];
    ?>
    <div class="row mb-3">
      <!-- member data -->
      <div class="col-12 col-md-6 d-flex flex-wrap">
        <div class="mb-4">
          <table>
            <?php foreach ($memberData as $key => $value) : ?>
              <?php if (is_array($value)) : ?>
                <tr>
                  <td>
                    <h5><b><?= $key; ?></b></h5>
                  </td>
                  <td style="width:15px" class="text-center">
                    <h5><b>:</b></h5>
                  </td>
                  <td>
                    <h5><b><?= $value[0]; ?></b></h5>
                  </td>
                </tr>
              <?php else : ?>
                <tr>
                  <td>
                    <h5><?= $key; ?></h5>
                  </td>
                  <td class="text-center">
                    <h5>:</h5>
                  </td>
                  <td>
                    <h5><?= $value; ?></h5>
                  </td>
                </tr>
              <?php endif; ?>
            <?php endforeach; ?>
          </table>
        </div>
      </div>
      <!-- book data -->
      <div class="col-12 col-md-6 d-flex flex-wrap">
        <div class="mb-4">
          <table>
            <?php foreach ($bookData as $key => $value) : ?>
              <?php if (is_array($value)) : ?>
                <tr>
                  <td>
                    <h5><b><?= $key; ?></b></h5>
                  </td>
                  <td style="width:15px" class="text-center">
                    <h5><b>:</b></h5>
                  </td>
                  <td>
                    <h5><b><?= $value[0]; ?></b></h5>
                  </td>
                </tr>
              <?php else : ?>
                <tr>
                  <td>
                    <h5><?= $key; ?></h5>
                  </td>
                  <td class="text-center">
                    <h5>:</h5>
                  </td>
                  <td>
                    <h5><?= $value; ?></h5>
                  </td>
                </tr>
              <?php endif; ?>
            <?php endforeach; ?>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-12 col-lg-<?php echo ($loan['loan_status_return'] == 'Setuju') ? '8' : '12'; ?>">
    <div class="row">
      <?php if ($loan['loan_status_return'] == 'Setuju') : ?>
      <!-- quantity -->
      <div class="col-12 col-sm-6">
        <div class="card" style="height: 180px;">
          <div class="card-body">
            <h2>
              <i class="ti ti-book"></i>
            </h2>
            <h5>Buku dipinjam: </h5>
            <h4>
              <?= $loan['quantity']; ?>
            </h4>
          </div>
        </div>
      </div>
      <!-- loan date -->
      <div class="col-12 col-sm-6">
        <div class="card" style="height: 180px;">
          <div class="card-body">
            <h2>
              <i class="ti ti-calendar-check"></i>
            </h2>
            <h5>Waktu pinjam: </h5>
            <h4>
              <?= $loanDate->toLocalizedString('d MMMM y'); ?><br>
              <?= $loanDate->toLocalizedString('HH:mm:ss'); ?>
            </h4>
          </div>
        </div>
      </div>
      <!-- due date -->
      <div class="col-12 col-sm-6">
        <div class="card" style="height: 180px;">
          <div class="card-body">
            <h2>
              <i class="ti ti-calendar-due"></i>
            </h2>
            <h5>Batas waktu pengembalian: </h5>
            <h4>
              <?= $dueDate->toLocalizedString('d MMMM y'); ?><br>
              <?= $dueDate->addDays(1)->subSeconds(1)->toTimeString(); ?>
            </h4>
          </div>
        </div>
      </div>
      <!-- return date -->
      <div class="col-12 col-sm-6">
        <div class="card" style="height: 180px;">
          <div class="card-body">
            <h2>
              <i class="ti ti-calendar-check"></i>
            </h2>
            <h5>Tanggal pengembalian: </h5>
            <h4>
              <?= $returnDate->toLocalizedString('d MMMM y'); ?><br>
              <?= $loanDate->toLocalizedString('HH:mm:ss'); ?>
            </h4>
          </div>
        </div>
      </div>
      <?php else : ?>
      <!-- quantity -->
      <div class="col-12 col-sm-6">
        <div class="card" style="height: 180px;">
          <div class="card-body">
            <h2>
              <i class="ti ti-book"></i>
            </h2>
            <h5>Buku dipinjam: </h5>
            <h4>
              <?= $loan['quantity']; ?>
            </h4>
          </div>
        </div>
      </div>
      <!-- status -->
      <div class="col-12 col-sm-6">
        <div class="card" style="height: 180px;">
          <div class="card-body">
            <h2>
              <i class="ti ti-calendar-check"></i>
            </h2>
            <h5>Status: </h5>
            <?php if ($loan['loan_status_return'] == 'Setuju') : ?>
              <span class="badge bg-success rounded-3">
                <h5 class="fw-semibold mb-0">Setuju</h5>
              </span>
            <?php elseif ($loan['loan_status_return'] == 'Proses') : ?>
              <span class="badge bg-warning rounded-3">
                <h5 class="fw-semibold mb-0">Proses</h5>
              </span>
            <?php else : ?>
              <span class="badge bg-danger rounded-3">
                <h5 class="fw-semibold mb-0">Tolak</h5>
              </span>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
  <?php if ($loan['loan_status_return'] == 'Setuju') : ?>
  <!-- fines -->
  <div class="col-12 col-lg-4">
    <div class="card">
      <div class="card-body">
        <?php
        $isFined = $loan['loan_id'] != null;
        $isFinePaid =  $isFined ? ($loan['paid_at'] ? true : false) : true;
        ?>
        <h5>
          Terlambat : <?= $returnDate->isAfter($dueDate) ? abs($returnDate->difference($dueDate)->getDays()) . ' Hari' : '-'; ?>
        </h5>
        <h5>Total denda : Rp<?= $loan['fine_amount'] ?? 0; ?></h5>
        <h5>Telah dibayar : Rp<?= $loan['amount_paid'] ?? 0; ?></h5>
        <h5>Sisa bayar : Rp<?= $loan['fine_amount'] - $loan['amount_paid']; ?></h5>
        <h5 class="d-inline">Status : </h5>
        <?php if ($isFinePaid) : ?>
          <span class="badge bg-success rounded-3">
            <h5 class="fw-semibold mb-0"><?= $isFined ? 'Lunas' : 'Selesai'; ?></h5>
          </span>
        <?php else : ?>
          <span class="badge bg-danger rounded-3">
            <h5 class="fw-semibold mb-0">Menunggak</h5>
          </span>
        <?php endif; ?>
        <?php if ($isFined && !$isFinePaid) : ?>
          <a href="<?= base_url("fines/pay/{$loan['uid']}"); ?>" class="btn btn-warning mt-3 w-100">
            Bayar denda
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>
<?= $this->endSection() ?>