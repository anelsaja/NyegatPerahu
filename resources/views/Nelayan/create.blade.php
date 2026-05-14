@extends('layouts.app')
@section('content')

<style>
    .bottom-nav {
        display: none !important;
    }
</style>

<div class="p-3">
    <h4 class="font-weight-bold mb-4 mt-2">Tambah Nelayan Baru</h4>

    <form action="{{ route('nelayan.store') }}" method="POST">
        @csrf
        <input type="hidden" name="asal" value="{{ request('asal') }}">
        <div class="form-group mb-4">
            <label class="font-weight-bold">Nama Nelayan <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control form-control-lg shadow-sm" style="border-radius: 12px; border: 2px solid #eaf6fd; color: #495057; background-color: #f8fcff;" placeholder="Masukkan nama" required>
        </div>

        <div class="form-group mb-5">
            <label class="font-weight-bold">Nomor WA <span class="text-danger">*</span></label>
            <input type="number" name="nomor_hp" class="form-control form-control-lg shadow-sm" style="border-radius: 12px; border: 2px solid #eaf6fd; color: #495057; background-color: #f8fcff;" placeholder="08123456789" required>
        </div>

        <button type="submit" class="btn btn-success btn-lg btn-block p-3 font-weight-bold" style="border-radius: 15px;">
            Simpan Data Nelayan
        </button>
        
        @if(request('asal') == 'penjualan')
            <a href="{{ route('penjualan.create') }}" class="btn btn-light btn-lg btn-block mt-3 font-weight-bold" style="border-radius: 15px;">
                Batal
            </a>
        @else
            <a href="{{ route('nelayan.index') }}" class="btn btn-light btn-lg btn-block mt-3 font-weight-bold" style="border-radius: 15px;">
                Batal
            </a>
        @endif

    </form>
</div>
@endsection