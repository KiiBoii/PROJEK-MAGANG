import './bootstrap'; // Bawaan Laravel

import React from 'react';
import { createRoot } from 'react-dom/client';

// Impor komponen yang baru kita buat
import BeritaList from './components/BeritaList';

// Cari div 'berita-list-app'
const container = document.getElementById('berita-list-app');

// Jika div-nya ada di halaman ini, render komponen React
if (container) {
    const root = createRoot(container);
    root.render(
        <React.StrictMode>
            <BeritaList />
        </React.StrictMode>
    );
}