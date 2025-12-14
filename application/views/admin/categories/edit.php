<div class="bg-white p-6 shadow rounded">
	
    <form method="POST">
		
        <label class="block mb-2 font-medium">Nama Kategori</label>
        <input type="text" name="name" value="<?= $category->name ?>" required
		class="w-full border px-3 py-2 rounded mb-4">
		
        <label class="block mb-2 font-medium">Status</label>
        <select name="is_active" class="w-full border px-3 py-2 rounded mb-4">
            <option value="1" <?= $category->is_active ? 'selected' : '' ?>>Aktif</option>
            <option value="0" <?= !$category->is_active ? 'selected' : '' ?>>Nonaktif</option>
		</select>
		
        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Update
		</button>
		
        <a href="<?= site_url('admin/categories') ?>" class="ml-3 text-gray-600">
            Batal
		</a>
		
	</form>
	
</div>
