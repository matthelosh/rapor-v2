import { usePage } from "@inertiajs/vue3";

const page = usePage();

export const cssUrl = () =>
    page.props.app_env == "local"
        ? page.props.appUrl + ":5173/resources/css/app.css"
        : "/build/assets/app.css";

export const siswaHeaders = () => {
    return [
        "no",
        "nama",
        "nipd",
        "jk",
        "nisn",
        "tempat_lahir",
        "tanggal_lahir",
        "nik",
        "agama",
        "alamat",
        "rt",
        "rw",
        "dusun",
        "kelurahan",
        "kecamatan",
        "kode_pos",
        "jenis_tinggal",
        "alat_transportasi",
        "telepon",
        "hp",
        "email",
        "skhun",
        "penerima_kps",
        "no_kps",
        "nama_ayah",
        "tahun_lahir_ayah",
        "nama_ibu",
        "tahun_lahir_ibu",
        "jenjang_pendidikan_ibu",
        "pekerjaan_ibu",
        "penghasilan_ibu",
        "nik_ibu",
        "nama_wali",
        "tahun_lahir_wali",
        "jenjang_pendidikan_wali",
        "pekerjaan_wali",
        "penghasilan_wali",
        "nik_wali",
        "rombel_saat_ini",
        "no_peserta_ujian_nasional",
        "no_seri_ijazah",
        "penerima_kip",
        "nomor_kip",
        "nama_di_kip",
        "nomor_kks",
        "no_registrasi_akta_lahir",
        "bank",
        "nomor_rekening_bank",
        "rekening_atas_nama",
        "layak_pip",
        "alasan_layak_pip",
        "kebutuhan_khusus",
        "sekolah_asal",
        "anak_ke",
        "lintang",
        "bujur",
        "no_kk",
        "berat_badan",
        "tinggi_badan",
        "lingkar_kepala",
        "jml_saudara",
        "jarak_rumah",
    ];
};

export default { cssUrl, siswaHeaders };
