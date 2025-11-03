import React, { useState, useEffect, useCallback } from 'react';
import axios from 'axios'; // Pastikan Anda sudah: npm install axios

// Komponen Card (Visual)
const BeritaCard = ({ berita, onDelete }) => {
    // Tentukan path gambar, tambahkan /storage/
    const imageUrl = berita.gambar ? `/storage/${berita.gambar}` : null;
    
    // Format tanggal
    const formattedDate = new Date(berita.created_at).toLocaleDateString('id-ID', {
        year: 'numeric', month: '2-digit', day: '2-digit'
    });
    
    // Fungsi Hapus (dipanggil dari parent)
    const handleDelete = () => {
        if (window.confirm(`Yakin ingin menghapus "${berita.judul}"?`)) {
            onDelete(berita.id);
        }
    };

    return (
        <div className="col-md-4 mb-4">
            <div className="card h-100">
                {imageUrl ? (
                    <img src={imageUrl} className="card-img-top" alt={berita.judul} style={{ height: '200px', objectFit: 'cover' }} 
                         onError={(e) => { e.target.src = 'https://placehold.co/600x400/lightgrey/grey?text=Gagal+Muat'; }} // Fallback
                    />
                ) : (
                    <div className="card-img-top d-flex align-items-center justify-content-center bg-light" style={{ height: '200px' }}>
                        <span className="text-muted"><i className="bi bi-image-fill fs-3"></i></span>
                    </div>
                )}
                <div className="card-body d-flex flex-column">
                    <h5 className="card-title">{berita.judul}</h5>
                    <small className="text-muted d-block text-end">{formattedDate}</small>
                    <p className="card-text text-muted mt-2">
                        {berita.isi.length > 100 ? `${berita.isi.substring(0, 100)}...` : berita.isi}
                    </p>
                    <hr className="mt-auto" /> 
                    <div className="d-flex justify-content-between">
                        <button onClick={handleDelete} className="btn btn-outline-danger btn-sm rounded-pill">
                            <i className="bi bi-trash me-1"></i> Hapus
                        </button>
                        <a href={`/admin/berita/${berita.id}/edit`} className="btn btn-outline-secondary btn-sm rounded-pill">
                            <i className="bi bi-pencil me-1"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    );
};

// Komponen Utama (Logika)
const BeritaApp = () => {
    const [beritas, setBeritas] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    
    // State untuk filter
    const [filterTanggal, setFilterTanggal] = useState('');
    const [filterTag, setFilterTag] = useState('');

    // Fungsi untuk mengambil data dari API
    const fetchData = useCallback(() => {
        setLoading(true);
        
        // Menyiapkan parameter filter untuk API
        const params = {
            tanggal: filterTanggal,
            tag: filterTag,
            // === INI PERBAIKANNYA ===
            // Tambahkan parameter unik (timestamp) untuk "cache busting"
            _t: new Date().getTime(),
        };

        axios.get('/api/admin/berita', { params })
            .then(response => {
                setBeritas(response.data);
                setError(null);
            })
            .catch(error => {
                console.error("Error fetching data:", error);
                setError("Gagal memuat data berita. Coba refresh halaman.");
            })
            .finally(() => {
                setLoading(false);
            });
    }, [filterTanggal, filterTag]); // Ambil data ulang jika filter berubah

    // Ambil data saat komponen pertama kali dimuat
    useEffect(() => {
        fetchData();
    }, [fetchData]);

    // Fungsi untuk menghapus berita
    const handleDeleteBerita = (id) => {
        // Optimistic UI: Hapus dari state dulu
        const originalBeritas = [...beritas];
        setBeritas(prevBeritas => prevBeritas.filter(berita => berita.id !== id));

        // Panggil API untuk hapus
        axios.delete(`/api/admin/berita/${id}`)
            .then(response => {
                // Sukses
                console.log(response.data.message);
            })
            .catch(error => {
                // Gagal, kembalikan data semula
                console.error("Error deleting data:", error);
                setBeritas(originalBeritas);
                alert("Gagal menghapus berita.");
            });
    };

    // Render Tampilan
    const renderContent = () => {
        if (loading) {
            return (
                <div className="col-12 text-center p-5">
                    <span className="spinner-border text-primary" role="status"></span>
                    <p className="mt-2">Memuat berita...</p>
                </div>
            );
        }

        if (error) {
            return <div className="col-12"><div className="alert alert-danger">{error}</div></div>;
        }

        if (beritas.length === 0) {
            return (
                <div className="col-12">
                    <div className="alert alert-secondary text-center" role="alert">
                        Belum ada berita yang dipublikasikan.
                    </div>
                </div>
            );
        }

        return beritas.map(berita => (
            <BeritaCard 
                key={berita.id} 
                berita={berita} 
                onDelete={handleDeleteBerita} 
            />
        ));
    };

    return (
        <>
            {/* Filter Dropdowns (sekarang dikontrol oleh React) */}
            <div className="d-flex mb-3">
                <div className="dropdown me-2">
                    <button className="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i className="bi bi-calendar3 me-1"></i> {filterTanggal || 'Tanggal'}
                    </button>
                    <ul className="dropdown-menu">
                        <li><a className="dropdown-item" href="#" onClick={() => setFilterTanggal('hari_ini')}>Hari Ini</a></li>
                        <li><a className="dropdown-item" href="#" onClick={() => setFilterTanggal('7_hari')}>7 Hari Terakhir</a></li>
<li><a className="dropdown-item" href="#" onClick={() => setFilterTanggal('bulan_ini')}>Bulan Ini</a></li>
                        <li><hr className="dropdown-divider" /></li>
                        <li><a className="dropdown-item" href="#" onClick={() => setFilterTanggal('')}>Semua</a></li>
                    </ul>
                </div>
                <div className="dropdown">
                    <button className="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i className="bi bi-tag me-1"></i> {filterTag || 'Tag Berita'}
                    </button>
                    <ul className="dropdown-menu">
                        <li><a className="dropdown-item" href="#" onClick={() => setFilterTag('Info')}>Info</a></li>
                        <li><a className="dropdown-item" href="#" onClick={() => setFilterTag('Layanan')}>Layanan</a></li>
                        <li><a className="dropdown-item" href="#" onClick={() => setFilterTag('Kegiatan')}>Kegiatan</a></li>
                        <li><hr className="dropdown-divider" /></li>
                        <li><a className="dropdown-item" href="#" onClick={() => setFilterTag('')}>Semua</a></li>
                    </ul>
                </div>
            </div>
            
            {/* List Berita */}
            <div className="row">
                {renderContent()}
            </div>
        </>
    );
};

export default BeritaApp;

