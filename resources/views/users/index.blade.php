@extends('layouts.app')

@section('title', 'Manajemen User - BEM Management System')
@section('page-title', 'Manajemen User')

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h2 style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin-bottom: 0.25rem;">
            Manajemen User
        </h2>
        <p style="color: #6b7280; font-size: 0.9rem;">Kelola user viewer yang dapat mengakses sistem</p>
    </div>
    <button type="button" class="btn btn-primary" onclick="document.getElementById('tambahUserModal').style.display='flex'">
        <i class="fas fa-plus"></i> Tambah User
    </button>
</div>

<!-- Statistik -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
    <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb; border-left: 4px solid #667eea;">
        <div style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">Total Admin</div>
        <div style="font-size: 1.75rem; font-weight: 700; color: #667eea;">{{ $adminCount }}</div>
    </div>
    <div style="background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb; border-left: 4px solid #f093fb;">
        <div style="color: #6b7280; font-size: 0.9rem; margin-bottom: 0.5rem;">Total User Viewer</div>
        <div style="font-size: 1.75rem; font-weight: 700; color: #f093fb;">{{ $users->total() }}</div>
    </div>
</div>

<!-- Tabel User -->
<div style="background: white; border-radius: 0.75rem; overflow: hidden; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb;">
    @if($users->count() > 0)
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f9fafb; border-bottom: 2px solid #e5e7eb;">
                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.85rem;">Nama</th>
                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.85rem;">Email</th>
                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.85rem;">Tanggal Lahir</th>
                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.85rem;">Password</th>
                <th style="padding: 1rem; text-align: left; font-weight: 600; color: #374151; font-size: 0.85rem;">Status</th>
                <th style="padding: 1rem; text-align: center; font-weight: 600; color: #374151; font-size: 0.85rem;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr style="border-bottom: 1px solid #e5e7eb; {{ $loop->odd ? '' : 'background-color: #f9fafb;' }}">
                <td style="padding: 1rem; color: #1f2937; font-weight: 500;">{{ $user->name }}</td>
                <td style="padding: 1rem; color: #6b7280;">{{ $user->email }}</td>
                <td style="padding: 1rem; color: #6b7280;">{{ $user->tanggal_lahir->translatedFormat('d F Y') }}</td>
                <td style="padding: 1rem;">
                    <code style="background: #f3f4f6; padding: 0.25rem 0.75rem; border-radius: 0.25rem; color: #667eea; font-size: 0.85rem;">
                        {{ $user->tanggal_lahir->format('dmY') }}
                    </code>
                </td>
                <td style="padding: 1rem;">
                    <span style="background-color: #dbeafe; color: #0c2340; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.85rem; font-weight: 500;">
                        Viewer
                    </span>
                </td>
                <td style="padding: 1rem; text-align: center;">
                    <button type="button" class="btn" style="background: none; color: #667eea; padding: 0.25rem 0.5rem; border: none; cursor: pointer; font-size: 0.9rem;" onclick="editUser({{ $user->id }})">
                        <i class="fas fa-edit"></i>
                    </button>
                    <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn" style="background: none; color: #ef4444; padding: 0.25rem 0.5rem; border: none; cursor: pointer; font-size: 0.9rem;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="padding: 3rem; text-align: center; color: #6b7280;">
        <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
        <p>Belum ada user viewer. Silakan tambahkan user baru.</p>
    </div>
    @endif
</div>

<!-- Pagination -->
@if($users->hasPages())
<div style="margin-top: 1.5rem; display: flex; justify-content: center;">
    {{ $users->links() }}
</div>
@endif

