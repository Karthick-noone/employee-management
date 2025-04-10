<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">


@if(session('success'))
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1055;">
        <div class="toast align-items-center text-white bg-success border-0" role="alert" data-bs-delay="3000">
            <div class="d-flex">
                <div class="toast-body">
                   User {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
@endif
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Welcome, <span>{{ session('user')->name }}</span></h2>
            <a href="{{ route('logout') }}" class="btn btn-outline-danger">Logout</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-header fw-semibold">
                Employee List
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>DOB</th>
                            <th>DOJ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $emp)
                        <tr>
                        <td>{{ ($employees->currentPage() - 1) * $employees->perPage() + $loop->iteration }}</td>
                            <td>{{ $emp->employee_id }}</td>
                            <td>{{ $emp->name }}</td>
                            <td>{{ $emp->email }}</td>
                            <td>{{ \Carbon\Carbon::parse($emp->dob)->format('d M Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($emp->doj)->format('d M Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">No employees found.</td>
                        </tr>
                        @endforelse
                    </tbody>
            
                </table>
                @if ($employees->hasPages())
    <div class="p-3">
        {{ $employees->links() }}
    </div>
@endif
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    const toastEl = document.querySelector('.toast');
    if (toastEl) {
        const toast = new bootstrap.Toast(toastEl);
        toast.show();
    }
</script>
</body>
</html>
