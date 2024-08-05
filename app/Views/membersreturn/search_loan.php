<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('head') ?>
<title>Pengembalian Baru</title>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<a href="<?= base_url('membersreturn'); ?>" class="btn btn-outline-primary mb-3">
  <i class="ti ti-arrow-left"></i>
  Kembali
</a>

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
    <div class="row">
      <div class="col-12 col-md-6">
        <h5 class="card-title fw-semibold mb-4">Pilih Buku</h5>
        <div class="mb-3">
          <label for="search" class="form-label">Cari Judul buku</label>
          <input type="text" class="form-control" id="search" name="search" placeholder="Cari buku">
          <div class="invalid-feedback">
          </div>
        </div>
        <button class="btn btn-primary" onclick="getLoan(document.querySelector('#search').value)">Cari</button>
      </div>
      <div class="col-12 col-md-6">
        <h5 class="card-title fw-semibold mb-4">Data Anggota</h5>
        <div class="w-100 mb-4">
          <?php

          use CodeIgniter\I18n\Time;

          $tableData = [
            'Nama Lengkap'  => [$member['first_name'] . ' ' . $member['last_name']],
            'Email'         => $member['email'],
            'Nomor telepon' => $member['phone'],
            'Alamat'        => $member['address'],
            'Tanggal lahir' => Time::parse($member['date_of_birth'], locale: 'id')->toLocalizedString('d MMMM Y'),
            'Jenis kelamin' => $member['gender'] == 'Male' ? 'Laki-laki' : 'Perempuan',
          ];
          ?>
          <table>
            <?php foreach ($tableData as $key => $value) : ?>
              <?php if (is_array($value)) : ?>
                <tr>
                  <td>
                    <h6 class="text-black-50"><b><?= $key; ?></b></h6>
                  </td>
                  <td style="width:15px" class="text-center">
                    <h6 class="text-black-50"><b>:</b></h6>
                  </td>
                  <td>
                    <h6 class="text-black-50"><b><?= $value[0]; ?></b></h6>
                  </td>
                </tr>
              <?php else : ?>
                <tr>
                  <td>
                    <h6 class="text-black-50"><?= $key; ?></h6>
                  </td>
                  <td class="text-center">
                    <h6 class="text-black-50">:</h6>
                  </td>
                  <td>
                    <h6 class="text-black-50"><?= $value; ?></h6>
                  </td>
                </tr>
              <?php endif; ?>
            <?php endforeach; ?>
          </table>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div id="loanResult">
          <p class="text-center mt-4">Data peminjaman muncul disini</p>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url("assets/libs/html5-qrcode/html5-qrcode.min.js") ?>"></script>
<script>
  function getLoan(param) {
    // console.log(param);

    jQuery.ajax({
      url: "<?= base_url('membersreturn/new/search'); ?>",
      type: 'get',
      data: {
        'param': param
      },
      success: function(response, status, xhr) {
        $('#loanResult').html(response);

        $('html, body').animate({
          scrollTop: $("#loanResult").offset().top
        }, 500);
      },
      error: function(xhr, status, thrown) {
        console.log(thrown);
        $('#loanResult').html(thrown);
      }
    });
  }

  const html5QrcodeScanner = new Html5QrcodeScanner(
    "reader", {
      formatsToSupport: [Html5QrcodeSupportedFormats.QR_CODE]
    }, {
      fps: 30,
      qrbox: {
        width: 250,
        height: 250
      }
    },
    /* verbose= */
    false
  );

  function onScanSuccess(decodedText, decodedResult) {
    // handle the scanned code as you like, for example:
    console.log(`Code matched = ${decodedText}`, decodedResult);

    html5QrcodeScanner.pause(true);

    // show resume button
    document.querySelector('#resumeBtn').style.display = 'block';

    getLoan(decodedText);
  }

  function onScanFailure(error) {
    // handle scan failure, usually better to ignore and keep scanning.
    // for example:
    // console.warn(`Code scan error = ${error}`);
  }

  html5QrcodeScanner.render(onScanSuccess, onScanFailure);

  setTimeout(() => {
    const startBtn = document.querySelector('#html5-qrcode-button-camera-start');
    const stopBtn = document.querySelector('#html5-qrcode-button-camera-stop');
    const fileBtn = document.querySelector('#html5-qrcode-button-file-selection');

    startBtn.classList.add('btn', 'btn-primary', 'mb-2');
    stopBtn.classList.add('btn', 'btn-primary', 'mb-2');
    fileBtn.classList.add('btn', 'btn-primary', 'mb-2');
  }, 3000);
</script>
<?= $this->endSection() ?>