@extends('layouts.public')

@section('title', 'Daftar Informasi Publik 2025')

@section('content')
<div class="container my-5">
    <h2 class="text-center mb-4">Dokumen: Daftar Informasi Publik Tahun 2025</h2>
    <div class="row justify-content-center">
        <div class="col-lg-10">
            @php
                // !!! GANTI DENGAN NAMA FILE PDF YANG SESUAI DI SERVER !!!
                $fileName = '1.DAFTAR_INFORMASI_PUBLIK_2025.pdf'; 
                $filePath = asset('dokumenPPid/' . $fileName);
            @endphp

            <iframe
                src="{{ $filePath }}" 
                style="width:100%; height:700px;"
                frameborder="0"
            ></iframe>
            <p class="mt-3 text-center text-muted">
                File dokumen PDF di atas adalah dokumen PPID yang diminta.
            </p>
        </div>
    </div>
</div>
@endsection