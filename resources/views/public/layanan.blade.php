@extends('layouts.public')

{{-- 1. CSS KUSTOM DITAMBAHKAN UNTUK SLIDER --}}
@push('styles')
<style>
    .news-slider .carousel-item {
        height: 450px; /* Atur tinggi slider */
        background-color: #555;
    }

    .news-slider .carousel-item img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Pastikan gambar mengisi area */
    }

    /* Overlay gradient gelap agar teks terbaca */
    .news-slider .carousel-item::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to top, rgba(0,0,0,0.6) 20%, rgba(0,0,0,0) 80%);
    }

    .news-slider .carousel-caption {
        bottom: 0;
        z-index: 10;
        text-align: left;
        padding: 2rem 1.5rem;
        width: 80%; 
        left: 5%; 
    }

    .news-slider .carousel-caption h5 {
        font-size: 2rem; 
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }
    .news-slider .carousel-caption p {
        font-size: 1rem;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }
    
    /* === CSS BARU UNTUK KARTU KONTAK === */
    .contact-card-link {
        display: block;
        background-color: #f8f9fa; /* Warna abu-abu sangat muda */
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 1.25rem;
        text-decoration: none;
        color: #333;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
    }
    .contact-card-link:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.05);
        color: var(--primary-color);
        border-color: var(--primary-color);
    }
    .contact-card-link i {
        font-size: 1.5rem;
        margin-right: 1rem;
        color: var(--primary-color);
        vertical-align: middle;
    }
    /* === AKHIR CSS BARU === */
    
    /* Style untuk tombol Tab yang aktif */
    .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
        background-color: var(--primary-color) !important;
        color: white !important;
        border-radius: 8px;
    }
</style>
@endpush

@section('content')

