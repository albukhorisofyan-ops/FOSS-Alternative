<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\FossAlternative;
use App\Models\ProprietarySoftware;
use App\Models\User;
use Illuminate\Database\Seeder;

class AlterfossSeeder extends Seeder
{
    /**
     * Seed kategori, perangkat lunak berbayar, alternatif FOSS, dan akun admin.
     */
    public function run(): void
    {
        $categories = [
            'office' => 'Office & Produktivitas',
            'desain' => 'Desain Grafis',
            'sistem-operasi' => 'Sistem Operasi',
            'engineering' => 'Teknik & CAD',
            'komunikasi' => 'Komunikasi & Kolaborasi',
            'sains' => 'Sains & Matematika',
        ];

        foreach ($categories as $slug => $name) {
            Category::updateOrCreate(
                ['slug' => $slug],
                ['name' => $name],
            );
        }

        $software = [
            [
                'category' => 'office',
                'name' => 'Microsoft 365 Business Standard',
                'vendor' => 'Microsoft',
                'price_per_year' => 1800000,
                'description' => 'Paket aplikasi produktivitas berlangganan yang mencakup Word, Excel, PowerPoint, Outlook, dan penyimpanan cloud.',
                'alternatives' => [
                    [
                        'name' => 'LibreOffice',
                        'license' => 'MPL-2.0',
                        'website' => 'https://www.libreoffice.org',
                        'description' => 'Rangkaian aplikasi perkantoran lengkap dengan Writer, Calc, dan Impress.',
                        'migration_guide' => 'Buka berkas .docx, .xlsx, dan .pptx langsung di LibreOffice, lalu simpan ulang dalam format ODF atau tetap gunakan format Microsoft.',
                    ],
                    [
                        'name' => 'ONLYOFFICE Desktop Editors',
                        'license' => 'AGPL-3.0',
                        'website' => 'https://www.onlyoffice.com',
                        'description' => 'Editor dokumen, spreadsheet, dan presentasi dengan antarmuka yang mirip Microsoft Office.',
                        'migration_guide' => 'Pasang Desktop Editors, impor dokumen lama, dan atur format default ke OOXML agar tetap kompatibel dengan rekan kerja.',
                    ],
                ],
            ],
            [
                'category' => 'desain',
                'name' => 'Adobe Photoshop',
                'vendor' => 'Adobe',
                'price_per_year' => 1300000,
                'description' => 'Perangkat lunak penyuntingan foto dan gambar raster berbasis langganan.',
                'alternatives' => [
                    [
                        'name' => 'GIMP',
                        'license' => 'GPL-3.0-or-later',
                        'website' => 'https://www.gimp.org',
                        'description' => 'Editor gambar raster untuk retouching, komposisi, dan konversi format.',
                        'migration_guide' => 'Ekspor berkas PSD dari Photoshop, buka di GIMP, lalu sesuaikan lapisan dan kuas favoritmu.',
                    ],
                    [
                        'name' => 'Krita',
                        'license' => 'GPL-3.0-or-later',
                        'website' => 'https://krita.org',
                        'description' => 'Aplikasi lukis digital dengan dukungan kuas, tekstur, dan animasi.',
                        'migration_guide' => 'Gunakan Krita untuk ilustrasi dan lukisan digital; impor PSD untuk melanjutkan karya yang sudah ada.',
                    ],
                ],
            ],
            [
                'category' => 'desain',
                'name' => 'Adobe Illustrator',
                'vendor' => 'Adobe',
                'price_per_year' => 3700000,
                'description' => 'Aplikasi desain vektor untuk logo, ilustrasi, dan materi cetak.',
                'alternatives' => [
                    [
                        'name' => 'Inkscape',
                        'license' => 'GPL-2.0-or-later',
                        'website' => 'https://inkscape.org',
                        'description' => 'Editor grafis vektor dengan dukungan SVG dan berbagai format desain populer.',
                        'migration_guide' => 'Simpan karya Illustrator sebagai SVG atau PDF, lalu buka di Inkscape untuk melanjutkan penyuntingan vektor.',
                    ],
                ],
            ],
            [
                'category' => 'desain',
                'name' => 'CorelDRAW Graphics Suite',
                'vendor' => 'Corel',
                'price_per_year' => 8500000,
                'description' => 'Paket desain vektor dan tata letak untuk kebutuhan percetakan dan branding.',
                'alternatives' => [
                    [
                        'name' => 'Inkscape',
                        'license' => 'GPL-2.0-or-later',
                        'website' => 'https://inkscape.org',
                        'description' => 'Alternatif vektor untuk logo, ikon, dan ilustrasi.',
                        'migration_guide' => 'Ekspor berkas CDR ke SVG atau PDF, buka di Inkscape, lalu periksa ulang font dan warna.',
                    ],
                    [
                        'name' => 'Scribus',
                        'license' => 'GPL-2.0-or-later',
                        'website' => 'https://www.scribus.net',
                        'description' => 'Aplikasi tata letak halaman untuk brosur, majalah, dan materi cetak.',
                        'migration_guide' => 'Pindahkan materi tata letak ke Scribus, atur ulang master page, dan ekspor ke PDF siap cetak.',
                    ],
                ],
            ],
            [
                'category' => 'sistem-operasi',
                'name' => 'Microsoft Windows 11 Pro',
                'vendor' => 'Microsoft',
                'price_per_year' => 2900000,
                'description' => 'Sistem operasi berbayar untuk komputer pribadi dan lingkungan kerja.',
                'alternatives' => [
                    [
                        'name' => 'Ubuntu Desktop',
                        'license' => 'GPL-3.0',
                        'website' => 'https://ubuntu.com/desktop',
                        'description' => 'Distribusi Linux populer dengan dukungan perangkat keras luas dan pembaruan rutin.',
                        'migration_guide' => 'Cadangkan data, buat media instalasi Ubuntu, lalu uji aplikasi kerja di lingkungan live sebelum memasang permanen.',
                    ],
                    [
                        'name' => 'Linux Mint',
                        'license' => 'GPL-3.0',
                        'website' => 'https://linuxmint.com',
                        'description' => 'Distribusi Linux ramah pengguna dengan antarmuka yang mirip Windows.',
                        'migration_guide' => 'Gunakan Linux Mint untuk transisi bertahap; impor dokumen dan atur ulang printer serta akun surel.',
                    ],
                ],
            ],
            [
                'category' => 'engineering',
                'name' => 'Autodesk AutoCAD',
                'vendor' => 'Autodesk',
                'price_per_year' => 27000000,
                'description' => 'Perangkat lunak desain berbantuan komputer untuk gambar teknik dan arsitektur.',
                'alternatives' => [
                    [
                        'name' => 'FreeCAD',
                        'license' => 'LGPL-2.1-or-later',
                        'website' => 'https://www.freecad.org',
                        'description' => 'Pemodelan parametrik 3D untuk teknik mesin, arsitektur, dan desain produk.',
                        'migration_guide' => 'Impor berkas DXF atau DWG ke FreeCAD, sesuaikan skala, lalu simpan sebagai berkas proyek FreeCAD.',
                    ],
                    [
                        'name' => 'LibreCAD',
                        'license' => 'GPL-2.0-or-later',
                        'website' => 'https://librecad.org',
                        'description' => 'Aplikasi CAD 2D untuk gambar teknik dan denah.',
                        'migration_guide' => 'Buka berkas DXF di LibreCAD, periksa lapisan dan dimensi, lalu ekspor kembali ke PDF atau DXF.',
                    ],
                ],
            ],
            [
                'category' => 'sains',
                'name' => 'MathWorks MATLAB',
                'vendor' => 'MathWorks',
                'price_per_year' => 15000000,
                'description' => 'Lingkungan komputasi numerik dan pemrograman untuk analisis data serta simulasi.',
                'alternatives' => [
                    [
                        'name' => 'GNU Octave',
                        'license' => 'GPL-3.0-or-later',
                        'website' => 'https://octave.org',
                        'description' => 'Bahasa komputasi numerik yang kompatibel dengan sebagian besar sintaks MATLAB.',
                        'migration_guide' => 'Jalankan skrip .m di Octave, periksa fungsi toolbox yang belum tersedia, lalu ganti dengan paket Octave Forge.',
                    ],
                    [
                        'name' => 'Scilab',
                        'license' => 'GPL-2.0-or-later',
                        'website' => 'https://www.scilab.org',
                        'description' => 'Perangkat komputasi numerik dan visualisasi untuk sains dan teknik.',
                        'migration_guide' => 'Terjemahkan skrip MATLAB ke sintaks Scilab, uji ulang hasil numerik, dan sesuaikan fungsi plot.',
                    ],
                ],
            ],
            [
                'category' => 'komunikasi',
                'name' => 'Zoom Workplace Pro',
                'vendor' => 'Zoom',
                'price_per_year' => 2200000,
                'description' => 'Layanan konferensi video dan rapat daring berbasis langganan.',
                'alternatives' => [
                    [
                        'name' => 'Jitsi Meet',
                        'license' => 'Apache-2.0',
                        'website' => 'https://jitsi.org',
                        'description' => 'Platform konferensi video open source yang dapat dipasang sendiri.',
                        'migration_guide' => 'Buat ruang rapat di Jitsi Meet, undang peserta lewat tautan, dan uji kamera serta mikrofon sebelum rapat.',
                    ],
                    [
                        'name' => 'BigBlueButton',
                        'license' => 'LGPL-3.0',
                        'website' => 'https://bigbluebutton.org',
                        'description' => 'Platform kelas virtual dengan papan tulis, berbagi layar, dan rekaman.',
                        'migration_guide' => 'Pasang BigBlueButton di server atau gunakan penyedia terkelola, lalu jadwalkan kelas dan uji rekaman.',
                    ],
                ],
            ],
            [
                'category' => 'office',
                'name' => 'Microsoft Visio Standard',
                'vendor' => 'Microsoft',
                'price_per_year' => 4000000,
                'description' => 'Aplikasi pembuatan diagram alur, bagan organisasi, dan proses bisnis.',
                'alternatives' => [
                    [
                        'name' => 'diagrams.net',
                        'license' => 'Apache-2.0',
                        'website' => 'https://www.drawio.com',
                        'description' => 'Editor diagram daring dan luring untuk diagram alur, arsitektur, dan proses.',
                        'migration_guide' => 'Impor berkas Visio (.vsdx) ke diagrams.net, periksa konektor dan teks, lalu simpan dalam format .drawio.',
                    ],
                ],
            ],
            [
                'category' => 'office',
                'name' => 'Adobe Acrobat Pro',
                'vendor' => 'Adobe',
                'price_per_year' => 2400000,
                'description' => 'Aplikasi penyuntingan, penggabungan, dan penandatanganan dokumen PDF.',
                'alternatives' => [
                    [
                        'name' => 'PDF Arranger',
                        'license' => 'GPL-3.0-or-later',
                        'website' => 'https://github.com/pdfarranger/pdfarranger',
                        'description' => 'Alat untuk menggabungkan, memisahkan, dan mengatur ulang halaman PDF.',
                        'migration_guide' => 'Buka PDF di PDF Arranger, susun ulang halaman, lalu ekspor hasilnya sebagai PDF baru.',
                    ],
                    [
                        'name' => 'LibreOffice Draw',
                        'license' => 'MPL-2.0',
                        'website' => 'https://www.libreoffice.org/discover/draw/',
                        'description' => 'Editor diagram dan PDF untuk anotasi serta penyuntingan halaman.',
                        'migration_guide' => 'Buka PDF di LibreOffice Draw untuk menambahkan anotasi atau mengedit teks, lalu ekspor kembali ke PDF.',
                    ],
                ],
            ],
        ];

        foreach ($software as $item) {
            $category = Category::where('slug', $item['category'])->firstOrFail();

            $proprietary = ProprietarySoftware::updateOrCreate(
                ['name' => $item['name']],
                [
                    'category_id' => $category->id,
                    'vendor' => $item['vendor'],
                    'price_per_year' => $item['price_per_year'],
                    'description' => $item['description'],
                ],
            );

            foreach ($item['alternatives'] as $alternative) {
                FossAlternative::updateOrCreate(
                    [
                        'proprietary_software_id' => $proprietary->id,
                        'name' => $alternative['name'],
                    ],
                    [
                        'license' => $alternative['license'],
                        'website' => $alternative['website'],
                        'description' => $alternative['description'],
                        'migration_guide' => $alternative['migration_guide'],
                    ],
                );
            }
        }

        User::updateOrCreate(
            ['email' => 'admin@alterfoss.test'],
            [
                'name' => 'Admin AlterFOSS',
                'password' => 'password',
            ],
        );
    }
}
