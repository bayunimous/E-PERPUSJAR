<?= $this->include('imports/styles/styles') ?>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="shortcut icon" type="image/png" href="<?= base_url('assets/images/logos/favicon.png') ?>" />

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<style>
.rating {
  display: flex;
  flex-direction: row;
  cursor: pointer;
}
.stars {
  display: flex;
}
.stars i {
  font-size: 24px;
  color: #ccc;
  margin-right: 5px;
}
.stars i.active {
  color: #f39c12;
}
</style>