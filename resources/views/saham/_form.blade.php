<div class="grid md:grid-cols-2 gap-5">
    <div>
        <label class="block text-sm font-medium text-gray-600 mb-1.5">Kode Saham</label>
        <input type="text" name="kode_saham" maxlength="10"
            value="{{ old('kode_saham', $saham->kode_saham ?? '') }}"
            placeholder="cth. BBCA"
            class="w-full rounded-lg border-gray-200 focus:border-[#7C1F33] focus:ring-[#7C1F33] text-sm">
        @error('kode_saham') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-600 mb-1.5">Sektor</label>
        <input type="text" name="sektor"
            value="{{ old('sektor', $saham->sektor ?? '') }}"
            placeholder="cth. Perbankan"
            class="w-full rounded-lg border-gray-200 focus:border-[#7C1F33] focus:ring-[#7C1F33] text-sm">
        @error('sektor') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-600 mb-1.5">Nama Perusahaan</label>
        <input type="text" name="nama_perusahaan"
            value="{{ old('nama_perusahaan', $saham->nama_perusahaan ?? '') }}"
            placeholder="cth. Bank Central Asia Tbk"
            class="w-full rounded-lg border-gray-200 focus:border-[#7C1F33] focus:ring-[#7C1F33] text-sm">
        @error('nama_perusahaan') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-600 mb-1.5">Harga Saat Ini (Rp / lembar)</label>
        <input type="number" name="harga_saat_ini" min="0" step="0.01"
            value="{{ old('harga_saat_ini', $saham->harga_saat_ini ?? '') }}"
            class="w-full rounded-lg border-gray-200 focus:border-[#7C1F33] focus:ring-[#7C1F33] text-sm">
        <p class="text-xs text-gray-400 mt-1">Admin bisa update harga ini kapan saja lewat halaman Edit.</p>
        @error('harga_saat_ini') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-600 mb-1.5">
            Deskripsi <span class="text-gray-400 font-normal">- opsional</span>
        </label>
        <textarea name="deskripsi" rows="3"
            class="w-full rounded-lg border-gray-200 focus:border-[#7C1F33] focus:ring-[#7C1F33] text-sm">{{ old('deskripsi', $saham->deskripsi ?? '') }}</textarea>
        @error('deskripsi') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
    </div>
</div>