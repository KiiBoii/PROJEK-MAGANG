import React, { useState, useEffect } from 'react';
import axios from 'axios'; // Pastikan Anda sudah install axios: npm install axios

// Komponen Card Berita
function BeritaCard({ berita, onDelete }) {
    // Tentukan path gambar
    const imageUrl = berita.gambar ? `/storage/${berita.gambar}` : null;
    
    // Format tanggal
    const formattedDate = new Date(berita.created_at).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit'
    });
    
    // Fungsi Hapus (dipanggil dari parent)
    const handleDelete = () => {
        if (window.confirm('Yakin ingin menghapus berita ini?')) {
            onDelete(berita.id);
        }
    };

    return (
        <div className="col-md-4 mb-4">
            <div className="card h-100">
                {imageUrl ? (
                    <img src={imageUrl} className="card-img-top" alt={berita.judul} style={{ height: '200px', objectFit: 'cover' }} />
                ) : (
                    <div className="card-img-top d-flex align-items-center justify-content-center bg-light" style={{ height: '200px' }}>
                        <span className="text-muted"><i className="bi bi-image-fill fs-3"></i></span>
                    </div>
                )}
                
                <div className="card-body d-flex flex-column">
                    <h5 className="card-title">{berita.judul}</h5>
                    <small className="text-muted d-block text-end">{formattedDate}</small>
                    
                    <p className="card-text text-muted mt-2">
                        {/* Str::limit versi JavaScript */}
                        {berita.isi.length > 100 ? `${berita.isi.substring(0, 100)}...` : berita.isi}
                    </p>
                    
                    <hr className="mt-auto" /> 
                    
                    <div className="d-flex justify-content-between">
                        {/* Tombol Hapus sekarang memanggil fungsi React */}
                        <button onClick={handleDelete} className="btn btn-outline-danger btn-sm rounded-pill">
                            <i className="bi bi-trash me-1"></i> Hapus
                        </button>
                        
                        {/* Tombol Edit masih mengarah ke halaman Blade */}
                        <a href={`/admin/berita/${berita.id}/edit`} className="btn btn-outline-secondary btn-sm rounded-pill">
                            <i className="bi bi-pencil me-1"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    );
}

// Komponen Utama
function BeritaList() {
    const [beritas, setBeritas] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);

    // 1. Fetch data dari API saat komponen dimuat
    useEffect(() => {
        // Ganti '/api/admin/berita' dengan URL API Anda
        // Kita tambahkan '?' untuk bypass cache jika perlu
        axios.get('/api/admin/berita?time=' + new Date().getTime())
            .then(response => {
                setBeritas(response.data);
                setLoading(false);
            })
            .catch(error => {
                console.error("Error fetching data:", error);
                setError("Gagal memuat data berita.");
                setLoading(false);
            });
    }, []); // Array kosong berarti 'useEffect' hanya berjalan sekali (saat mount)

    // 2. Fungsi untuk menghapus berita
    const handleDeleteBerita = (id) => {
        // Panggil API untuk hapus
        axios.delete(`/api/admin/berita/${id}`)
            .then(response => {
                // Update state untuk menghapus card dari UI secara instan
                setBeritas(prevBeritas => prevBeritas.filter(berita => berita.id !== id));
                // Opsional: tampilkan notifikasi sukses
                alert(response.data.message || 'Berita berhasil dihapus.');
            })
            .catch(error => {
                console.error("Error deleting data:", error);
                alert("Gagal menghapus berita.");
            });
    };

    // 3. Render Tampilan
    if (loading) {
        return <div className="text-center p-5"><span className="spinner-border text-primary" role="status"></span><p>Memuat berita...</p></div>;
    }

    if (error) {
        return <div className="alert alert-danger">{error}</div>;
    }

    return (
        <div className="row">
            {beritas.length > 0 ? (
                beritas.map(berita => (
                    <BeritaCard 
                        key={berita.id} 
                        berita={berita} 
                        onDelete={handleDeleteBerita} 
                    />
                ))
            ) : (
                <div className="col-12">
                    <div className="alert alert-secondary text-center" role="alert">
                        Belum ada berita yang dipublikasikan.
                    </div>
                </div>
            )}
        </div>
    );
}

export default BeritaList;