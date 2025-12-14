<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
    <style>
        @media print {
            body { font-size: 12px; }
            .no-print { display: none !important; }
            @page { margin: 0.5cm; }
        }
        body { font-family: 'Courier New', monospace; }
        .container { max-width: 80mm; margin: 0 auto; }
        .header { text-align: center; border-bottom: 1px dashed #000; padding-bottom: 10px; margin-bottom: 10px; }
        .item-row { border-bottom: 1px dotted #ccc; padding: 3px 0; }
        .total-row { border-top: 2px solid #000; margin-top: 10px; padding-top: 10px; font-weight: bold; }
        .status { 
            padding: 2px 6px; 
            border-radius: 3px; 
            font-size: 10px; 
            display: inline-block;
            margin-left: 5px;
        }
        .status-paid { background: #d4edda; color: #155724; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-cancelled { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h2><?= $settings->store_name ?? 'Toko Saya' ?></h2>
            <p><?= $settings->address ?? '' ?></p>
            <p>Telp: <?= $settings->phone ?? '' ?></p>
            <p>================================</p>
        </div>
        
        <!-- Info Order -->
        <div>
            <p><strong>Order ID:</strong> #<?= $order->id ?></p>
            <p><strong>Nama:</strong> <?= $order->name ?></p>
            <p><strong>Lokasi:</strong> <?= $order->location ?></p>
            <p><strong>Tanggal:</strong> <?= date('d/m/Y H:i', strtotime($order->created_at)) ?></p>
            <p>
                <strong>Status:</strong> 
                <?php 
                $statusClass = '';
                $statusText = '';
                switch($order->status_pembayaran) {
                    case 'paid':
                        $statusClass = 'status-paid';
                        $statusText = 'LUNAS';
                        break;
                    case 'cancelled':
                        $statusClass = 'status-cancelled';
                        $statusText = 'BATAL';
                        break;
                    default:
                        $statusClass = 'status-pending';
                        $statusText = 'PENDING';
                }
                ?>
                <span class="status <?= $statusClass ?>"><?= $statusText ?></span>
            </p>
            <?php if($order->payment_method): ?>
            <p><strong>Metode Bayar:</strong> <?= $order->payment_method ?></p>
            <?php endif; ?>
        </div>
        
        <p>--------------------------------</p>
        
        <!-- Items -->
        <div>
            <h3>ITEMS:</h3>
            <?php 
            $items = json_decode($order->items, true);
            if($items && is_array($items)):
                foreach($items as $item): 
            ?>
            <div class="item-row">
                <div>
                    <?= $item['name'] ?> 
                    <?php if(isset($item['size'])): ?>
                    (<?= $item['size'] ?>)
                    <?php endif; ?>
                </div>
                <div style="float: right;">
                    <?= $item['quantity'] ?> x Rp <?= number_format($item['price']) ?>
                </div>
                <div style="clear: both;"></div>
            </div>
            <?php endforeach; endif; ?>
        </div>
        
        <p>--------------------------------</p>
        
        <!-- Total -->
        <div>
            <div style="display: flex; justify-content: space-between;">
                <span>Total Items:</span>
                <span>Rp <?= number_format($order->total) ?></span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span>Admin Fee:</span>
                <span>Rp <?= number_format($order->fee) ?></span>
            </div>
            <div class="total-row" style="display: flex; justify-content: space-between;">
                <span>GRAND TOTAL:</span>
                <span>Rp <?= number_format($order->total + $order->fee) ?></span>
            </div>
        </div>
        
        <p>================================</p>
        <p style="text-align: center;">Terima kasih telah berbelanja</p>
        <p style="text-align: center;"><?= date('d/m/Y H:i') ?></p>
        
        <!-- Tombol Print (hanya tampil saat tidak di-print) -->
        <div class="no-print" style="text-align: center; margin-top: 20px;">
            <button onclick="window.print()" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
                Print Struk
            </button>
            <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
                Tutup
            </button>
        </div>
    </div>
    
    <script>
        // Auto print saat halaman terbuka
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        }
        
        // Auto close setelah print (optional)
        window.onafterprint = function() {
            // window.close(); // Uncomment jika ingin auto close
        };
    </script>
</body>
</html>