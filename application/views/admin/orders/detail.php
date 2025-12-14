<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-semibold">Detail Order #<?= $order->id ?></h1>
        <div class="flex space-x-2">
            <a href="<?= site_url('admin/orders') ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <a href="<?= site_url('admin/orders/print_detail/' . $order->id) ?>" 
               target="_blank" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                <i class="fas fa-print"></i> Print
            </a>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Informasi Order -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4 pb-2 border-b">Informasi Order</h2>
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">ID Order:</span>
                    <span class="font-semibold">#<?= $order->id ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Nama Pemesan:</span>
                    <span class="font-semibold"><?= htmlspecialchars($order->name) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Lokasi:</span>
                    <span class="font-semibold"><?= htmlspecialchars($order->location) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Tanggal Order:</span>
                    <span class="font-semibold"><?= date('d M Y H:i', strtotime($order->created_at)) ?></span>
                </div>
            </div>
        </div>
        
        <!-- Status Pembayaran -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4 pb-2 border-b">Status Pembayaran</h2>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Status:</span>
                    <?php
                    $statusClass = '';
                    $statusText = '';
                    switch($order->status_pembayaran) {
                        case 'paid':
                            $statusClass = 'bg-green-100 text-green-800';
                            $statusText = 'Lunas';
                            break;
                        case 'cancelled':
                            $statusClass = 'bg-red-100 text-red-800';
                            $statusText = 'Batal';
                            break;
                        default:
                            $statusClass = 'bg-yellow-100 text-yellow-800';
                            $statusText = 'Pending';
                    }
                    ?>
                    <span class="px-3 py-1 rounded-full text-sm font-medium <?= $statusClass ?>">
                        <?= $statusText ?>
                    </span>
                </div>
                <?php if($order->payment_method): ?>
                <div class="flex justify-between">
                    <span class="text-gray-600">Metode Pembayaran:</span>
                    <span class="font-semibold"><?= $order->payment_method ?></span>
                </div>
                <?php endif; ?>
                <?php if($order->payment_date): ?>
                <div class="flex justify-between">
                    <span class="text-gray-600">Tanggal Pembayaran:</span>
                    <span class="font-semibold"><?= date('d M Y H:i', strtotime($order->payment_date)) ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <!-- Tombol Update Status -->
            <div class="mt-6">
                <button onclick="updateStatusModal(<?= $order->id ?>)" 
                        class="w-full bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">
                    <i class="fas fa-edit"></i> Update Status
                </button>
            </div>
        </div>
    </div>
    
    <!-- Daftar Items -->
    <div class="bg-white rounded-lg shadow p-6 mt-6">
        <h2 class="text-xl font-semibold mb-4 pb-2 border-b">Items Pesanan</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 text-left">Item</th>
                        <th class="p-3 text-left">Harga</th>
                        <th class="p-3 text-left">Qty</th>
                        <th class="p-3 text-left">Size</th>
                        <th class="p-3 text-left">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $items = json_decode($order->items, true);
                    if($items && is_array($items)):
                        foreach($items as $item): 
                    ?>
                    <tr class="border-b">
                        <td class="p-3"><?= htmlspecialchars($item['name']) ?></td>
                        <td class="p-3">Rp <?= number_format($item['price']) ?></td>
                        <td class="p-3"><?= $item['quantity'] ?></td>
                        <td class="p-3"><?= $item['size'] ?? '-' ?></td>
                        <td class="p-3">Rp <?= number_format($item['price'] * $item['quantity']) ?></td>
                    </tr>
                    <?php endforeach; endif; ?>
                </tbody>
                <tfoot class="bg-gray-50 font-semibold">
                    <tr>
                        <td colspan="4" class="p-3 text-right">Total Items:</td>
                        <td class="p-3">Rp <?= number_format($order->total) ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="p-3 text-right">Admin Fee:</td>
                        <td class="p-3">Rp <?= number_format($order->fee) ?></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="p-3 text-right text-lg">Grand Total:</td>
                        <td class="p-3 text-lg text-blue-600">
                            Rp <?= number_format($order->total + $order->fee) ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<script>
function updateStatusModal(orderId) {
    Swal.fire({
        title: 'Update Status Pembayaran',
        html: `
            <div class="text-left">
                <label class="block mb-2">Status:</label>
                <select id="statusSelect" class="w-full border rounded p-2 mb-3">
                    <option value="pending" <?= $order->status_pembayaran == 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="paid" <?= $order->status_pembayaran == 'paid' ? 'selected' : '' ?>>Lunas (Paid)</option>
                    <option value="cancelled" <?= $order->status_pembayaran == 'cancelled' ? 'selected' : '' ?>>Batal (Cancelled)</option>
                </select>
                <label class="block mb-2">Metode Pembayaran (opsional):</label>
                <input type="text" id="paymentMethod" class="w-full border rounded p-2" 
                       value="<?= htmlspecialchars($order->payment_method) ?>"
                       placeholder="Contoh: Tunai, Transfer, QRIS">
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Update',
        cancelButtonText: 'Batal',
        preConfirm: () => {
            return {
                status: document.getElementById('statusSelect').value,
                payment_method: document.getElementById('paymentMethod').value
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '<?= site_url("admin/orders/update_status") ?>',
                method: 'POST',
                data: {
                    id: orderId,
                    status: result.value.status,
                    payment_method: result.value.payment_method
                },
                dataType: 'json',
                success: function(res) {
                    if (res.success) {
                        Swal.fire('Berhasil!', res.message, 'success').then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Error!', res.message, 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'Terjadi kesalahan', 'error');
                }
            });
        }
    });
}
</script>