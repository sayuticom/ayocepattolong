<h1 class="text-xl font-bold mb-4"><?= $title ?></h1>

<form action="<?= site_url('admin/products/'.$action) ?>" method="post" enctype="multipart/form-data" class="bg-white p-4 rounded shadow">
	<div class="mb-3">
		<label class="block text-sm">Nama</label>
		<input type="text" name="name" value="<?= isset($product)?$product->name:'' ?>" class="w-full border p-2 rounded">
	</div>
	
	<div class="mb-3">
		<label class="block text-sm">Kategori</label>
		<select name="category_id" class="w-full border p-2 rounded">
			<option value="">-- pilih --</option>
			<?php foreach($categories as $c): ?>
			<option value="<?= $c->id ?>" <?= isset($product) && $product->category_id==$c->id?'selected':'' ?>><?= $c->name ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	
	<div class="grid grid-cols-2 gap-4">
		<div class="mb-3">
			<label class="block text-sm">Harga</label>
			<input type="number" name="price" value="<?= isset($product)?$product->price:'' ?>" class="w-full border p-2 rounded">
		</div>
		<div class="mb-3">
			<label class="block text-sm">Harga Asli</label>
			<input type="number" name="original_price" value="<?= isset($product)?$product->original_price:'' ?>" class="w-full border p-2 rounded">
		</div>
	</div>
	
	<div class="mb-3">
		<label class="block text-sm">Gambar</label>
		<input type="file" name="image" class="w-full">
		<?php if(isset($product) && $product->image): ?><img src="<?= base_url($product->image) ?>" class="h-20 mt-2"><?php endif; ?>
	</div>
	
	<div class="flex items-center space-x-4 mb-4">
		<label class="flex items-center"><input type="checkbox" name="is_active" <?= isset($product) && $product->is_active? 'checked':'' ?>> Aktif</label>
		<label class="flex items-center"><input type="checkbox" name="is_ready" <?= isset($product) && $product->is_ready? 'checked':'' ?>> Ready</label>
	</div>
	
	<div>
		<button class="px-4 py-2 bg-green-600 text-white rounded">Simpan</button>
		<a href="<?= site_url('admin/products') ?>" class="ml-2 px-4 py-2 border rounded">Batal</a>
	</div>
</form>
