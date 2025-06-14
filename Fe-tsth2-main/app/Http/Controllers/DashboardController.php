<?php

    namespace App\Http\Controllers;

    use App\Models\Barang;
    use App\Models\Gudang;
    use App\Models\JenisBarang;
    use App\Models\Satuan;
    use App\Models\TransactionType;
    use App\Models\User;
    use App\Services\BarangCategoryService;
    use App\Services\BarangService;
    use App\Services\GudangService;
    use App\Services\JenisBarangService;
    use App\Services\RoleService;
    use App\Services\AuthService;
    use App\Services\SatuanService;
    use App\Services\TransactionService;
    use App\Services\TransactionTypeService;
    use App\Services\UserService;
    use Illuminate\Http\Request;

    class DashboardController extends Controller
    {
        protected $barang_service;
        protected $jenis_barang_service;
        protected $kategori_barang_service;
        protected $satuan_service;
        protected $user_service;
        protected $gudang_service;
        protected $transaksi_service;
        protected $role_service;

        protected $auth_service;

        protected $transactionType_service;

        public function __construct(
            BarangService $barang_service,
            JenisBarangService $jenis_barang_service,
            BarangCategoryService $barang_category_service,
            SatuanService $satuan_service,
            UserService $user_service,
            AuthService $auth_service,
            GudangService $gudang_service,
            TransactionService $transaksi_service,
            RoleService $role_service,
            TransactionTypeService $transactionType_service,


        ) {
            $this->barang_service = $barang_service;
            $this->jenis_barang_service = $jenis_barang_service;
            $this->kategori_barang_service = $barang_category_service;
            $this->satuan_service = $satuan_service;
            $this->user_service = $user_service;
            $this->gudang_service = $gudang_service;
            $this->transaksi_service = $transaksi_service;

            $this->auth_service = $auth_service;
            $this->role_service = $role_service;
            $this->transactionType_service = $transactionType_service;
        }
        public function index()
        {
            $token = session('token');
            $barangs = $this->barang_service->countBarang();
            $jenisbarangs = $this->jenis_barang_service->count();
            $satuans = $this->satuan_service->satuancount();
            $users = $this->user_service->count();
            $gudangs = $this->gudang_service->count();
            $transaksis = $this->transaksi_service->countTransaksi();
            $transactions = $this->transaksi_service->getAllTransactions($token);

            $events = collect();

            foreach ($transactions as $trx) {
                $user = $trx['user']['name'] ?? '-';
                $type = $trx['transaction_type']['name'] ?? '-';
                $created_at = $trx['created_at'];
                $code = $trx['transaction_code'] ?? '-';

                foreach ($trx['items'] as $item) {
                    $barang = $item['barang']['nama'] ?? '-';
                    $gudang = $item['gudang']['nama'] ?? '-';
                    $qty = $item['quantity'] ?? '-';

                    // Event utama
                    $events->push([
                        'title' => "$user - $type",
                        'start' => $created_at,
                        'backgroundColor' => $type === 'Barang Masuk' ? '#28a745' : '#dc3545',
                        'textColor' => '#fff',
                        'extendedProps' => [
                            'type' => $type,
                            'kode' => $code,
                            'barang' => $barang,
                            'jumlah' => $qty,
                            'gudang' => $gudang,
                        ],
                    ]);

                    // Event pengembalian jika ada
                    if (!empty($item['tanggal_kembali'])) {
                        $events->push([
                            'title' => "$user - Pengembalian",
                            'start' => $item['tanggal_kembali'],
                            'backgroundColor' => '#ffc107',
                            'textColor' => '#000',
                            'extendedProps' => [
                                'type' =>  $type,
                                'kode' => $code,
                                'barang' => $barang,
                                'jumlah' => $qty,
                                'gudang' => $gudang,
                            ],
                        ]);
                    }
                }
            }

            // Ambil semua tipe transaksi dari service
            $transactionTypes = $this->transactionType_service->all($token);

            // Inisialisasi array untuk menyimpan hasil count tipe transaksi
            $typeCounts = [];

            // Inisialisasi semua tipe transaksi dari service dengan count 0
            foreach ($transactionTypes as $type) {
                $typeCounts[$type['name']] = 0;
            }

            // Tambahkan 'Pengembalian' khusus
            $typeCounts['Pengembalian'] = 0;
            // Hitung jumlah transaksi per tipe
            foreach ($transactions as $trx) {
                $typeName = $trx['transaction_type']['name'] ?? 'Lainnya';

                // Jika tipe ada di daftar, hitung
                if (array_key_exists($typeName, $typeCounts)) {
                    $typeCounts[$typeName]++;
                } else {
                    // Kalau tipe baru yang belum ada di list, bisa ditambah manual
                    $typeCounts[$typeName] = 1;
                }

                // Cek item yang ada tanggal_kembali untuk hitung pengembalian
                foreach ($trx['items'] as $item) {
                    if (!empty($item['tanggal_kembali'])) {
                        $typeCounts['Pengembalian']++;
                    }
                }
            }
            $roles = $this->role_service->count();
            $barang_category = $this->kategori_barang_service->count();
            $transactionType = $this->transactionType_service->count();

            $allTransactions = $this->transaksi_service->getAllTransactions($token) ?? [];
            $transactionTypes = $this->transactionType_service->all($token);

            // Persiapkan ringkasan transaksi per tipe
            $summaryByType = [];
            foreach ($transactionTypes as $type) {
                $summaryByType[$type['name']] = [];
            }

            foreach ($allTransactions as $trx) {
                $date = substr($trx['created_at'], 0, 10);
                $typeName = $trx['transaction_type']['name'] ?? 'Unknown';
                $summaryByType[$typeName][$date] = ($summaryByType[$typeName][$date] ?? 0) + 1;
            }

            $allDates = collect($allTransactions)
                ->pluck('created_at')
                ->map(fn($d) => substr($d, 0, 10))
                ->unique()
                ->sort()
                ->values();

            return view('frontend.dashboard', compact(
                'transactionType',
                'barangs',
                'barang_category',
                'jenisbarangs',
                'satuans',
                'users',
                'gudangs',
                'transaksis',
                'roles',
                'summaryByType',
                'allDates',
                'allTransactions',
                'transactions',
                'events',
                'typeCounts'
            ));
        }
    }
