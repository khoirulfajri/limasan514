@extends('admin.layout')

@section('content')

<h3>Data User</h3>

{{-- FORM TAMBAH USER --}}
<form action="{{route('admin.users.store')}}" method="POST" class="card p-3 mb-4">
    @csrf

    <div class="row">

        <div class="col-md-2">
            <input name="nama" class="form-control" placeholder="Nama">
        </div>

        <div class="col-md-2">
            <input name="email" class="form-control" placeholder="Email">
        </div>

        <div class="col-md-2">
            <input name="no_telp" class="form-control" placeholder="No Telp">
        </div>

        <div class="col-md-2">
            <select name="jenis_kelamin" class="form-control">
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
            </select>
        </div>

        <div class="col-md-2">
            <select name="role" class="form-control">
                <option value="customer">Customer</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <div class="col-md-2">
            <input type="password" name="password" class="form-control" placeholder="Password">
        </div>

    </div>

    <button class="btn btn-primary mt-3">Tambah User</button>

</form>


{{-- TABEL USER --}}
<table class="table table-bordered">

    <thead>

        <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>No Telp</th>
            <th>JK</th>
            <th>Role</th>
            <th width="150">Aksi</th>
        </tr>

    </thead>

    <tbody>

        @foreach($users as $u)

        <tr>

            <td>{{$u->nama}}</td>
            <td>{{$u->email}}</td>
            <td>{{$u->no_telp}}</td>
            <td>{{$u->jenis_kelamin}}</td>
            <td>{{$u->role}}</td>

            <td>

                <button class="btn btn-warning btn-sm btnEdit" data-id="{{$u->id}}" data-nama="{{$u->nama}}"
                    data-email="{{$u->email}}" data-telp="{{$u->no_telp}}" data-jk="{{$u->jenis_kelamin}}"
                    data-role="{{$u->role}}">
                    Ubah
                </button>

                <a href="{{route('admin.users.delete',$u->id)}}" class="btn btn-danger btn-sm">
                    Hapus
                </a>

            </td>

        </tr>

        @endforeach

    </tbody>

</table>



{{-- MODAL EDIT USER --}}
<div class="modal fade" id="modalEdit">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">
                <h5>Edit User</h5>
            </div>

            <form id="formEdit" method="POST">
                @csrf

                <div class="modal-body">

                    <input type="text" name="nama" id="editNama" class="form-control mb-2">

                    <input type="email" name="email" id="editEmail" class="form-control mb-2">

                    <input type="text" name="no_telp" id="editTelp" class="form-control mb-2">

                    <select name="jenis_kelamin" id="editJK" class="form-control mb-2">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>

                    <select name="role" id="editRole" class="form-control mb-2">
                        <option value="admin">Admin</option>
                        <option value="customer">Customer</option>
                    </select>

                    <input type="password" name="password" class="form-control"
                        placeholder="Password Kosongkan jika tidak diubah">

                </div>

                <div class="modal-footer">

                    <button class="btn btn-primary">Update</button>

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



{{-- SCRIPT MODAL --}}
<script>
    document.querySelectorAll('.btnEdit').forEach(btn => {

btn.addEventListener('click',function(){

document.getElementById('editNama').value = this.dataset.nama;
document.getElementById('editEmail').value = this.dataset.email;
document.getElementById('editTelp').value = this.dataset.telp;
document.getElementById('editJK').value = this.dataset.jk;
document.getElementById('editRole').value = this.dataset.role;

document.getElementById('formEdit').action =
"/admin/users/update/"+this.dataset.id;

var modal = new bootstrap.Modal(document.getElementById('modalEdit'));

modal.show();

})

})

</script>

@endsection