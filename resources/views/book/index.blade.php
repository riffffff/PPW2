@extends('auth.layouts')
@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header" style="text-align: center"><b>Data Buku</b></div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr style="text-align: center">
                                <th>No</th>
                                <th>ID</th>
                                <th>Judul Buku</th>
                                <th>Penulis</th>
                                <th>Harga</th>
                                <th>Tanggal Terbit</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($buku as $index => $item)
                                <tr>
                                    <td style="text-align: center">{{ ($buku->perPage() * ($buku->currentPage() - 1)) + $loop->iteration }}</td>
                                    <td style="text-align: center">{{ $item->id }}</td>
                                    <td>{{ $item->judul }}</td>
                                    <td>{{ $item->penulis }}</td>
                                    <td>{{ 'Rp ' . number_format($item->harga, 2, ',', '.') }}</td>
                                    <td style="text-align: center">{{ \Carbon\Carbon::parse($item->tanggal_terbit)->format('d-m-Y') }}</td>
                                    <td class="p-2 ">
                                        <div class="d-flex align-items-center">
                                            <form action="{{ route('book.edit', $item->id) }}" class="mx-2">
                                                @csrf
                                                <button type="submit" class="btn btn-warning">Edit</button>
                                            </form>
                                            <form action="{{ route('book.destroy', $item->id) }}" method="POST">
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

                    <div class="d-flex justify-content-center">{{ $buku->links('pagination::bootstrap-4') }}</div>


                    <p>Jumlah Buku: {{ $jumlah_buku }}</p>

                    <p>Total Harga Buku: Rp {{ number_format($total_harga, 2, ',', '.') }}</p>

                    <a href="{{ route('book.create') }}" class="btn btn-primary"> Tambah Buku</a>
                </div>
            </div>
        </div>
    </div>
@endsection
