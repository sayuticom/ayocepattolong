<!-- sidebar -->
<div id="sidebar" class="w-64 bg-white border-r hidden md:block">
	<div class="p-4">
		<div class="text-xl font-bold mb-4"><?= $settings->app_name ?? 'App' ?></div>
		<ul>
			<li class="mb-2"><a href="<?= site_url('admin') ?>" class="block px-3 py-2 rounded hover:bg-gray-100">Dashboard</a></li>
			<li class="mb-2"><a href="<?= site_url('admin/categories') ?>" class="block px-3 py-2 rounded hover:bg-gray-100">Kategori</a></li>
			<li class="mb-2"><a href="<?= site_url('admin/products') ?>" class="block px-3 py-2 rounded hover:bg-gray-100">Produk</a></li>
			<li class="mb-2"><a href="<?= site_url('admin/settings') ?>" class="block px-3 py-2 rounded hover:bg-gray-100">Settings</a></li>
		</ul>
	</div>
</div>
<!-- content -->
<div class="flex-1 p-6">
