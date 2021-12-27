const flashdata = $('.flash-data').data('flashdata');
let statusMessage = $('.flash-data').data('status');

if (flashdata) {
  if (statusMessage == 'success') {
    Swal.fire(
      'Sukses!',
      flashdata,
      'success'
    );
  } else if (statusMessage == 'failed') {
    Swal.fire(
      'Gagal!',
      flashdata,
      'error'
    );
  }
}