<div class="container my-5">
    
    <div id="heroSlider" class="carousel slide news-slider" data-bs-ride="carousel" data-bs-pause="false" data-bs-interval="3000" 
         style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
        
        <div class="carousel-indicators">
            @if(isset($sliders) && $sliders->count() > 0)
                @foreach($sliders as $slider)
                    <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}" aria-current="{{ $loop->first ? 'true' : 'false' }}" aria-label="Slide {{ $loop->iteration }}"></button>
                @endforeach
            @else
                <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            @endif
        </div>
        
        <div class="carousel-inner">
            @forelse(isset($sliders) ? $sliders : [] as $slider)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                    <img src="{{ asset('storage/' . $slider->gambar) }}" class="d-block w-100" alt="{{ $slider->judul }}">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>{{ $slider->judul }}</h5>
                        <p>{{ $slider->keterangan }}</p>
                    </div>
                </div>
            @empty
                <div class="carousel-item active">
                    <img src="https://placehold.co/1920x450/6610f2/white?text=LAYANAN+DINAS+SOSIAL" class="d-block w-100" alt="Slider Fallback">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>LAYANAN PUBLIK</h5>
                        <p>Informasi layanan, pusat bantuan, dan formulir pengaduan.</p>
                    </div>
                </div>
            @endforelse
        </div>
        
        @if(isset($sliders) && $sliders->count() > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#heroSlider" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroSlider" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        @endif
    </div>

</div> <div class="container my-5">
    <div class="row">
        
        <div class="col-lg-8">
            
            <div>
                <h2 class="section-title text-start ps-0 mb-4">Pelayanan Mandiri</h2>
                <p class="text-muted mb-4">Temukan informasi layanan yang Anda butuhkan melalui tautan di bawah ini.</p>
            </div>
            
            <ul class="nav nav-pills nav-fill mb-4 p-2 bg-light rounded-3" id="serviceTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-dark w-100 py-3" id="tab-dokumen" data-bs-toggle="pill" data-bs-target="#content-dokumen" type="button" role="tab" aria-controls="content-dokumen" aria-selected="false">
                        <i class="bi bi-file-earmark-zip me-2"></i> Dokumen Publikasi
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-dark w-100 py-3" id="tab-bantuan" data-bs-toggle="pill" data-bs-target="#content-bantuan" type="button" role="tab" aria-controls="content-bantuan" aria-selected="false">
                        <i class="bi bi-question-circle me-2"></i> Pusat Bantuan
                    </button>
                </li>
            </ul>

            {{-- Tab Content --}}
            <div class="tab-content" id="serviceTabContent">

                {{-- TAB 2: DOKUMEN PUBLIKASI (Fitur Baru) --}}
                <div class="tab-pane fade" id="content-dokumen" role="tabpanel" aria-labelledby="tab-dokumen">
                    <h2 class="section-title text-start ps-0 mb-4">Dokumen Publikasi</h2>
                    <p class="text-muted mb-4">Unduh dokumen resmi yang diterbitkan oleh Dinas Sosial Provinsi Riau.</p>

                    <div class="row mb-3 align-items-center">
                        <div class="col-md-6 d-flex align-items-center">
                            <label for="perPageDok" class="me-2">Tampilkan</label>
                            
                            {{-- ▼▼▼ PERBAIKAN 1 DI SINI ▼▼▼ --}}
                            <select id="perPageDok" class="form-select form-select-sm w-auto" onchange="window.location.href = this.value;">
                                @php
                                    $currentPage = $dokumens->perPage();
                                    $currentUrl = request()->fullUrlWithoutQuery(['per_page', 'cari']);
                                @endphp
                                @foreach ([10, 25, 50] as $size)
                                    <option value="{{ $currentUrl }}?per_page={{ $size }}{{ request('cari') ? '&cari=' . request('cari') : '' }}#content-dokumen" {{ $currentPage == $size ? 'selected' : '' }}>
                                        {{ $size }}
                                    </option>
                                @endforeach
                            </select>
                            {{-- ▲▲▲ AKHIR PERBAIKAN 1 ▲▲▲ --}}

                            <span class="ms-2">data per halaman</span>
                        </div>
                        <div class="col-md-6">
                            <form action="#content-dokumen" method="GET" class="d-flex" onsubmit="window.location.href='#' + this.action.split('#')[1]">
                                <input type="text" name="cari" class="form-control" placeholder="Cari Dokumen..." value="{{ request('cari') }}">
                                <button type="submit" class="btn btn-primary ms-2">Cari</button>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>Judul Dokumen</th>
                                    <th>Keterangan</th>
                                    <th style="width: 120px;">Download</th>
                                    <th style="width: 150px;">Tanggal Upload</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($dokumens as $dokumen)
                                <tr>
                                    <td>{{ $dokumens->firstItem() + $loop->index }}</td>
                                    <td>{{ $dokumen->judul }}</td>
                                    <td>{{ $dokumen->keterangan }}</td>
                                    <td class="text-center">
                                        <a href="{{ $dokumen->download_url }}" target="_blank" class="btn btn-sm btn-primary">
                                            <i class="bi bi-download"></i>
                                        </a>
                                    </td>
                                    <td>{{ $dokumen->created_at->format('d M Y') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">Dokumen tidak ditemukan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- ▼▼▼ PERBAIKAN 2 DI SINI ▼▼▼ --}}
                    <div class="d-flex justify-content-center mt-4">
                        {{ $dokumens->fragment('content-dokumen')->links('pagination::bootstrap-5') }}
                    </div>
                    {{-- ▲▲▲ AKHIR PERBAIKAN 2 ▲▲▲ --}}

                </div>
                
                {{-- TAB 3: PUSAT BANTUAN (FAQ) --}}
                <div class="tab-pane fade" id="content-bantuan" role="tabpanel" aria-labelledby="tab-bantuan">
                    <h3 id="pusat-bantuan" class="fw-bold mb-4">Pusat Bantuan</h3>
                    <p class="text-muted mb-4">Berikut adalah jawaban untuk pertanyaan yang sering diajukan seputar layanan kami.</p>
                    
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Bagaimana cara mendaftar Bantuan Sosial?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Pendaftaran Bantuan Sosial dilakukan melalui Data Terpadu Kesejahteraan Sosial (DTKS). Silakan mendaftar ke kantor desa/lurah setempat...
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Apa saja syarat untuk mendapatkan rehabilitasi sosial?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Syarat utama adalah...
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    Bagaimana cara melaporkan pengaduan?
                                </button>
                            </h2>
                            <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Anda dapat menggunakan formulir pengaduan di samping, atau menghubungi kami melalui kontak yang tertera.
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">Hubungi Kami</h4>
                    
                    <a href="{{ route('public.kontak') }}" class="contact-card-link mb-3">
                        <i class="bi bi-pencil-square"></i>
                        <span>Masukan</span>
                    </a>
                    
                    <a href="mailto:info@dinsos.riau.go.id" class="contact-card-link">
                        <i class="bi bi-envelope-fill"></i>
                        <span>Email Kontak</span>
                    </a>

                </div>
            </div>
        </div>
        
    </div>
</div>

@endsection

{{-- ▼▼▼ JAVASCRIPT DIPERBARUI DI SINI ▼▼▼ --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /**
     * Fungsi untuk membaca hash URL dan mengaktifkan tab yang sesuai.
     */
    function activateTabFromHash() {
        const urlParams = new URLSearchParams(window.location.search);
        const urlHash = window.location.hash; // cth: #content-dokumen
        
        let tabToActivateId;

        if (urlHash === '#content-dokumen') {
            // Prioritas 1: Jika link memiliki hash #content-dokumen
            tabToActivateId = 'tab-dokumen';
        } else if (urlHash === '#content-bantuan') {
            // Prioritas 2: Jika link memiliki hash #content-bantuan
            tabToActivateId = 'tab-bantuan';
        } else if (urlParams.has('cari') || urlParams.has('per_page')) {
            // Prioritas 3: Jika ada parameter pencarian (untuk tab dokumen)
            tabToActivateId = 'tab-dokumen';
        } else {
            // Default: Jika tidak ada hash atau parameter, buka tab Bantuan
            tabToActivateId = 'tab-bantuan';
        }

        const tabElement = document.getElementById(tabToActivateId);
        if (tabElement) {
            // Coba dapatkan instance Tab yang sudah ada, atau buat baru
            const tabInstance = bootstrap.Tab.getInstance(tabElement) || new bootstrap.Tab(tabElement);
            tabInstance.show();
        }
    }

    // 1. Panggil fungsi saat halaman pertama kali dimuat
    activateTabFromHash();

    // 2. TAMBAHAN: Panggil fungsi yang sama SETIAP KALI hash berubah
    // Ini akan menangani klik navbar saat user sudah di halaman layanan
    window.addEventListener('hashchange', activateTabFromHash);

});
</script>
@endpush