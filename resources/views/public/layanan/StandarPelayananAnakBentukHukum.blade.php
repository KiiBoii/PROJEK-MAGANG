@extends('layouts.public')

@section('title', 'Standar Pelayanan Rehabilitasi Sosial Bagi Anak Nakal, Anak Berhadapan Hukum (ABH), Diluar Hiv/Aids dan Napza di Dalam Panti')

@section('content')
<div class="container my-5">
    <h2 class="text-center mb-4">Dokumen: Standar Pelayanan Rehabilitasi Sosial Bagi Anak Nakal, Anak Berhadapan Hukum (ABH), Diluar Hiv/Aids dan Napza di Dalam Panti</h2>
    <div class="row justify-content-center">
        <div class="col-lg-10">
            @php
                // !!! GANTI DENGAN NAMA FILE PDF YANG SESUAI UNTUK DOKUMEN INI !!!
                // Contoh nama file yang disederhanakan:
                $fileName = '6.REHABILITASI_SOSIAL_BAGI_ANAK_NAKAL_ABH_DILUAR_HIV_AIDS_DAN_NAPZA_DIDALAM_PANTI.pdf'; 
                
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