<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = array(
            'id' => "post",
            'menu' => 'Gallery',
            'galleries' => Post::where('picture','!=','')
            ->whereNotNull('picture')
            ->orderBy('created_at','desc')
            ->paginate(10)
        );
        return view('gallery.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('gallery.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'picture' => 'image|nullable|max:1999'
        ]);

        if ($request->hasFile('picture')) {
            $filenameWithExt = $request->file('picture')->getClientOriginalName();
            $extension = $request->file('picture')->getClientOriginalExtension();
            $basename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $basename = uniqid() . time();
            $smallFilename = "small_" . $basename . "." . $extension;
            $mediumFilename = "medium_" . $basename . "." . $extension;
            $largeFilename = "large_" . $basename . "." . $extension;
            $filenameSimpan = $basename . "." . $extension;
            $path = $request->file('picture')->storeAs('posts/image', $filenameSimpan);
        } else {
            $filenameSimpan = "noimage.png";
        }

        // dd($request->input());

        $post = new Post;
        $post->picture = $filenameSimpan;
        $post->title = $request->input('title');
        $post->description = $request->input('description');
        $post->save();

        return redirect('/gallery')->with('success', 'Berhasil menambahkan data baru!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $gallery = Post::findOrFail($id);
        return view('gallery.edit', compact('gallery'));
    }

    public function update(Request $request, $id)
    {
        $gallery = Post::findOrFail($id);

        $request->validate([
            'description' => 'required|string|max:255',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $gallery->description = $request->description;

        if ($request->hasFile('picture')) {
            // Hapus gambar lama
            if ($gallery->picture && $gallery->picture != 'noimage.png') {
                Storage::delete('posts/image/' . $gallery->picture);
            }

            // Ambil ekstensi file
            $extension = $request->file('picture')->getClientOriginalExtension();
            // Buat nama file unik dengan hashing
            $basename = uniqid() . time();
            $filenameSimpan = $basename . '.' . $extension;
            // Simpan file dengan nama yang baru di-hashing
            $request->picture->storeAs('posts/image', $filenameSimpan);

            $gallery->picture = $filenameSimpan;
        }

        $gallery->save();
        return redirect()->route('gallery.index')->with('success', 'Data berhasil diperbarui!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $gallery = Post::find($id);

        $file = public_path() . 'storage/' . $gallery->picture;

        try {
            if (File::exists($file)) {
                File::delete($file);
            }

            $gallery->delete();
        } catch (\Throwable $th) {
            return redirect()
            ->route('gallery.index')
            ->with('error', 'Gagal hapus data');
        }

        return redirect()
        ->route('gallery.index')
        ->with('success', 'Berhasil hapus data');
    }
}
