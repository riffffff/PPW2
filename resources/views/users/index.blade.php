@extends('auth.layouts')

@section('content')
    <table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Photo</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if ($user->photo)
                            <img src="{{ asset('storage/' . $user->photo) }}" width="100px">
                        @else
                            <img src="{{ asset('noimage.jpg') }}" width="100px">
                        @endif
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <form action="{{ route('users.edit', $user->id) }}" class="mx-2">
                                @csrf
                                <button type="submit" class="btn btn-warning">Edit</button>
                            </form>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Yakin mau dihapus??')" type="submit"
                                    class="btn btn-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
