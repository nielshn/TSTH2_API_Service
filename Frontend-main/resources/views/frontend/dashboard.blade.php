@extends('layouts.main')

@section('content')
    <div class="row">
        <!-- Statistik Barang -->
        <div class="col-lg-3 mb-0">
            <a href="{{ route('barangs.index') }}">
                <a href="{{ route('barangs.index') }}">
                    <div class="card bg-teal text-white shadow">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-box-seam fs-1"></i>
                            </div>
                            <div>
                                <h3 class="mb-4">{{ $barangs }}</h3>
                                <div>Barang</div>
                            </div>
                        </div>
                    </div>
                </a>
        </div>
        @can('view_jenis_barang')
            <div class="col-lg-3 mb-0">
                <a href="{{ route('jenis-barangs.index') }}">
                    <div class="card bg-primary text-white shadow">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-tags fs-1"></i>
                            </div>
                            <div>
                                <h3 class="mb-4">{{ $jenisbarangs }}</h3>
                                <div>Jenis Barang</div>

                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endcan

        <!-- Statistik Transaksi -->
        <div class="col-lg-3 mb-0">
            <a href="{{ route('transactions.index') }}">
            <div class="card bg-pink text-white shadow">
                    <div class="card-body d-flex align-items-center">
                        <div class="me-3">
                            <i class="bi bi-arrow-left-right fs-1"></i>
                        </div>
                        <div>
                            <h3 class="mb-4">{{ $transaksis }}</h3>
                            <div>Transaksi</div>

                            <div class="dropdown d-inline-flex ms-auto">
                                <a href="#" class="text-white d-inline-flex align-items-center dropdown-toggle"
                                    data-bs-toggle="dropdown">
                                    <i class="ph-gear"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="#" class="dropdown-item">
                                        <i class="ph-chart-line me-2"></i> Statistics
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        @can('view_satuan')
            <div class="col-lg-3 mb-0">
                <a href="{{ route('satuans.index') }}">
                    <div class="card bg-success text-white shadow">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-bounding-box fs-1"></i>
                            </div>
                            <div>
                                <h3 class="mb-4">{{ $satuans }}</h3>
                                <div>Satuan</div>

                            </div>
                        </div>

                    </div>
                </a>
            </div>
        @endcan

        @can('view_user')
            <div class="col-lg-3 mb-0">
                <a href="{{ route('users.index') }}">
                    <div class="card bg-info text-white shadow">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-people fs-1"></i>
                            </div>
                            <div>
                                <h3 class="mb-4">{{ $users }}</h3>
                                <div>User</div>

                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endcan

        @can('view_gudang')
            <div class="col-lg-3 mb-0">
                <a href="{{ route('gudangs.index') }}">
                    <div class="card bg-warning text-white shadow">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-house-door fs-1"></i>
                            </div>
                            <div>
                                <h3 class="mb-4">{{ $gudangs }}</h3>
                                <div>Gudang</div>

                            </div>

                        </div>
                    </div>
                </a>
            </div>
        @endcan

        @can('view_role')
            <div class="col-lg-3 mb-0">
                <a href="{{ route('roles.index') }}">
                    <div class="card bg-secondary text-white shadow">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-person-badge fs-1"></i>
                            </div>
                            <div>
                                <h3 class="mb-4">{{ $roles }}</h3>
                                <div>Role</div>

                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endcan

        @can('view_transaction_type')
            <div class="col-lg-3 mb-0">
                <a href="{{ route('transaction-types.index') }}">
                    <div class="card bg-dark text-white shadow">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-list-columns-reverse fs-1"></i>
                            </div>
                            <div>
                                <h3 class="mb-4">{{ $transactionType }}</h3>
                                <div>Jenis Transaksi</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endcan

        @can('view_category_barang')
            <div class="col-lg-3 mb-0">
                <a href="{{ route('barang-categories.index') }}">
                    <div class="card bg-danger text-white shadow">
                        <div class="card-body d-flex align-items-center">
                            <div class="me-3">
                                <i class="bi bi-collection fs-1"></i>
                            </div>
                            <div>
                                <h3 class="mb-4">{{ $barang_category }}</h3>
                                <div>Kategori Barang</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        @endcan
    </div>

    <!-- Grafik Transaksi -->
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card shadow-sm mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Grafik Transaksi</h5>
                    <div class="d-flex gap-2">
                        <input type="date" id="startDate" class="form-control" />
                        <input type="date" id="endDate" class="form-control" />
                    </div>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 450px; width: 100%;">
                        <canvas id="transaksiChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kalender Transaksi -->
    <div class="card shadow-sm mt-3">
        <div class="card-header">
            <h5 class="mb-0">Kalender Transaksi</h5>
        </div>
        <div class="card-body">
            <div id="calendar"></div>
        </div>
    </div>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.API_BASE_URL = "{{ config('api.base_url') }}";
    </script>
    <script>
        const summaryByType = @json($summaryByType);
        const colors = ['#4BC0C0', '#FF6384', '#FFCE56', '#36A2EB', '#9966FF', '#FF9F40', '#00A36C'];
        const typeList = Object.keys(summaryByType);

        function filterDataByRange(start, end) {
            const labelsSet = new Set();

            typeList.forEach(type => {
                Object.keys(summaryByType[type]).forEach(date => {
                    if ((!start || date >= start) && (!end || date <= end)) {
                        labelsSet.add(date);
                    }
                });
            });

            const labels = Array.from(labelsSet).sort();

            const datasets = typeList.map((type, index) => {
                const data = labels.map(label => summaryByType[type][label] || 0);
                return {
                    label: type,
                    data,
                    backgroundColor: colors[index % colors.length],
                    borderColor: colors[index % colors.length],
                    fill: false,
                    tension: 0.3,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    pointBackgroundColor: colors[index % colors.length],
                    borderWidth: 2,
                    spanGaps: true,
                };
            });

            return {
                labels,
                datasets
            };
        }

        const ctx = document.getElementById('transaksiChart').getContext('2d');
        let transaksiChart = new Chart(ctx, {
            type: 'line',
            data: filterDataByRange('', ''),
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 20,
                            padding: 15,
                        }
                    },
                    tooltip: {
                        mode: 'nearest',
                        intersect: false,
                        callbacks: {
                            label: function(ctx) {
                                return ctx.dataset.label + ': ' + ctx.parsed.y;
                            }
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false,
                },
                scales: {
                    x: {
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45,
                            autoSkip: true,
                            maxTicksLimit: 15,
                        },
                        title: {
                            display: true,
                            text: 'Tanggal'
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Transaksi'
                        }
                    }
                }
            }
        });

        document.getElementById('startDate').addEventListener('change', updateChart);
        document.getElementById('endDate').addEventListener('change', updateChart);

        function updateChart() {
            const start = document.getElementById('startDate').value;
            const end = document.getElementById('endDate').value;
            const {
                labels,
                datasets
            } = filterDataByRange(start, end);
            transaksiChart.data.labels = labels;
            transaksiChart.data.datasets = datasets;
            transaksiChart.update();
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                height: 'auto',
                events: @json($events),
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                eventDidMount: function(info) {
                    const {
                        kode,
                        barang,
                        jumlah,
                        gudang,
                        type
                    } = info.event.extendedProps;

                    const tooltipContent =
                        `<strong>Kode:</strong> ${kode}<br>` +
                        `<strong>Barang:</strong> ${barang}<br>` +
                        `<strong>Jumlah:</strong> ${jumlah}<br>` +
                        `<strong>Gudang:</strong> ${gudang}<br>` +
                        `<strong>Tipe:</strong> ${type}`;

                    if (window.tippy) {
                        tippy(info.el, {
                            content: tooltipContent,
                            allowHTML: true,
                            theme: 'light',
                            placement: 'top',
                            arrow: true,
                        });
                    } else {
                        info.el.setAttribute('title', tooltipContent.replace(/<br>/g, '\n'));
                    }
                }
            });

            calendar.render();
        });
    </script>

    <script src="https://unpkg.com/tippy.js@6"></script>
@endpush
