@extends('layout.app')

@section('content')
    <div class="app-content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">SQL SERVER LINUX</h3>
                </div>
                <div class="col-sm-6">
                    {{-- <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Files</li>
                    </ol> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-3">
                <!-- Folder tree -->
                <div class="col-lg-3">
                    <div class="card mb-3 d-grid">
                        <div class="card-body">
                            <p class="fw-semibold small">
                                <i class="bi bi-folder2-open me-1" aria-hidden="true"></i>
                                Save Location Backup Database is Linux Server:
                            </p>
                            <form>

                                <div class="mb-3" bis_skin_checked="1">
                                    <div class="input-group mb-3" bis_skin_checked="1">
                                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-folder"
                                                aria-hidden="true"></i></span>
                                        <input type="text" name="backup_path" class="form-control"
                                            placeholder="Path Backup Database" aria-label="Path Backup Database"
                                            value="/home/BackupDB/" aria-describedby="basic-addon1">
                                    </div>
                                </div>

                                <div class="card-footer" bis_skin_checked="1">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-body">
                            <p class="fw-semibold small">
                                <i class="bi bi-key-fill me-1" aria-hidden="true"></i>
                                Kredensial SFTP/SSH Linux:
                            </p>
                            <form>

                                <div class="mb-3" bis_skin_checked="1">
                                    <div class="input-group mb-3" bis_skin_checked="1">
                                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-person-fill"
                                                aria-hidden="true"></i></span>
                                        <input type="text" name="ssh_username" class="form-control"
                                            placeholder="SSH Username" aria-label="SSH Username"
                                            aria-describedby="basic-addon1">
                                    </div>
                                    <div class="input-group mb-3" bis_skin_checked="1">
                                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-lock-fill"
                                                aria-hidden="true"></i></span>
                                        <input type="password" name="ssh_password" class="form-control"
                                            placeholder="SSH Password" aria-label="SSH Password"
                                            aria-describedby="basic-addon1">
                                    </div>
                                    <div class="input-group mb-3" bis_skin_checked="1" style="width: 120px;">
                                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-hdd-network"
                                                aria-hidden="true"></i></span>
                                        <input type="number" name="ssh_port" class="form-control" placeholder="Port"
                                            value="22" aria-label="Port" aria-describedby="basic-addon1" min="1"
                                            max="65535">
                                    </div>
                                </div>

                                <div class="card-footer" bis_skin_checked="1">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-body">
                            <p class="fw-semibold mb-2 small">
                                <i class="bi bi-cloud me-1" aria-hidden="true"></i>
                                Storage
                            </p>
                            <div class="progress mb-2" style="height: 8px">
                                <div class="progress-bar" role="progressbar" style="width: 62%" aria-valuenow="62"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <small class="text-secondary"> 6.2 GB of 10 GB used </small>
                        </div>
                    </div>
                </div>

                <!-- File browser -->
                <div class="col-lg-9">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"> SQL Server Databases</h3>
                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 16rem">
                                    <span class="input-group-text">
                                        <i class="bi bi-search" aria-hidden="true"></i>
                                    </span>
                                    <input id="table-filter" type="search" class="form-control"
                                        placeholder="Filter rows…" aria-label="Filter rows" />
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex gap-2 mb-3">
                                <button id="export-csv" type="button" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-filetype-csv me-1" aria-hidden="true"></i>
                                    Export CSV
                                </button>
                                <button id="export-json" type="button" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-filetype-json me-1" aria-hidden="true"></i>
                                    Export JSON
                                </button>
                                <button id="print-table" type="button" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-printer me-1" aria-hidden="true"></i>
                                    Print
                                </button>
                            </div>
                            <button id="btnBackup" class="btn btn-success mb-3">
                                <i class="bi bi-download"></i>
                                Backup Selected
                            </button>

                            <div id="users-table"></div>
                        </div>
                        <div class="card-footer text-secondary small">
                            Powered by
                            <a href="https://tabulator.info/" target="_blank" rel="noopener">Tabulator</a>
                            &mdash; vanilla JS, no jQuery required.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        const statusBadge = (cell) => {
            const value = cell.getValue();
            const map = {
                Active: 'success',
                Invited: 'info',
                Suspended: 'secondary'
            };
            const color = map[value] || 'secondary';
            return `<span class="badge text-bg-${color}">${value}</span>`;
        };

        const databases = @json($databases);

        document.addEventListener('DOMContentLoaded', () => {
            const data = databases.map((db, index) => ({
                id: index + 1,
                database: db.name
            }));

            const table = new Tabulator('#users-table', {
                data: data,
                layout: 'fitColumns',
                pagination: true,
                paginationSize: 10,
                paginationSizeSelector: [10, 25, 50, 100],
                movableColumns: true,
                columns: [{
                        title: "",
                        field: "select",
                        width: 60,
                        formatter: function(cell) {

                            return `
            <input
                type="checkbox"
                class="db-checkbox"
                value="${cell.getRow().getData().database}">
        `;
                        }
                    },
                    {
                        title: "No",
                        field: "id",
                        width: 80
                    },
                    {
                        title: "Database Name",
                        field: "database",
                        headerFilter: "input"
                    }
                ]
            });

            document.getElementById('table-filter').addEventListener('input', (e) => {
                const value = e.target.value;
                if (value) {
                    table.setFilter([
                        [{
                            field: 'database',
                            type: 'like',
                            value: value
                        }],
                    ]);
                } else {
                    table.clearFilter();
                }
            });

            document
                .getElementById('export-csv')
                .addEventListener('click', () => table.download('csv', 'users.csv'));
            document
                .getElementById('export-json')
                .addEventListener('click', () => table.download('json', 'users.json'));
            document
                .getElementById('print-table')
                .addEventListener('click', () => table.print(false, true));
        });

        document
            .getElementById('btnBackup')
            .addEventListener('click', () => {

                let selected = [];

                document
                    .querySelectorAll('.db-checkbox:checked')
                    .forEach(item => {

                        selected.push(item.value);

                    });

                console.log(selected);

            });
    </script>
@endsection
