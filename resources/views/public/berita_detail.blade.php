@extends('layouts.public')

{{-- CSS Kustom untuk halaman detail --}}
@push('styles')
<style>
    .article-header {
        margin-bottom: 2rem;
    }
    .article-category {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--primary-color);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .article-meta {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 1.5rem;
    }
    .article-meta .author {
        font-weight: 600;
        color: #333;
    }
    .article-main-image {
        width: 100%;
        height: 450px; 
        object-fit: cover; 
        border-radius: 12px;
        margin-bottom: 2rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        background-color: #e0e0e0; 
    }
    .article-content {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #343a40;
    }
    .article-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1.5rem 0;
    }

    /* Sidebar Berita Terkait */
    .sidebar-card {
        border: 1px solid #e0e0e0;
        border-radius: 12px;
    }
    .related-news-item {
        display: flex;
        align-items: flex-start;
        text-decoration: none;
        color: #212529;
        padding-bottom: 1rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid #f0f0f0;
    }
    .related-news-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }
    .related-news-item img {
        width: 100px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        margin-right: 1rem;
    }
    .related-news-item h6 {
        font-size: 0.95rem;
        font-weight: 600;
        line-height: 1.4;
        margin-bottom: 0.25rem;
    }
    .related-news-item .category {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--primary-color);
        text-transform: uppercase;
    }
</style>
@endpush

@section('content')

<div class="container my-5">
    <div class="row">

        <div class="col-lg-8">
            <article>
                <header class="article-header">
                    <div class="article-category">
                        {{ $berita->kategori ?? 'Berita' }}
                    </div>
                    
                    <h1 class="display-5 fw-bold my-3" style="color: var(--dark-blue);">
                        {{ $berita->judul }}
                    </h1>
                    
                    <div class="article-meta">
                        {{-- Ganti 'Admin' dengan $berita->penulis jika ada --}}
                        By <span class="author">Admin</span> | 
                        {{ $berita->created_at->format('d F Y, H:i') }} WIB
                    </div>
                </header>

                @if($berita->gambar)
                <img src="{{ asset('storage/' . $berita->gambar) }}" class="article-main-image" alt="{{ $berita->judul }}">
                @else
                <div class="article-main-image" style="background-color: #e9ecef; display: flex; align-items: center; justify-content: center; color: #6c757d;">
                    <span>Gambar tidak tersedia</span>
                </div>
                @endif
                
                <div class="article-content">
                    {!! $berita->isi !!}
                </div>
            </article>
        </div>

        <div class="col-lg-4">
            {{-- 
                KELAS 'sticky-top' DAN STYLE 'top' TELAH DIHAPUS DARI DIV DI BAWAH INI
            --}}
            <div class="card sidebar-card">
                <div class="card-body">
                    <h5 class="fw-bold mb-4" style="color: var(--dark-blue);">Related News</h5>
                    
                    @forelse($related_news as $related)
                    <a href="{{ route('public.berita.detail', $related->id) }}" class="related-news-item">
                        @if($related->gambar)
                        <img src="{{ asset('storage/' . $related->gambar) }}" alt="{{ $related->judul }}">
                        @else
                        <img src="https://placehold.co/100x80/e0e0e0/999?text=Berita" alt="Placeholder">
                        @endif
                        <div>
                            <span class="category">{{ $related->kategori ?? 'Berita' }}</span>
                            <h6>{{ $related->judul }}</h6>
                        </div>
                    </a>
                    @empty
                    <p class="text-muted">Tidak ada berita terkait.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>

@endsection