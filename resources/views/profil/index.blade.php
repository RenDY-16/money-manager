@extends('layouts.app')

@section('title', 'Profil Admin')
@section('subtitle', 'Informasi akun, foto profil, dan keamanan sistem')

@section('content')
@php($user = auth()->user())
<div class="page-heading">
    <div>
        <h1>Profil Admin</h1>
        <p>Kelola identitas admin, foto profil, email, dan password login.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-4">
        <div class="content-card">
            <div class="content-card-body text-center">
                <div class="avatar mx-auto mb-3" style="width:96px;height:96px;font-size:30px;">
                    @if($user?->profile_photo)
                        <img src="{{ asset($user->profile_photo) }}" alt="Foto admin" class="profile-avatar-img">
                    @else
                        {{ strtoupper(substr($user->name ?? 'AJ', 0, 2)) }}
                    @endif
                </div>
                <h5 class="fw-bold mb-1">{{ $user->name ?? 'Admin' }}</h5>
                <p class="text-muted mb-3">Owner Kost AJ Lanraki</p>
                <span class="badge-status badge-success">Aktif</span>
                <hr>
                <div class="text-start small">
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">Email</span><strong>{{ $user->email ?? 'admin@kostaj.com' }}</strong></div>
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">Role</span><strong>Owner</strong></div>
                    <div class="d-flex justify-content-between"><span class="text-muted">Bergabung</span><strong>{{ optional($user->created_at)->format('Y') ?? date('Y') }}</strong></div>
                </div>
            </div>
        </div>

        <div class="finance-panel primary mt-4">
            <div class="label"><span class="material-symbols-outlined">shield_lock</span> Security Status</div>
            <div class="value" style="font-size:18px;">Secure</div>
            <p class="mb-0 mt-2" style="color:#d8e2ff;font-size:12px;">Gunakan password kuat dan ganti berkala untuk menjaga akses admin.</p>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="content-card mb-4">
            <div class="content-card-header">
                <h5><span class="material-symbols-outlined">manage_accounts</span> Edit Profil Admin</h5>
            </div>
            <div class="content-card-body">
                <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Admin</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Admin</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Foto Profil Admin</label>
                            <input type="file" name="profile_photo" class="form-control" accept="image/png,image/jpeg,image/webp">
                            <div class="form-text text-muted">Format jpg, jpeg, png, atau webp. Maksimal 2 MB.</div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <a href="{{ route('profil.index') }}" class="btn-secondary-custom">Batal</a>
                        <button type="submit" class="btn-primary-custom">Simpan Profil</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="content-card mb-4">
            <div class="content-card-header">
                <h5><span class="material-symbols-outlined">password</span> Ganti Password</h5>
            </div>
            <div class="content-card-body">
                <form action="{{ route('profil.password.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Password Saat Ini</label>
                        <input type="password" name="current_password" class="form-control" placeholder="Masukkan password saat ini" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru" required>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="reset" class="btn-secondary-custom">Reset</button>
                        <button type="submit" class="btn-primary-custom">Update Password</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="content-card">
            <div class="content-card-header">
                <h5><span class="material-symbols-outlined">history</span> Aktivitas Akun</h5>
            </div>
            <div class="content-card-body flush">
                <table class="table-modern">
                    <tbody>
                        <tr>
                            <td><span class="badge-status badge-blue">Login</span></td>
                            <td>Sesi admin aktif</td>
                            <td class="text-end text-muted">{{ now()->locale('id')->translatedFormat('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td><span class="badge-status badge-success">Akun</span></td>
                            <td>Data admin dapat diperbarui melalui halaman ini</td>
                            <td class="text-end text-muted">Aktif</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
