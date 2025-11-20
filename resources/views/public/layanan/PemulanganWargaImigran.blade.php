@extends('layouts.public')

@section('title', 'Pemulangan warga imigran korban tindak kekerasan')

@section('content')
<div class="container my-5">
    <h2 class="text-center mb-4">Dokumen: Pemulangan warga imigran korban tindak kekerasan dari titik debarkasi di daerah Provinsi untuk di pulangkan ke Daerah Kab/ Kota Asal</h2>
    <div class="row justify-content-center">
        <div class="col-lg-10">
            @php
                // ID file dari link yang Anda berikan
                $driveFileId = '1geWQNHe_N6P4LP5lXKFaLtCgCNZyvwfB';
                
                // Format URL untuk menyematkan (embed) file Google Drive
                // Ini akan memberikan tampilan dokumen yang bersih tanpa side panel.
                $embedUrl = 'https://drive.google.com/file/d/' . $driveFileId . '/preview';
            @endphp

            <iframe
                {{-- MENGGUNAKAN URL embed Google Drive yang stabil --}}
                src="{{ $embedUrl }}" 
                style="width:100%; height:700px;"
                frameborder="0"
                allowfullscreen="true" {{-- Mengizinkan mode fullscreen --}}
            ></iframe>
            <p class="mt-3 text-center text-muted">
                File dokumen di atas ditampilkan dari Google Drive.
            </p>
        </div>
    </div>
</div>
@endsection