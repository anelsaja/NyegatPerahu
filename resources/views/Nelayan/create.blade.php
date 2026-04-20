@extends('layouts.app')
@section('content')
<div class="p-4">
    <h4 class="font-weight-bold mb-4 mt-2">Tambah Nelayan</h4>

    <form action="{{ route('nelayan.store') }}" method="POST">
        @csrf
        <div class="form-group mb-4">
            <label class="font-weight-bold">Nama Nelayan <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control form-control-lg" placeholder="Masukkan nama" required>
        </div>

        <div class="form-group mb-5">
            <label class="font-weight-bold">Nomor HP <span class="text-muted">(Opsional)</span></label>
            <input type="number" name="nomor_hp" class="form-control form-control-lg" placeholder="08123456789">
        </div>

        <button type="submit" class="btn btn-success btn-lg btn-block p-3 font-weight-bold" style="border-radius: 15px;">
            Simpan Data Nelayan
        </button>
        
        <a href="{{ url()->previous() }}" class="btn btn-light btn-lg btn-block mt-3" style="border-radius: 15px;">
            Batal
        </a>
    </form>
</div>
@endsection