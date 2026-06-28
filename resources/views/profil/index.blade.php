@extends('layouts.app')

@section('title', 'Profil Admin')
@section('subtitle', 'Informasi akun dan keamanan sistem')

@section('content')
<div class="page-heading">
    <div>
        <h1>Profil Admin</h1>
        <p>Halaman profil mengikuti referensi desain admin pada file desain.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-4">
        <div class="content-card">
            <div class="content-card-body text-center">
                <div class="avatar mx-auto mb-3" style="width:86px;height:86px;font-size:28px;">{{ strtoupper(substr(auth()->user()->name ?? 'AJ', 0, 2)) }}</div>
                <h5 class="fw-bold mb-1">{{ auth()->user()->name ?? 'Admin' }}</h5>
                <p class="text-muted mb-3">Owner Kost AJ Lanraki</p>
                <span class="badge-status badge-success">Aktif</span>
                <hr>
                <div class="text-start small">
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">Email</span><strong>{{ auth()->user()->email ?? 'admin@kostaj.com' }}</strong></div>
                    <div class="d-flex justify-content-between mb-2"><span class="text-muted">Role</span><strong>Owner</strong></div>
                    <div class="d-flex justify-content-between"><span class="text-muted">Bergabung</span><strong>{{ date('Y') }}</strong></div>
                </div>
            </div>
        </div>

        <div class="finance-panel primary mt-4">
            <div class="label"><span class="material-symbols-outlined">shield_lock</span> Security Status</div>
            <div class="value" style="font-size:18px;">Secure</div>
            <p class="mb-0 mt-2" style="color:#d8e2ff;font-size:12px;">Last login tercatat pada sesi lokal aplikasi.</p>
        </div>
    </div>

    <div class="col-xl-8">
        <div class="content-card mb-4">
            <div class="content-card-header">
                <h5><span class="material-symbols-outlined">password</span> Change Password</h5>
            </div>
            <div class="content-card-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" class="form-control" placeholder="Masukkan password saat ini">
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control" placeholder="Password baru">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" placeholder="Ulangi password baru">
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn-secondary-custom">Batal</button>
                        <button type="button" class="btn-primary-custom">Update Password</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="content-card">
            <div class="content-card-header">
                <h5><span class="material-symbols-outlined">history</span> Recent Login Activity</h5>
                <a href="#" class="btn-secondary-custom">View All</a>
            </div>
            <div class="content-card-body flush">
                <table class="table-modern">
                    <tbody>
                        <tr>
                            <td><span class="badge-status badge-blue">Desktop</span></td>
                            <td>Windows Browser</td>
                            <td class="text-end text-muted">Hari ini, 09.00</td>
                        </tr>
                        <tr>
                            <td><span class="badge-status badge-success">Local</span></td>
                            <td>Laravel Development Server</td>
                            <td class="text-end text-muted">Kemarin, 16.40</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
