<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <!-- Success Toast -->
    @if(session('success'))
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1055;">
        <div class="toast align-items-center text-white bg-success border-0" role="alert" data-bs-delay="3000">
            <div class="d-flex">
                <div class="toast-body">
                    Admin {{ session('success') }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
    @endif


    <!-- Error Toast -->
    @if($errors->any())
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1055;">
        <div id="errorToast" class="toast align-items-center text-white bg-danger border-0" role="alert" data-bs-delay="5000">
            <div class="d-flex">
                <div class="toast-body">
                    {{ $errors->first() }} {{-- show first error --}}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>
    @endif



    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Welcome, {{ session('user')->name }}</h2>
            <a href="{{ route('logout') }}" class="btn btn-outline-danger">Logout</a>
        </div>

        <!-- Add Employee Form -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header fw-semibold">{{ isset($editEmployee) ? 'Update Employee' : 'Add New Employee' }}</div>
            <div class="card-body">
                <form method="POST" action="{{ isset($editEmployee) ? route('employee.update', $editEmployee->id) : route('employee.store') }}" id="employeeForm">
                    @csrf
                    @if(isset($editEmployee))
                    @method('PUT')
                    @endif
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Employee ID</label>
                            <input name="employee_id" class="form-control" required value="{{ old('employee_id', $editEmployee->employee_id ?? '') }}">
                        </div>
                        <div class="col">
                            <label class="form-label">Name</label>
                            <input name="name" class="form-control" required value="{{ old('name', $editEmployee->name ?? '') }}">
                        </div>
                        <div class="col">
                            <label class="form-label">Email</label>
                            <input name="email" type="email" class="form-control" required value="{{ old('email', $editEmployee->email ?? '') }}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Date of Birth</label>
                            <input name="dob" type="date" class="form-control" required value="{{ old('dob', $editEmployee->dob ?? '') }}">
                        </div>
                        <div class="col">
                            <label class="form-label">Date of Joining</label>
                            <input name="doj" type="date" class="form-control" required value="{{ old('doj', $editEmployee->doj ?? '') }}">
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            {{ isset($editEmployee) ? 'Update Employee' : 'Add Employee' }}
                        </button>

                        @if(isset($editEmployee))
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancel</a>
                        @endif
                    </div>

                </form>
            </div>
        </div>

        <!-- Employee List Table -->
        <div class="card shadow-sm mb-4">
            <div class="card-header fw-semibold">Employee List</div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Emp ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>DOB</th>
                            <th>DOJ</th>
                            <th>Actions</th>
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

                            <td class="d-flex gap-2">
                                <a href="{{ route('admin.dashboard', ['edit' => $emp->id]) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form method="POST" action="{{ route('employee.delete', $emp->id) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this employee?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No employees found</td>
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('employeeForm').addEventListener('submit', function(e) {
            const dobInput = document.querySelector('input[name="dob"]');
            const dojInput = document.querySelector('input[name="doj"]');

            const dob = new Date(dobInput.value);
            const doj = new Date(dojInput.value);
            const today = new Date();

            // Rule 1: Must be at least 18 years old
            const minAgeDate = new Date();
            minAgeDate.setFullYear(minAgeDate.getFullYear() - 18);
            if (dob > minAgeDate) {
                e.preventDefault();
                alert("Employee must be at least 18 years old.");
                return;
            }

            // Rule 2: DOJ can't be in the future
            if (doj > today) {
                e.preventDefault();
                alert("Date of Joining cannot be in the future.");
                return;
            }

            // Rule 3: DOB must be before DOJ
            if (dob >= doj) {
                e.preventDefault();
                alert("Date of Birth must be before Date of Joining.");
                return;
            }
        });
    </script>
    <script>
        const successToastEl = document.getElementById('successToast');
        if (successToastEl) {
            const toast = new bootstrap.Toast(successToastEl);
            toast.show();
        }

        const errorToastEl = document.getElementById('errorToast');
        if (errorToastEl) {
            const toast = new bootstrap.Toast(errorToastEl);
            toast.show();
        }

        const toastEl = document.querySelector('.toast');
        if (toastEl) {
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
        }
    </script>



</body>

</html>