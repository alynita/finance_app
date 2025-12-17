@component('mail::message')
# Notifikasi Pengajuan

{{ $pesan }}

@component('mail::button', ['url' => $url])
Lihat Pengajuan
@endcomponent

Terima kasih,<br>
Sistem Pengajuan Barang
@endcomponent
