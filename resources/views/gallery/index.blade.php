@extends('auth.layouts')

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    <div class="row">
                        @if (count($galleries) > 0)
                            @foreach ($galleries as $gallery)
                                <div class="col-md-4">
                                    <a class="example-image-link"
                                        href="{{ asset('storage/posts/image/' . $gallery->picture) }}"
                                        data-lightbox="roadtrip">
                                        <img class="example-image img-fluid m-2"
                                            src="{{ asset('storage/posts/image/' . $gallery->picture) }}"
                                            alt="Image-{{ $gallery->id }}">
                                    </a>
                                    <p>{{ $gallery->description }}</p>
                                    <div class="d-flex align-items-center">
                                        <a href="{{ route('gallery.edit', $gallery->id) }}"
                                            class="btn btn-warning mx-2">Edit</a>
                                        <form action="{{ route('gallery.destroy', $gallery->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Yakin mau dihapus??')" type="submit"
                                                class="btn btn-danger">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <h3>Tidak ada data</h3>
                        @endif
                    </div>
                    <div class="mb-3">
                        <a href="{{ route('gallery.create') }}" class="btn btn-primary" style="margin: 30px auto 0;">Add
                            Photo</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