<!-- Modal Tambah User -->
<div id="tambahUserModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 1rem; padding: 2rem; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #e5e7eb; padding-bottom: 1rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin: 0;">Tambah User Viewer</h3>
            <button type="button" onclick="closeUserModals()" style="background: none; border: none; cursor: pointer; color: #6b7280; font-size: 1.5rem;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div style="background: #dbeafe; border: 1px solid #bfdbfe; color: #0c2340; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; font-size: 0.9rem;">
            <i class="fas fa-info-circle"></i> Password otomatis dibuat dari tanggal lahir (Format: DDMMYYYY)
        </div>
        
        <form id="formTambahUser" onsubmit="submitFormUser(event)">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Lengkap *</label>
                <input type="text" name="name" class="form-input" placeholder="Masukkan nama lengkap..." required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Email (Username) *</label>
                <input type="email" name="email" class="form-input" placeholder="contoh@email.com" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Tanggal Lahir *</label>
                <input type="date" name="tanggal_lahir" class="form-input" required>
                <small style="color: #6b7280; display: block; margin-top: 0.5rem;">Password akan menjadi: <strong id="previewPassword">DDMMYYYY</strong></small>
            </div>
            
            <div id="formErrorUser" style="display: none; background: #fee2e2; color: #7f1d1d; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;"></div>
            
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeUserModals()">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Tambah User
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit User -->
<div id="editUserModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; border-radius: 1rem; padding: 2rem; max-width: 600px; width: 90%; max-height: 90vh; overflow-y: auto; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; border-bottom: 1px solid #e5e7eb; padding-bottom: 1rem;">
            <h3 style="font-size: 1.25rem; font-weight: 600; color: #1f2937; margin: 0;">Edit User Viewer</h3>
            <button type="button" onclick="closeUserModals()" style="background: none; border: none; cursor: pointer; color: #6b7280; font-size: 1.5rem;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div style="background: #dbeafe; border: 1px solid #bfdbfe; color: #0c2340; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; font-size: 0.9rem;">
            <i class="fas fa-info-circle"></i> Password akan diperbarui sesuai tanggal lahir baru
        </div>
        
        <form id="formEditUser" onsubmit="submitFormEditUser(event)">
            @csrf
            @method('PUT')
            <input type="hidden" id="editUserId" name="id">
            
            <div class="form-group">
                <label class="form-label">Nama Lengkap *</label>
                <input type="text" id="editName" name="name" class="form-input" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Email (Username) *</label>
                <input type="email" id="editEmail" name="email" class="form-input" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Tanggal Lahir *</label>
                <input type="date" id="editTanggalLahir" name="tanggal_lahir" class="form-input" required onchange="updatePasswordPreview('editPassword', this.value)">
                <small style="color: #6b7280; display: block; margin-top: 0.5rem;">Password akan menjadi: <strong id="editPassword">DDMMYYYY</strong></small>
            </div>
            
            <div id="formErrorEditUser" style="display: none; background: #fee2e2; color: #7f1d1d; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;"></div>
            
            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeUserModals()">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Perbarui User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function closeUserModals() {
    document.getElementById('tambahUserModal').style.display = 'none';
    document.getElementById('editUserModal').style.display = 'none';
}

function updatePasswordPreview(elementId, dateString) {
    if (!dateString) return;
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    const password = day + month + year;
    document.getElementById(elementId).textContent = password;
}

// Update preview saat user menginput tanggal di form tambah
document.addEventListener('DOMContentLoaded', function() {
    const addTanggalInput = document.querySelector('#formTambahUser input[name="tanggal_lahir"]');
    if (addTanggalInput) {
        addTanggalInput.addEventListener('change', function() {
            updatePasswordPreview('previewPassword', this.value);
        });
    }
});

function editUser(id) {
    fetch(`/users/${id}/data`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('editUserId').value = data.id;
            document.getElementById('editName').value = data.name;
            document.getElementById('editEmail').value = data.email;
            document.getElementById('editTanggalLahir').value = data.tanggal_lahir;
            updatePasswordPreview('editPassword', data.tanggal_lahir);
            document.getElementById('editUserModal').style.display = 'flex';
        });
}

function submitFormUser(e) {
    e.preventDefault();
    const formData = new FormData(document.getElementById('formTambahUser'));
    formData.append('role', 'viewer');
    
    fetch('{{ route('users.store') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeUserModals();
            location.reload();
        } else {
            document.getElementById('formErrorUser').innerHTML = data.message;
            document.getElementById('formErrorUser').style.display = 'block';
        }
    })
    .catch(error => {
        document.getElementById('formErrorUser').innerHTML = 'Terjadi kesalahan: ' + error;
        document.getElementById('formErrorUser').style.display = 'block';
    });
}

function submitFormEditUser(e) {
    e.preventDefault();
    const id = document.getElementById('editUserId').value;
    const formData = new FormData(document.getElementById('formEditUser'));
    
    fetch(`/users/${id}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeUserModals();
            location.reload();
        } else {
            document.getElementById('formErrorEditUser').innerHTML = data.message;
            document.getElementById('formErrorEditUser').style.display = 'block';
        }
    })
    .catch(error => {
        document.getElementById('formErrorEditUser').innerHTML = 'Terjadi kesalahan: ' + error;
        document.getElementById('formErrorEditUser').style.display = 'block';
    });
}
</script>
@endsection
