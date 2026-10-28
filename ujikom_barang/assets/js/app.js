// Isi data barang ke modal konfirmasi hapus
var modalHapus = document.getElementById('modalHapus');

if (modalHapus) {
    modalHapus.addEventListener('show.bs.modal', function (event) {
        var tombol = event.relatedTarget;
        modalHapus.querySelector('input[name="id"]').value = tombol.getAttribute('data-id');
        modalHapus.querySelector('.nama-barang').textContent = tombol.getAttribute('data-nama');
    });
}
