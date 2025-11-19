@extends('layouts.public')

{{-- Menetapkan Judul Halaman --}}
@section('title', 'Pusat Bantuan (FAQ)')

{{-- 1. CSS KUSTOM DITAMBAHKAN (SLIDER, KARTU, AOS, BACK-TO-TOP) --}}
@push('styles')
    {{-- [BARU] Tambahkan CSS AOS (Animate On Scroll) --}}
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">

<style>
    /* === [BARU] CSS SLIDER (Diambil dari contoh) === */
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
    /* === AKHIR CSS SLIDER === */


    /* === CSS UNTUK KARTU KONTAK (Sudah ada) === */
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
    /* === AKHIR CSS KARTU KONTAK === */

    /* Styling accordion (Sudah ada) */
    .accordion-item {
        border: none;
        border-radius: 8px !important;
        margin-bottom: 1rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        overflow: hidden; /* Penting untuk border-radius */
    }
    .accordion-button {
        font-weight: 600;
        color: var(--dark-blue);
    }
    .accordion-button:not(.collapsed) {
        background-color: var(--primary-color);
        color: white;
        box-shadow: none;
    }
    .accordion-button:focus {
        box-shadow: none;
    }

    /* ▼▼▼ [BARU] STYLE TOMBOL BACK TO TOP ▼▼▼ */
    #backToTopBtn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
        visibility: hidden; /* Sembunyi secara default */
        opacity: 0;
        transition: visibility 0.3s, opacity 0.3s ease-in-out;
        
        /* Menggunakan style Bootstrap */
        padding: 0.5rem 1rem; /* Sesuaikan padding untuk btn-lg */
        font-size: 1.25rem; /* Sesuaikan font-size untuk btn-lg */
        width: 58px; /* Pastikan bulat sempurna untuk btn-lg */
        height: 58px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #backToTopBtn.show {
        visibility: visible;
        opacity: 1;
    }
    /* ▲▲▲ AKHIR STYLE TOMBOL BACK TO TOP ▲▲▲ */
</style>
@endpush

@section('content')

<!-- === [BARU] BAGIAN SLIDER === -->
<div class="container my-5" data-aos="fade-in" data-aos-duration="1000">
    
    <div id="heroSlider" class="carousel slide news-slider" data-bs-ride="carousel" data-bs-pause="false" data-bs-interval="3000" 
          style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.08);">
        
        <!-- Indicators (Dibuat dinamis) -->
        <div class="carousel-indicators">
            @foreach($sliders as $slider)
                <button type="button" data-bs-target="#heroSlider" data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}" aria-current="{{ $loop->first ? 'true' : 'false' }}" aria-label="Slide {{ $loop->iteration }}"></button>
            @endforeach
        </div>
        
        <!-- Slides (Dibuat dinamis) -->
        <div class="carousel-inner">
            @forelse($sliders as $slider)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                    <img src="{{ asset('storage/' . $slider->gambar) }}" class="d-block w-100" alt="{{ $slider->judul }}">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>{{ $slider->judul }}</h5>
                        <p>{{ Str::limit(strip_tags($slider->keterangan), 100) }}</p>
                    </div>
                </div>
            @empty
                {{-- Fallback jika tidak ada slider --}}
                <div class="carousel-item active">
                    <img src="https://placehold.co/1920x450/007bff/white?text=Pusat+Bantuan" class="d-block w-100" alt="Slider 1">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Pusat Bantuan (FAQ)</h5>
                        <p>Temukan jawaban atas pertanyaan Anda di sini.</p>
                    </div>
                </div>
            @endforelse
        </div>
        
        {{-- Tampilkan tombol navigasi hanya jika slide lebih dari 1 --}}
        @if($sliders->count() > 1)
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

</div> <!-- Penutup container slider -->
<!-- === AKHIR BAGIAN SLIDER === -->


