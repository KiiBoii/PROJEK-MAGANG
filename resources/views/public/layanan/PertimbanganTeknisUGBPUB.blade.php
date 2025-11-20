@extends('layouts.public')

@section('title', 'Pertimbangan teknis Undian Gratis Berhadiah (UGB) dan Pengumpulan Uang atau Barang (PUB)')

@section('content')
<div class="container my-5">
    <h2 class="text-center mb-4">Dokumen: Pertimbangan teknis Undian Gratis Berhadiah (UGB) dan Pengumpulan Uang atau Barang (PUB)</h2>
    <div class="row justify-content-center">
        <div class="col-lg-10">
            @php
                // !!! GANTI DENGAN NAMA FILE PDF YANG SESUAI UNTUK DOKUMEN INI !!!
                // Contoh nama file yang disederhanakan:
                $fileName = '12.Pertimbangan_teknis_Undian_Gratis_Berhadiah_(UGB)_dan_Pengumpulan_Uang_atau_Barang(PUB).pdf'; 
                
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