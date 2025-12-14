<div class="container mx-auto px-4 py-8 bg-white">
    <h1 class="text-2xl font-semibold mb-6">Pengaturan Aplikasi</h1>
    
    <?php if ($this->session->flashdata('success')): ?>
    <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-4">
        <?= $this->session->flashdata('success') ?>
	</div>
    <?php endif; ?>
    
    <form action="<?= site_url('admin/settings/update') ?>" method="post" enctype="multipart/form-data" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="app_name" class="block font-medium mb-1">Nama Website</label>
                <input type="text" name="app_name" id="app_name" 
                value="<?= set_value('app_name', isset($settings->app_name) ? $settings->app_name : '') ?>" 
                class="w-full border border-gray-300 rounded px-3 py-2" required>
			</div>
            
            <div>
                <label for="wa_number" class="block font-medium mb-1">Nomor WhatsApp</label>
                <input type="text" name="wa_number" id="wa_number" 
                value="<?= set_value('wa_number', isset($settings->wa_number) ? $settings->wa_number : '') ?>" 
                class="w-full border border-gray-300 rounded px-3 py-2" required>
			</div>
		</div>
        
        <div>
            <label for="site_desc" class="block font-medium mb-1">Deskripsi situs</label>
            <textarea name="site_desc" id="site_desc" rows="3" 
            class="w-full border border-gray-300 rounded px-3 py-2"><?= set_value('site_desc', isset($settings->site_desc) ? $settings->site_desc : '') ?></textarea>
		</div>
          
        <div>
            <label for="site_key" class="block font-medium mb-1">Keywords situs</label>
            <textarea name="site_key" id="site_key" rows="3" 
            class="w-full border border-gray-300 rounded px-3 py-2"><?= set_value('site_key', isset($settings->site_key) ? $settings->site_key : '') ?></textarea>
		</div>
          
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Logo Aplikasi -->
            <div>
                <label for="app_logo" class="block font-medium mb-1">
                    Logo Aplikasi
                    <span class="text-sm text-gray-500">(PNG/JPG max 2MB, recommended: 200x60px)</span>
				</label>
                <input type="file" name="app_logo" id="app_logo" accept=".png,.jpg,.jpeg" class="block w-full border border-gray-300 rounded px-3 py-2">
                
                <?php if (!empty($settings->app_logo)): ?>
                <div class="mt-3">
                    <p class="text-sm text-gray-600 mb-1">Logo saat ini:</p>
                    <img src="<?= base_url($settings->app_logo) ?>" alt="App Logo" class="w-32 h-auto rounded shadow">
                    <p class="text-xs text-gray-500 mt-1"><?= $settings->app_logo ?></p>
				</div>
                <?php endif; ?>
			</div>
            
            <!-- Favicon/App Icon -->
            <div>
                <label for="app_icon" class="block font-medium mb-1">
                    Favicon/App Icon
                    <span class="text-sm text-gray-500">(ICO/PNG max 1MB, recommended: 32x32px atau 64x64px)</span>
				</label>
                <input type="file" name="app_icon" id="app_icon" accept=".ico,.png,.jpg,.jpeg" class="block w-full border border-gray-300 rounded px-3 py-2">
                
                <?php if (!empty($settings->app_icon)): ?>
                <div class="mt-3">
                    <p class="text-sm text-gray-600 mb-1">Favicon saat ini:</p>
                    <img src="<?= base_url($settings->app_icon) ?>" alt="App Icon" class="w-16 h-16 rounded shadow">
                    <p class="text-xs text-gray-500 mt-1"><?= $settings->app_icon ?></p>
				</div>
                <?php else: ?>
                <div class="mt-3 p-3 bg-yellow-50 rounded border border-yellow-200">
                    <p class="text-sm text-yellow-700">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Favicon belum diupload. Upload file ICO atau PNG untuk icon browser/tab.
					</p>
				</div>
                <?php endif; ?>
			</div>
		</div>
        
        <!-- Preview Section -->
        <?php if (!empty($settings->app_logo) || !empty($settings->app_icon)): ?>
        <div class="mt-6 p-4 bg-gray-50 rounded-lg border">
            <h3 class="font-medium text-lg mb-3">Preview:</h3>
            <div class="flex items-center space-x-4">
                <?php if (!empty($settings->app_icon)): ?>
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-1">Favicon</p>
                    <img src="<?= base_url($settings->app_icon) ?>" alt="App Icon" class="w-12 h-12 mx-auto">
				</div>
                <?php endif; ?>
                
                <?php if (!empty($settings->app_logo)): ?>
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-1">Logo</p>
                    <img src="<?= base_url($settings->app_logo) ?>" alt="App Logo" class="h-12 w-auto mx-auto">
				</div>
                <?php endif; ?>
			</div>
		</div>
        <?php endif; ?>
        
        <div class="flex justify-end pt-4 border-t">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded transition-colors duration-200">
                <i class="fas fa-save mr-2"></i>Simpan Perubahan
			</button>
		</div>
	</form>
</div>

<!-- JavaScript untuk preview image -->
<script>
    // Preview untuk app_logo
    document.getElementById('app_logo').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Tampilkan preview
                const previewDiv = document.createElement('div');
                previewDiv.className = 'mt-3';
                previewDiv.innerHTML = `
				<p class="text-sm text-gray-600 mb-1">Preview logo baru:</p>
				<img src="${e.target.result}" alt="Preview Logo" class="w-32 h-auto rounded shadow">
                `;
                
                // Hapus preview sebelumnya jika ada
                const oldPreview = document.querySelector('#app_logo').nextElementSibling;
                if (oldPreview && oldPreview.querySelector('img')) {
                    oldPreview.remove();
				}
                
                // Tambahkan preview setelah input
                document.getElementById('app_logo').parentNode.appendChild(previewDiv);
			}
            reader.readAsDataURL(e.target.files[0]);
		}
	});
    
    // Preview untuk app_icon
    document.getElementById('app_icon').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Tampilkan preview
                const previewDiv = document.createElement('div');
                previewDiv.className = 'mt-3';
                previewDiv.innerHTML = `
				<p class="text-sm text-gray-600 mb-1">Preview favicon baru:</p>
				<img src="${e.target.result}" alt="Preview Icon" class="w-16 h-16 rounded shadow">
                `;
                
                // Hapus preview sebelumnya jika ada
                const oldPreview = document.querySelector('#app_icon').nextElementSibling;
                if (oldPreview && oldPreview.querySelector('img')) {
                    oldPreview.remove();
				}
                
                // Tambahkan preview setelah input
                document.getElementById('app_icon').parentNode.appendChild(previewDiv);
			}
            reader.readAsDataURL(e.target.files[0]);
		}
	});
</script>