<div class="container my-5">
    
    {{-- Judul Halaman (Sudah ada) --}}
    <div class="row mb-5" data-aos="fade-up">
        <div class="col-12">
            <h2 class="section-title">
                Pertanyaan yang Sering Diajukan (FAQ)
            </h2>
        </div>
    </div>

    <div class="row">
        
        {{-- KOLOM UTAMA: DAFTAR FAQ (ACCORDION) --}}
        <div class="col-lg-8" data-aos="fade-right" data-aos-delay="100">
            
            <p class="text-muted mb-4 fs-5" style="max-width: 90%;">
                Temukan jawaban cepat seputar data, bansos, dan layanan Dinas Sosial.
            </p>
            
            <div class="accordion" id="faqAccordion">
                
                {{-- ▼▼▼ KONTEN FAQ BARU DIMASUKKAN DI SINI ▼▼▼ --}}

                <div class="accordion-item" data-aos="fade-up" data-aos-delay="200">
                    <h2 class="accordion-header" id="headingFAQ1">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFAQ1" aria-expanded="false" aria-controls="collapseFAQ1">
                            1. Data apa yang digunakan untuk sasaran program intervensi penanganan kemiskinan?
                        </button>
                    </h2>
                    <div id="collapseFAQ1" class="accordion-collapse collapse" aria-labelledby="headingFAQ1" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            Disebutkan pada pasal 10 ayat 3 UU No 13 Tahun 2011 semua program bantuan dan atau pemberdayaan pemerintah dalam rangka penanganan fakir miskin harus berdasarkan Data Terpadu. Sedangkan berdasarkan Inpres No 4 Tahun 2025, saat ini yang digunakan adalah Data Tunggal Sosial dan Ekonomi Nasional (DTSEN).
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item" data-aos="fade-up" data-aos-delay="250">
                    <h2 class="accordion-header" id="headingFAQ2">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFAQ2" aria-expanded="false" aria-controls="collapseFAQ2">
                            2. Apa itu bantuan sosial (bansos)?
                        </button>
                    </h2>
                    <div id="collapseFAQ2" class="accordion-collapse collapse" aria-labelledby="headingFAQ2" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            Bansos merupakan program pemerintah yang bisa berupa bantuan uang/barang/pembiayaan untuk membantu masyarakat yang membutuhkan dan melindungi dari risiko sosial. Tujuannya secara langsung untuk memenuhi dan menjamin kebutuhan dasar, mendorong kemandirian, meningkatkan taraf hidup penerima bansos, dan secara tidak langsung menjaga stabilitas ekonomi nasional.
                            <br><br>
                            Sifat bantuan sosial pada dasarnya *tidak terus-menerus* dan *selektif*. Namun pada keadaan tertentu, bantuan sosial dapat diberikan secara berkelanjutan hingga penerima tidak lagi berada dalam risiko sosial (dengan evaluasi kelayakan secara berkala). Pemberian bantuan juga disesuaikan dengan prioritas kebijakan pembangunan serta kemampuan keuangan pemerintah baik pusat maupun daerah. Oleh karena itu, kepesertaan bantuan sosial bersifat sementara dan bisa berubah apabila ada perubahan kebijakan alokasi anggaran, perubahan kriteria dan perubahan/pembaharuan data sasaran.
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item" data-aos="fade-up" data-aos-delay="300">
                    <h2 class="accordion-header" id="headingFAQ3">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFAQ3" aria-expanded="false" aria-controls="collapseFAQ3">
                            3. Apa saja jenis bansos reguler Kemensos dan kriteria penerimanya?
                        </button>
                    </h2>
                    <div id="collapseFAQ3" class="accordion-collapse collapse" aria-labelledby="headingFAQ3" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            <p class="fw-bold mb-1">Penerima Bantuan Iuran Jaminan Kesehatan (PBI JK)</p>
                            <p>Bantuan berupa pembiayaan iuran yang dibayarkan langsung kepada BPJS Kesehatan (tidak diberikan kepada penerima dalam bentuk uang). Penerima PBI JK dapat menggunakan layanan kesehatan di fasilitas kesehatan mitra BPJS Kesehatan secara gratis sesuai ketentuan yang berlaku.
                            <br>Kriteria: Masuk **DTSEN Desil 1-5**.</p>
                            
                            <p class="fw-bold mb-1">SEMBAKO</p>
                            <p>Bantuan berupa uang sebesar Rp 200.000/bulan yang dapat diterimakan dalam beberapa periode sekaligus, disalurkan melalui HIMBARA yang ditunjuk. Penerima baru akan melalui proses pembukaan rekening kolektif (Burekol) yang akan disertai dengan distribusi / pembagian Kartu Keluarga Sejahtera (KKS) sebagai alat transaksi / pencairan.
                            <br>Kriteria: Masuk **DTSEN Desil 1-5**.</p>

                            <p class="fw-bold mb-1">Program Keluarga Harapan (PKH)</p>
                            <p>Bantuan tunai bersyarat berupa uang dengan besaran sesuai jenis komponen yang dimiliki. Bantuan disalurkan melalui HIMBARA yang ditunjuk. Penerima PKH wajib mengikuti kegiatan rutin Pertemuan Peningkatan Kemampuan Keluarga (P2K2).
                            <br>Kriteria: Keluarga yang masuk **DTSEN Desil 1-4**, serta memiliki *minimal satu* dari komponen berikut:</p>
                            <ul>
                                <li>**Komponen Kesehatan**: Apabila dalam keluarga terdapat ibu hamil dan atau anak usia dini (di bawah 7 tahun / belum sekolah).</li>
                                <li>**Komponen Pendidikan**: Apabila dalam keluarga terdapat anak sekolah dasar (SD), anak sekolah menengah pertama (SMP), anak sekolah menengah atas (SMA).</li>
                                <li>**Komponen Kesejahteraan Sosial**: Apabila dalam keluarga terdapat lanjut usia atau penyandang Disabilitas (Kepdirjen Perlinsos No 9/3/HK.01/1/2025).</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="accordion-item" data-aos="fade-up" data-aos-delay="350">
                    <h2 class="accordion-header" id="headingFAQ4">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFAQ4" aria-expanded="false" aria-controls="collapseFAQ4">
                            4. Bagaimana cara mengetahui status keberadaan seseorang/keluarga dalam DTSEN?
                        </button>
                    </h2>
                    <div id="collapseFAQ4" class="accordion-collapse collapse" aria-labelledby="headingFAQ4" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            Untuk mengetahui status keberadaan seseorang/keluarga dalam DTSEN yaitu melakukan pengecekan berbasis NIK melalui akun aplikasi SIKS-NG. Hal tersebut dapat dilakukan melalui petugas/operator kalurahan/kelurahan setempat dan atau petugas/operator Dinas Sosial Kabupaten/Kota setempat dengan menunjukkan KTP/KK.
                        </div>
                    </div>
                </div>

                <div class="accordion-item" data-aos="fade-up" data-aos-delay="400">
                    <h2 class="accordion-header" id="headingFAQ5">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFAQ5" aria-expanded="false" aria-controls="collapseFAQ5">
                            5. Jika menemui keluarga miskin yang saat akan dilakukan pengusulan bansos ternyata diketahui belum masuk DTSEN, apa yang harus dilakukan?
                        </button>
                    </h2>
                    <div id="collapseFAQ5" class="accordion-collapse collapse" aria-labelledby="headingFAQ5" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            Sebagaimana disebutkan dalam pasal 9 ayat 1 UU No 13 Tahun 2011, bahwa seorang fakir miskin yang belum terdata dapat secara aktif mendaftarkan diri kepada lurah atau kepala desa atau nama lain yang sejenis di tempat tinggalnya.
                            <br><br>
                            Kementerian Sosial telah menyediakan kebijakan, mekanisme dan sistem untuk mengelola tatacara penyampaian data usulan dari daerah. Di mana setiap data usulan harus melalui proses finalisasi secara berjenjang oleh Lurah dan Bupati/Walikota (Permensos No 3 Tahun 2025). Di wilayah DIY, mekanisme pengusulan ke dalam data terpadu / DTSEN dilakukan melalui Musyawarah Kelurahan/Kalurahan (Pergub DIY No 63 Tahun 2023).
                        </div>
                    </div>
                </div>

                <div class="accordion-item" data-aos="fade-up" data-aos-delay="450">
                    <h2 class="accordion-header" id="headingFAQ6">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFAQ6" aria-expanded="false" aria-controls="collapseFAQ6">
                            6. Data/informasi apa saja yang harus diisikan pada saat melakukan proses usulan data/proses usulan pembaharuan data?
                        </button>
                    </h2>
                    <div id="collapseFAQ6" class="accordion-collapse collapse" aria-labelledby="headingFAQ6" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            Pada saat melakukan usulan baru (belum masuk DTSEN), di aplikasi SIKS-NG akan ada tahap pengisian data mencakup **39 variabel** yang dikelompokkan menjadi dua kategori utama: 13 variabel individu dan 26 variabel keluarga. Variabel-variabel ini digunakan untuk mengumpulkan data kondisi sosial dan ekonomi masyarakat agar penyaluran bantuan sosial menjadi lebih tepat sasaran.
                            <br><br>
                            Selain itu petugas/operator juga harus melengkapi dengan foto-foto dan geotag lokasi rumah individu/keluarga yang akan diusulkan tersebut. Pengisian data dilakukan melalui akun SIKS-NG atau SIKSMA oleh Petugas/Operator/Pendamping sosial yang ditunjuk dengan datang berkunjung ke alamat rumah sesuai KTP.
                        </div>
                    </div>
                </div>

                <div class="accordion-item" data-aos="fade-up" data-aos-delay="500">
                    <h2 class="accordion-header" id="headingFAQ7">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFAQ7" aria-expanded="false" aria-controls="collapseFAQ7">
                            7. Apa yang sebaiknya dilakukan apabila kita menemui seseorang/keluarga (dalam 1 kelurahan/kalurahan yang sama) yang terlihat mampu/kaya tetapi ternyata masih mendapatkan bansos?
                        </button>
                    </h2>
                    <div id="collapseFAQ7" class="accordion-collapse collapse" aria-labelledby="headingFAQ7" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            Sebagai upaya melibatkan partisipasi masyarakat dalam menjaga ketepatan sasaran bantuan sosial, Kementerian Sosial RI telah menyediakan mobile App Cek Bansos (dapat diunduh melalui Playstore). Untuk dapat digunakan, pengguna harus membuat akun terlebih dahulu dengan mengisikan data diri dan keluarga sesuai KTP dan KK terbaru.
                            <br><br>
                            Info lebih lanjut tentang pemanfaatan mobile app Cek Bansos dapat diketahui melalui tautan video dari akun Youtube resmi Pusdatin Kesos berikut: 
                            <ul>
                                <li><a href="#" class="text-decoration-none">Aplikasi Cek Bansos Menu Usul dan Sanggah</a></li>
                                <li><a href="#" class="text-decoration-none">Usulan Bantuan Sosial Melalui Aplikasi Cek Bansos</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="accordion-item" data-aos="fade-up" data-aos-delay="550">
                    <h2 class="accordion-header" id="headingFAQ8">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFAQ8" aria-expanded="false" aria-controls="collapseFAQ8">
                            8. Apa saja yang dapat menyebabkan kepesertaan bansos nonaktif?
                        </button>
                    </h2>
                    <div id="collapseFAQ8" class="accordion-collapse collapse" aria-labelledby="headingFAQ8" data-bs-parent="#faqAccordion">
                        <div class="accordion-body text-muted">
                            Kepesertaan bansos dapat berhenti / nonaktif karena hal-hal berikut:
                            <ul>
                                <li>Pada saat dilakukan verifikasi/groundcheck, alamat tidak ditemukan</li>
                                <li>Pada saat dilakukan verifikasi/groundcheck, individu tidak ditemukan</li>
                                <li>Meninggal dunia</li>
                                <li>Memiliki pekerjaan sebagai ASN/TNI/POLRI/Pegawai BUMN/Pegawai BUMD/Pejabat Negara</li>
                                <li>Merupakan anggota keluarga dari ASN/TNI/POLRI/Pegawai BUMN/Pegawai BUMD/Pejabat Negara</li>
                                <li>Tidak lagi sesuai kriteria (masuk dalam Desil 6-10 DTSEN)</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                {{-- ▲▲▲ AKHIR KONTEN FAQ BARU ▲▲▲ --}}

            </div>

        </div>

        {{-- KOLOM SIDEBAR: HUBUNGI KAMI --}}
        <div class="col-lg-4" data-aos="fade-left" data-aos-delay="200">
            <div class="card shadow-sm border-0" style="border-radius: 12px;">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3">Butuh Bantuan Lain?</h4>
                    <p class="text-muted mb-4">
                        Jika Anda tidak menemukan jawaban atas pertanyaan Anda, jangan ragu untuk menghubungi kami.
                    </p>
                    
                    <a href="{{ route('public.kontak') }}" class="contact-card-link mb-3">
                        <i class="bi bi-pencil-square"></i>
                        <span>Kirim Masukan</span>
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

