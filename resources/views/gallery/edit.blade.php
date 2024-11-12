@extends('auth.layouts')
@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <form action="{{ route('gallery.update', $gallery->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <input type="text" class="form-control" name="description"
                                value="{{ $gallery->description }}" required>
                        </div>
                        <div class="form-group">
                            <label for="photo">Photo</label>
                            <input type="file" name="picture" class="form-control">
                            @if ($gallery->picture)
                                <img src="{{ asset('storage/posts/image/' . $gallery->picture) }}" alt="User Photo"
                                    class="img-thumbnail" width="100">
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
@endsection
