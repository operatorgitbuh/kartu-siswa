<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle }}</title>

    <style>
        @font-face {
            font-family: 'Poppins';
            src: url('data:font/truetype;charset=utf-8;base64,{{ base64_encode(file_get_contents(public_path("main/fonts/Poppins-Regular.ttf"))) }}') format('truetype');
            font-weight: 400;
            font-style: normal;
        }

        @font-face {
            font-family: 'Poppins';
            src: url('data:font/truetype;charset=utf-8;base64,{{ base64_encode(file_get_contents(public_path("main/fonts/Poppins-SemiBold.ttf"))) }}') format('truetype');
            font-weight: 600;
            font-style: normal;
        }

        @font-face {
            font-family: 'Poppins';
            src: url('data:font/truetype;charset=utf-8;base64,{{ base64_encode(file_get_contents(public_path("main/fonts/Poppins-Medium.ttf"))) }}') format('truetype');
            font-weight: 500;
            font-style: normal;
        }

        /* body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6 transparent;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        } */

        /* .card-container {
            display: inline-block;
            white-space: nowrap;
            page-break-after: always;
        } */

        /* .ktp-card {
            width: 85.6mm;
            height: 53.98mm;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            display: inline-block;
            margin: 0 10px;
            position: relative;
            overflow: hidden;
            top: -1rem;
            vertical-align: top;
            margin-bottom: 0.90rem;
        } */
        .ktp-card {
            width: 85.6mm;
            height: 53.98mm;
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            box-sizing: border-box;
            display: inline-block;
            margin: 5px;
            position: relative;
            overflow: hidden;
            vertical-align: top;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: white;
            margin-left: 1.5rem;
            padding: 0;
            display: block;
        }

        .card-container {
            display: block;
            margin-bottom: 5px;
            width: 100%;
            clear: both;
        }

        /* .ktp-card {
            width: 85.6mm;
            height: 53.98mm;
            background-color: #ffffff;
            border-radius: 12px;
            box-sizing: border-box;
            display: inline-block;
            margin: 5px;
            position: relative;
            overflow: hidden;
            vertical-align: top;
        } */

        /* .a4-paper {
            width: 210mm;
            margin: auto;
        } */

        /* Styling untuk gambar di dalam kartu */
        .card-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 12px;
            z-index: 1;
        }

        /* Styling untuk teks header di dalam kartu */
        .header {
            z-index: 2;
            width: 100%;
            text-align: center;
            display: flex;
            color: #e5e7eb;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100%;
            /* Memastikan header menempati seluruh ruang */
            position: relative;
        }

        .logosmk {
            position: absolute;
            z-index: 2;
            width: 2.3rem;
            margin-left: 16.7rem;
            margin-top: -3.39rem;
        }

        .logodinas {
            position: absolute;
            z-index: 2;
            width: 2.3rem;
            margin-left: 1.2rem;
            margin-top: -3.2rem;
        }

        .header h5 {
            font-family: 'Poppins', sans-serif;
            font-weight: medium;
            font-size: 10px;
            margin-top: 0.1rem;
            margin-bottom: -0.65rem;
        }

        .header h4 {
            font-family: 'Poppins', sans-serif;
            font-weight: medium;
            font-size: 10px;
            margin-top: 0.3rem;
            margin-bottom: -0.65rem;
        }

        .header h3 {
            font-family: 'Poppins', sans-serif;
            font-weight: medium;
            font-size: 10px;
            margin-top: 0.3rem;
            margin-bottom: -0.70rem;
        }

        .header p {
            font-family: 'Poppins', sans-serif;
            font-size: 6px;
            font-weight: medium;
        }

        .email {
            font-size: 5px;
            margin-top: -0.65rem;
            font-style: italic;
        }

        .isi {
            color: black;

        }

        .isi h5 {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-weight: 600;
            font-size: 12px;
            margin-top: -0.45rem;
            color: #006172;
            /* margin-bottom: 0.1rem; */
        }

        .isi h4 {
            font-size: 9px;
            font-weight: normal;
            margin-top: 0.99rem;
            text-align: left;
            margin-left: 5.2rem;
            line-height: 0.8rem;

        }

        .isi small {
            font-family: 'Poppins', sans-serif;
            font-weight: normal;
            position: absolute;
            z-index: 2;
            margin-top: 0.78rem;
        }

        .jurusan {
            font-size: 9px;
            /* right: 10rem; */
            margin-left: 5.2rem;
        }

        .foto {
            position: absolute;
            z-index: 3;
            width: 4rem;
            height: 5.5rem;
            margin-top: 0.5rem;
            margin-left: 0.8rem;
            border-radius: 5px;
        }

        .qrcode svg {
            position: absolute;
            z-index: 2;
            width: 3.5rem;
            margin-top: -7.5rem;
            margin-left: 15.5rem;
        }

        .expired {
            z-index: 3;
            position: absolute;
            text-align: left;
            margin-left: 1.3rem;
            margin-top: 2.4rem;
        }

        .expired h5 {
            font-family: 'Poppins', sans-serif;
            font-weight: normal;
            font-size: 7px;
            color: black;
            font-style: italic;
        }

        .expired h4 {
            font-family: 'Poppins', sans-serif;
            font-weight: normal;
            font-size: 7px;
            margin-left: 0.3rem;
            margin-top: 0.4rem;
            /* text-decoration: underline; */
            /* text-decoration-color: black; */
            color: black;
        }

        .barcode {
            z-index: 3;
            position: absolute;
            align-items: center;
            margin-left: 2.5rem;
            margin-top: 1.3rem;
        }

        .barcode img {
            width: 5rem;
            height: 1.3rem;
        }

        .kepsek {
            position: absolute;
            z-index: 3;
            color: black;
            text-align: left;
            font-size: 8px;
            line-height: 0.5rem;
            margin-top: 1.7rem;
            margin-left: 12.5rem;
        }

        .cap {
            position: absolute;
            z-index: 3;
        }

        .cap img {
            margin-top: 1.7rem;
            margin-left: 10.2rem;
            width: 3rem;
        }

        .ttd {
            position: absolute;
            z-index: 3;
        }

        .ttd img {
            margin-top: 1.7rem;
            margin-left: 12.2rem;
            width: 3rem;
        }

        .syarat {
            z-index: 2;
            position: absolute;
            text-align: center;
            display: flex;
            color: #e5e7eb;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin-left: 4.545rem;
            margin-top: -0.5rem;
        }

        .syarat h5 {
            font-weight: 700;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
        }

        ul {
            position: relative;
            z-index: 1;
            margin-top: 5rem;
            padding-left: 15px;
            text-align: left;
            font-size: 9px;
            color: #000000;
            margin-left: 0.9rem;
            margin-right: 1rem;
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            line-height: 1rem;
            font-style: normal;
            margin-bottom: 2rem;
        }

        .informasi {
            position: absolute;
            z-index: 2;

        }

        .informasi h4 {
            font-family: 'Poppins', sans-serif;
            font-weight: bold;
            font-size: 7.4px;
            color: #ffffff;
            margin-top: -0.5rem;
            text-align: center;
            margin-left: 1.4rem;
            background-color: #006172;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <!-- Kertas A4 virtual yang berfungsi sebagai latar belakang -->
    <div class="a4-paper">
        <!-- Container untuk kedua kartu -->
        <div class="card-container">
            <!-- Kartu Depan dengan tag img -->
            <div class="ktp-card">
                <img src="{{ public_path('storage/' . ($backgrounds->background_front ?? 'default.jpg')) }}"
                    alt="Latar Belakang Kartu Depan" class="card-image">
                <div class="header">
                    <h5>{{ $DownloadPDF->school->pemerintah_provinsi }}</h5>
                    <h4>{{ $DownloadPDF->school->instansi_pemerintah }}</h4>
                    <h3>{{ $DownloadPDF->school->nama_sekolah }}</h3>
                    <p>{{ $DownloadPDF->school->alamat_sekolah }}</p>
                    {{-- <p >Jl. Brawijaya, Harapan, Wonosari, Boalemo, Prov Gorontalo</p> --}}
                    <p class="email">email : {{ $DownloadPDF->school->email_sekolah }} website :
                        {{ $DownloadPDF->school->website_sekolah }}</p>
                    <img src="{{ public_path('storage/' . $DownloadPDF->school->logo_sekolah) }}" alt="logosmk"
                        class="logosmk">
                    <img src="{{ public_path('storage/' . $DownloadPDF->school->logo_provinsi) }}" alt="logodinas"
                        class="logodinas">
                    <div class="isi">
                        <h5>KARTU PELAJAR</h5>
                        <img src="{{ public_path('storage/' . $DownloadPDF->foto) }}" alt="foto" class="foto">
                        <h4>{{ $DownloadPDF->student->name }}</h4>
                        <h4>{{ $DownloadPDF->student->nipd }}/{{ $DownloadPDF->student->nisn }}</h4>
                        <h4>{{ $DownloadPDF->student->jenis_kelamin }} ({{ $DownloadPDF->student->agama }})</h4>
                        <h4>{{ $DownloadPDF->student->tempat_lahir }},
                            {{ \Carbon\Carbon::parse($DownloadPDF->student->tanggal_lahir)->locale('id')->format('d F Y') }}
                        </h4>
                        <small class="jurusan">{{ $DownloadPDF->student->classroom->name_classroom }}</small>
                    </div>
                    <div class="qrcode">
                        {{-- <img src="{{public_path('storage/' .$DownloadPDF->foto)}}" alt="foto" class="qrcode"> --}}
                        {{ $qrcode }}
                    </div>
                    <div class="expired">
                        <h5>Masa Berlaku</h5>
                        <h4>{{ \Carbon\Carbon::parse($DownloadPDF->exp_date)->format('d-m-Y') }}</h4>
                    </div>
                    <div class="barcode">
                        <img src="data:image/png;base64, {{ $barcode }}" alt="Barcode" class="barcode" />
                    </div>
                    <div class="kepsek">
                        Kepala Sekolah
                        <br>
                        SMK Negeri 1 Wonosari
                        <br>
                        <br>
                        <br>
                        {{ $DownloadPDF->school->nama_kepsek }}
                        <br>
                        Nip.{{ $DownloadPDF->school->nip_kepsek }}
                    </div>
                    <div class="cap">
                        <img src="{{ public_path('storage/' . $DownloadPDF->school->cap_sekolah) }}" alt=""
                            class="cap">
                    </div>
                    <div class="ttd">
                        <img src="{{ public_path('storage/' . $DownloadPDF->school->ttd_kepsek) }}" alt=""
                            class="ttd">
                    </div>
                </div>
            </div>

            <!-- Kartu Belakang dengan tag img -->
            <div class="ktp-card">
                <img src="{{ public_path('storage/' . ($backgrounds->background_back ?? 'default.jpg')) }}"
                    alt="Latar Belakang Kartu Belakang" class="card-image">
                <div class="syarat">
                    <h5>SYARAT DAN KETENTUAN</h5>
                </div>
                <div class="ul">
                    <ul class="ul">
                        <li>Sebagai kartu identitas Peserta Didik SMK Negeri 1 Wonosari</li>
                        <li>Apabila hilang, segera melapor dan dapat dibuatkan duplikat</li>
                        <li>Apabila menemukan kartu ini, harap dikembalikan ke</li>SMK Negeri 1 Wonosari
                        {{-- <li>Verifikasi Keaktifan Kartu di website kartu.smkn1wonosari.my.id</li> --}}
                    </ul>
                </div>
                <div class="informasi">
                    <h4>website : {{ $DownloadPDF->school->website_sekolah }} | email :
                        {{ $DownloadPDF->school->email_sekolah }}</h4>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