{{-- ▼▼▼ [BARU] HTML TOMBOL BACK TO TOP ▼▼▼ --}}
<a href="#" id="backToTopBtn" class="btn btn-primary btn-lg rounded-circle shadow" title="Kembali ke atas">
    <i class="bi bi-arrow-up"></i>
</a>
{{-- ▲▲▲ AKHIR HTML TOMBOL BACK TO TOP ▲▲▲ --}}

@endsection

{{-- [BARU] Tambahkan JS di akhir body --}}
@push('scripts')
    {{-- [BARU] Tambahkan JS AOS (Animate On Scroll) --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    {{-- [BARU] Inisialisasi AOS --}}
    <script>
        // Tunggu hingga seluruh halaman (termasuk gambar) dimuat
        window.addEventListener('load', function() {
            AOS.init({
                duration: 800,   // Durasi animasi dalam milidetik
                once: true,      // [BERBEDA] Animasi hanya terjadi SEKALI
                offset: 100,     // Jarak (px) dari bagian bawah layar sebelum animasi dimulai
                easing: 'ease-out-cubic', // Jenis easing
            });
        });
    </script>

    {{-- ▼▼▼ [BARU] SCRIPT UNTUK TOMBOL BACK TO TOP ▼▼▼ --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var mybutton = document.getElementById("backToTopBtn");

            // Tampilkan/sembunyikan tombol
            window.onscroll = function() {
                var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
                
                // Tampilkan tombol setelah scroll 300px
                if (scrollTop > 300) { 
                    mybutton.classList.add("show");
                } else {
                    mybutton.classList.remove("show");
                }
            };

            // Scroll ke atas saat diklik
            mybutton.onclick = function(e) {
                e.preventDefault(); // Mencegah URL berubah menjadi #
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth' // Animasi scroll halus
                });
            }
        });
    </script>
    {{-- ▲▲▲ AKHIR SCRIPT TOMBOL BACK TO TOP ▲▲▲ --}}
@endpush