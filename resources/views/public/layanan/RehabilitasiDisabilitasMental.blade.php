@extends('layouts.public')

@section('title', 'Rehabilitasi Sosial Dasar Penyandang Disabilitas Mental Telantar')

@section('content')
<div class="container my-5">
    <h2 class="text-center mb-4">Dokumen: Rehabilitasi Sosial Dasar Penyandang Disabilitas Mental Telantar</h2>
    <div class="row justify-content-center">
        <div class="col-lg-10">
            @php
                // !!! GANTI DENGAN NAMA FILE PDF YANG SESUAI UNTUK DOKUMEN INI !!!
                // Contoh nama file yang disederhanakan:
                $fileName = '4.REHABILITASI_SOSIAL_DASAR_PENYANDANG_DISABILITAS_MENTAL_TERLANTAR.pdf'; 
                
                // Ini adalah URL langsung ke file PDF Anda.
                $filePath = asset('dokumenPPid/' . $fileName);
            @endphp

            <iframe
                {{-- MENGGUNAKAN TAMPILAN NATIVE BROWSER --}}
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