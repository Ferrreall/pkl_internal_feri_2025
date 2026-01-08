@extends('layouts.admin')

@section('title', 'Manajemen Kategori')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800 fw-bold">Kategori Produk</h1>
        <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="bi bi-plus-circle me-1"></i> Tambah Kategori
        </button>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 ps-4 py-3">Kategori</th>
                                    <th class="border-0 text-center py-3">Total Produk</th>
                                    <th class="border-0 text-center py-3">Status</th>
                                    <th class="border-0 text-end pe-4 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-45px me-3">
                                                    @if($category->image)
                                                        <img src="{{ Storage::url($category->image) }}" class="rounded-3 object-fit-cover" width="45" height="45" alt="{{ $category->name }}">
                                                    @else
                                                        <div class="bg-light rounded-3 d-flex align-items-center justify-content-center text-muted" style="width: 45px; height: 45px;">
                                                            <i class="bi bi-folder2 fs-4"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="text-dark fw-bold fs-6">{{ $category->name }}</span>
                                                    <span class="text-muted fw-normal small">/{{ $category->slug }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill bg-light text-primary border border-primary px-3">
                                                {{ $category->products_count ?? 0 }} Produk
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($category->is_active)
                                                <span class="badge bg-success-subtle text-success border border-success px-3">Aktif</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border border-danger px-3">Non-Aktif</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-icon btn-light-warning me-2" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editModal{{ $category->id }}"
                                                        title="Edit">
                                                    <i class="bi bi-pencil-square text-warning"></i>
                                                </button>
                                                <form action="{{ route('admin.categories.destroy', $category) }}" 
                                                      method="POST" 
                                                      class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-icon btn-light-danger" title="Hapus">
                                                        <i class="bi bi-trash text-danger"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <img src="{{ asset('assets/img/empty.svg') }}" width="150" class="mb-3 opacity-50">
                                            <p class="text-muted fs-6">Belum ada data kategori yang tersedia.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($categories->hasPages())
                <div class="card-footer bg-white border-0 py-3">
                    {{ $categories->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content border-0 shadow" action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4 text-center">
                    <div class="image-input mb-3">
                        <label for="imageCreate" class="form-label d-block text-muted small">Gambar Cover</label>
                        <input type="file" name="image" id="imageCreate" class="form-control form-control-sm">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Kategori</label>
                    <input type="text" name="name" class="form-control rounded-3" required placeholder="Misal: Alat Tulis Kantor">
                </div>
                <div class="form-check form-switch p-0 ms-4 mt-4">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="activeCreate" checked>
                    <label class="form-check-label ms-2 fw-medium" for="activeCreate">Aktifkan Kategori</label>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>

@foreach($categories as $category)
<div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form class="modal-content border-0 shadow" action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Edit Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-4 text-center">
                    @if($category->image)
                        <img src="{{ Storage::url($category->image) }}" class="rounded-3 shadow-sm mb-3 object-fit-cover" width="100" height="100">
                    @endif
                    <input type="file" name="image" class="form-control form-control-sm">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Kategori</label>
                    <input type="text" name="name" class="form-control rounded-3" value="{{ $category->name }}" required>
                </div>
                <div class="form-check form-switch p-0 ms-4 mt-4">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="activeEdit{{ $category->id }}" {{ $category->is_active ? 'checked' : '' }}>
                    <label class="form-check-label ms-2 fw-medium" for="activeEdit{{ $category->id }}">Aktifkan Kategori</label>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endforeach
@endsection

@push('styles')
<style>
    .btn-icon { width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; border: none; }
    .btn-light-warning { background-color: #fff4e5; }
    .btn-light-danger { background-color: #ffe5e5; }
    .bg-success-subtle { background-color: #e8fff3; }
    .bg-danger-subtle { background-color: #fff5f8; }
    .table thead th { font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.025em; font-weight: 700; color: #7e8299; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data kategori ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            })
        });
    });
</script>
@endpush