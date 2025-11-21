<h1>Tambah Guru</h1>

<form action="{{ route('guru.store') }}" method="POST">
    @csrf
    <label>Nama Guru</label><br>
    <input type="text" name="nama" required><br><br>

    <label>NIP (opsional)</label><br>
    <input type="text" name="nip"><br><br>

    <button type="submit">Simpan</button>
</form>
