@extends('auth.layouts')

@section('content')
    <h2>Edit User</h2>

    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="form-group">
            <label for="photo">Photo</label>
            <input type="file" name="photo" class="form-control">
            @if ($user->photo)
                <img src="{{ asset('storage/' . $user->photo) }}" alt="User Photo" class="img-thumbnail" width="100">
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
