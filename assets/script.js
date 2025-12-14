let products = [];
let cart = [];
let waNumber = "6289606357045";
let adminFeePercent = 0;
let categories = []; // Array untuk menyimpan kategori
const apiKey = 'RAddaadad_12345';
let currentOffset = 0;
const limit = 10;
let isLoading = false;
let hasMore = true;
let currentFilter = "*";
let isFirstLoad = true;
document.addEventListener("DOMContentLoaded", function () {
	
	// --- 1. LOAD PENGATURAN ---
    fetch(base_url + 'api/settings', {
        headers: {
            'X-API-KEY': apiKey
		}
	})
    .then(response => {
        if (!response.ok) throw new Error("Gagal load settings");
        return response.json();
	})
    .then(settings => {
        if (settings) {
            // Update Branding
            if(settings.app_name) {
                document.getElementById('app-name').innerText = settings.app_name;
                document.title = settings.app_name;
			}
            if(settings.app_logo) {
                document.getElementById('app-logo').src = settings.app_logo;
			}
            
            // Update Config Global
            waNumber = settings.wa_number || "6289606357045";
            adminFeePercent = parseInt(settings.admin_fee) || 0;
            
            // Update QRIS Input
            const qrisInput = document.getElementById('qrisInput');
            if(qrisInput) qrisInput.value = settings.qris_content;
            
            // Update Info Promo List
            const promoList = document.getElementById('promo-list');
            if (promoList && settings.promo_text) {
                promoList.innerHTML = ''; 
                settings.promo_text.split('\n').forEach(line => {
                    if (line.trim()) {
                        const li = document.createElement('li');
                        li.innerText = line;
                        promoList.appendChild(li);
					}
				});
			}
		}
	})
    .catch(err => console.error("Error Settings:", err));
    
    // --- 2. LOAD KATEGORI PERTAMA ---
    loadCategories();
    
    // --- 3. LOAD PRODUK PERTAMA ---
    loadProducts(true);
    
     // --- 4. FUNGSI LOAD KATEGORI ---
    function loadCategories() {
        fetch(base_url + 'api/products/categories', {
            headers: {
                'X-API-KEY': apiKey
            }
        })
        .then(response => {
            if (!response.ok) throw new Error("Gagal load categories");
            return response.json();
        })
        .then(data => {
            if (data.status === 'success' && data.categories) {
                categories = data.categories;
                generateCategoryButtons();
            }
        })
        .catch(error => {
            console.error('Error Categories:', error);
        });
    }
    
    // --- 5. FUNGSI LOAD PRODUCTS ---
    function loadProducts(reset = false, category = null) {
        if (isLoading || (!hasMore && !reset)) return;
        
        if (reset) {
            currentOffset = 0;
            hasMore = true;
            products = [];
            filteredProductsCache = {}; // Reset cache
            document.getElementById("product-list").innerHTML = `
                <div id="initial-loading" class="col-12 text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            `;
        }
        
        isLoading = true;
        
        // Tampilkan loading untuk infinite scroll
        if (!reset) {
            const loadingEl = document.createElement("div");
            loadingEl.id = "loading-spinner";
            loadingEl.className = "col-12 text-center py-3";
            loadingEl.innerHTML = `
                <div class="spinner-border text-primary spinner-border-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <span class="ms-2">Memuat lebih banyak...</span>
            `;
            document.getElementById("product-list").appendChild(loadingEl);
        }
        
        // Build URL dengan parameter
        let url = `${base_url}api/products?limit=${limit}&offset=${currentOffset}`;
        if (category && category !== "*") {
            url += `&category=${encodeURIComponent(category)}`;
        }
        
        fetch(url, {
            headers: {
                'X-API-KEY': apiKey
            }
        })
        .then(response => {
            if (!response.ok) throw new Error("Gagal load products");
            return response.json();
        })
        .then(data => {
            const loadingSpinner = document.getElementById("loading-spinner");
            if (loadingSpinner) loadingSpinner.remove();
            
            if (data.status === 'success') {
                if (reset) {
                    products = data.products;
                } else {
                    const existingIds = products.map(p => p.id);
                    const newProducts = data.products.filter(p => !existingIds.includes(p.id));
                    products = [...products, ...newProducts];
                }
                
                hasMore = data.has_more;
                currentOffset = data.offset + data.products.length;
                
                // Update cache untuk kategori yang sedang aktif
                if (currentFilter !== "*") {
                    filteredProductsCache[currentFilter] = products.filter(p => p.category === currentFilter);
                }
                
                displayProducts(currentFilter, reset);
                
                if (reset || isFirstLoad) {
                    const initialLoading = document.getElementById("initial-loading");
                    if (initialLoading) initialLoading.remove();
                    
                    if (categories.length === 0 && products.length > 0) {
                        extractCategoriesFromProducts();
                    }
                    
                    if (isFirstLoad) {
                        setupInfiniteScroll();
                        isFirstLoad = false;
                    }
                }
            }
        })
        .catch(error => {
            console.error('Error Products:', error);
            document.querySelectorAll("#loading-spinner, #initial-loading").forEach(el => el.remove());
            document.getElementById("product-list").innerHTML = `
                <div class='col-12 text-center text-danger py-5'>
                    Gagal memuat data.<br>${error.message}
                </div>
            `;
        })
        .finally(() => {
            isLoading = false;
        });
    }
    
    // --- 6. FUNGSI LOAD PRODUCTS BY CATEGORY ---
    function loadProductsByCategory(category) {
        if (isLoading) return;
        
        isLoading = true;
        document.getElementById("product-list").innerHTML = `
            <div class="col-12 text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="mt-2">Memuat produk ${category}...</div>
            </div>
        `;
        
        // Reset offset untuk kategori baru
        currentOffset = 0;
        hasMore = true;
        
        // Load produk untuk kategori tertentu
        fetch(`${base_url}api/products?limit=1000&offset=0`, {
            headers: {
                'X-API-KEY': apiKey
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Filter produk berdasarkan kategori
                const categoryProducts = data.products.filter(p => p.category === category);
                
                // Simpan ke cache
                filteredProductsCache[category] = categoryProducts;
                
                if (categoryProducts.length === 0) {
                    document.getElementById("product-list").innerHTML = `
                        <div class='col-12 text-center py-5 text-muted'>
                            Tidak ada produk dalam kategori "${category}"
                        </div>
                    `;
                } else {
                    // Untuk kategori spesifik, kita hanya simpan produk yang difilter
                    displayProducts(category, true);
                }
            }
        })
        .catch(error => {
            console.error('Error loading category products:', error);
            document.getElementById("product-list").innerHTML = `
                <div class='col-12 text-center text-danger py-5'>
                    Gagal memuat produk ${category}
                </div>
            `;
        })
        .finally(() => {
            isLoading = false;
        });
    }
    
    // --- 7. EKSTRAK KATEGORI DARI PRODUK ---
    function extractCategoriesFromProducts() {
        if (products.length === 0) return;
        
        const categoryMap = {};
        products.forEach(product => {
            if (product.category && !categoryMap[product.category]) {
                categoryMap[product.category] = {
                    name: product.category,
                    disable_size_option: product.disable_size_option || 0,
                    has_size_option: product.has_size_option || 0,
                    size_label: product.size_label || 'Large',
                    size_price: product.size_price || 3000
                };
            }
        });
        
        categories = Object.values(categoryMap);
        generateCategoryButtons();
    }
    
    // --- 8. GENERATE CATEGORY BUTTONS ---
    function generateCategoryButtons() {
        const wrapper = document.getElementById("category-wrapper");
        if (!wrapper) {
            console.error("Category wrapper not found!");
            return;
        }
        
        wrapper.innerHTML = '';
        
        // Button "Semua"
        const btnAll = document.createElement("button");
        btnAll.className = "category-btn active";
        btnAll.innerHTML = '<i class="fas fa-star me-1"></i> Semua';
        btnAll.onclick = () => {
            setActiveBtn(btnAll);
            currentFilter = "*";
            currentOffset = 0;
            hasMore = true;
            
            // Aktifkan infinite scroll
            const sentinel = document.getElementById("scroll-sentinel");
            if (sentinel) sentinel.style.display = "block";
            
            // Tampilkan semua produk
            displayProducts("*", true);
        };
        wrapper.appendChild(btnAll);
        
        if (categories.length === 0) {
            const noCatBtn = document.createElement("button");
            noCatBtn.className = "category-btn";
            noCatBtn.disabled = true;
            noCatBtn.innerHTML = '<i class="fas fa-tags me-1"></i> No Categories';
            wrapper.appendChild(noCatBtn);
            return;
        }
        
        // Buat button untuk setiap kategori
        categories.forEach(cat => {
            const categoryName = typeof cat === 'object' ? cat.name : cat;
            const btn = document.createElement("button");
            btn.className = "category-btn";
            btn.innerText = categoryName;
            btn.title = categoryName;
            btn.onclick = () => {
                setActiveBtn(btn);
                currentFilter = categoryName;
                
                // Nonaktifkan infinite scroll untuk kategori spesifik
                const sentinel = document.getElementById("scroll-sentinel");
                if (sentinel) sentinel.style.display = "none";
                
                // Cek apakah produk untuk kategori ini sudah ada di cache
                if (filteredProductsCache[categoryName] && filteredProductsCache[categoryName].length > 0) {
                    // Tampilkan dari cache
                    displayProducts(categoryName, true);
                } else {
                    // Load produk untuk kategori ini
                    loadProductsByCategory(categoryName);
                }
            };
            wrapper.appendChild(btn);
        });
        
        console.log(`Generated ${categories.length} category buttons`);
    }
    
    
    // --- 9. SET ACTIVE BUTTON ---
    function setActiveBtn(activeBtn) {
        document.querySelectorAll('.category-btn').forEach(b => b.classList.remove('active'));
        activeBtn.classList.add('active');
	}
    
    // --- 10. SETUP INFINITE SCROLL ---
    function setupInfiniteScroll() {
        const oldSentinel = document.getElementById("scroll-sentinel");
        if (oldSentinel) oldSentinel.remove();
        
        const sentinel = document.createElement("div");
        sentinel.id = "scroll-sentinel";
        sentinel.className = "h-10";
        
        const productContainer = document.getElementById("product-container");
        if (productContainer) {
            productContainer.appendChild(sentinel);
		}
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !isLoading && hasMore && currentFilter === "*") {
                    loadProducts(false);
				}
			});
			}, {
            rootMargin: '100px',
            threshold: 0.1
		});
        
        if (sentinel) observer.observe(sentinel);
	}
    
	// --- 11. FUNGSI DISPLAY PRODUCTS (DIPERBAIKI) ---
    function displayProducts(filter = "*", resetView = false) {
        const productList = document.getElementById("product-list");
        if(!productList) return;
        
        console.log(`Displaying products with filter: ${filter}, resetView: ${resetView}`);
        
        // Kosongkan tampilan jika reset atau filter berubah
        if (resetView || filter !== currentFilter) {
            productList.innerHTML = "";
            console.log("Cleared product list for new filter");
		}
        
        currentFilter = filter;
        
        let productsToDisplay = [];
        
        if (filter === "*") {
            // Tampilkan semua produk
            productsToDisplay = products;
            console.log(`Displaying all products: ${productsToDisplay.length}`);
			} else {
            // Filter produk berdasarkan kategori
            // Cek cache dulu
            if (filteredProductsCache[filter]) {
                productsToDisplay = filteredProductsCache[filter];
                console.log(`Using cached products for ${filter}: ${productsToDisplay.length}`);
				} else {
                productsToDisplay = products.filter(p => p.category === filter);
                console.log(`Filtering products for ${filter}: ${productsToDisplay.length} found`);
                // Simpan ke cache
                filteredProductsCache[filter] = productsToDisplay;
			}
		}
        
        if (productsToDisplay.length === 0) {
            productList.innerHTML = `
			<div class='col-12 text-center py-5 text-muted'>
			${filter === "*" ? 'Belum ada produk' : `Tidak ada produk dalam kategori "${filter}"`}
			</div>`;
            return;
		}
        
        // Render produk
        productsToDisplay.forEach(product => {
            // Cek apakah produk sudah ditampilkan (untuk menghindari duplikat)
            const existingElement = document.querySelector(`[data-product-id="${product.id}"][data-category="${filter}"]`);
            if (existingElement) return;
            
            const el = document.createElement("div");
            el.className = "col-12 col-md-6 mb-3";
            el.setAttribute("data-product-id", product.id);
            el.setAttribute("data-category", filter);
            
            // LOGIKA STOK HABIS
            const isHabis = parseInt(product.is_ready) === 0;
            const opacityClass = isHabis ? "opacity-75 bg-light grayscale" : ""; 
            
            const badgeHabis = isHabis ? 
			`<div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-white bg-opacity-50" style="z-index:5;">
			<span class="badge bg-danger fs-6 shadow">HABIS</span>
			</div>` : "";
            
            const btnHtml = isHabis ? 
			`<button class="btn btn-secondary rounded-circle btn-sm" disabled><i class="fas fa-ban"></i></button>` :
			`<button class="btn-add" onclick="addToCart('${product.id}')"><i class="fas fa-plus"></i></button>`;
            
            const priceHtml = product.price != product.originalPrice ? 
			`<div>
			<span class="price-coret me-1">${formatRupiah(product.originalPrice)}</span>
			<span class="price-final">${formatRupiah(product.price)}</span>
			</div>` : 
			`<span class="price-final text-dark">${formatRupiah(product.price)}</span>`;
            
            // LOGIKA SIZE OPTION
            const hasSizeOption = product.has_size_option !== undefined ? 
			parseInt(product.has_size_option) === 1 : 
			(parseInt(product.harga_tambahan) > 0);
            
            const disableSizeOption = product.disable_size_option !== undefined ? 
			parseInt(product.disable_size_option) === 1 : 
			(parseInt(product.harga_tambahan) === 0);
            
            const showSizeOption = hasSizeOption && !disableSizeOption && !isHabis;
            const disableSize = isHabis ? 'disabled' : '';
            
            const sizeLabel = product.size_label || 'Large';
            const sizePrice = parseInt(product.size_price) || parseInt(product.harga_tambahan) || 3000;
            
            // PATH GAMBAR
            let imgSrc = 'https://via.placeholder.com/80?text=No+Img';
            if (product.image) {
                if (product.image.startsWith('uploads/')) {
                    imgSrc = base_url + product.image;
					} else if (product.image.startsWith('http')) {
                    imgSrc = product.image;
					} else {
                    imgSrc = base_url + 'uploads/' + product.image;
				}
			}
            
            const hargaSize = formatRupiahShort(sizePrice);
            
            el.innerHTML = `
            <div class="product-card d-flex align-items-center gap-3 h-100 ${opacityClass}">
			<div class="position-relative">
			<img src="${imgSrc}" class="product-img bg-light" alt="${product.name}" 
			onerror="this.onerror=null; this.src='https://via.placeholder.com/80?text=Err'">
			${badgeHabis}
			</div>
			<div class="flex-grow-1">
			<div class="product-title text-truncate">${product.name}</div>
			${priceHtml}
			${showSizeOption ? `
				<div class="form-check mt-1">
				<input class="form-check-input" type="checkbox" id="size-${product.id}" style="cursor:pointer;" ${disableSize}>
				<label class="form-check-label small text-muted" for="size-${product.id}">
				${sizeLabel} (+${hargaSize})
				</label>
			</div>` : ''}
			</div>
			<div>${btnHtml}</div>
            </div>
            `;
            
            productList.appendChild(el);
		});
        
        console.log(`Displayed ${productsToDisplay.length} products for filter: ${filter}`);
	}
    
	// --- 12. LOAD MORE FILTERED ---
    window.loadMoreFiltered = function(filter) {
        if (isLoading) return;
        // Untuk kategori spesifik, load semua produk sekaligus
        if (filter !== "*") {
            loadProductsByCategory(filter);
			} else {
            loadProducts(false);
		}
	}
    
    
    // --- 13. FORMAT RUPIAH FUNCTIONS ---
    function formatRupiah(amount) {
        return parseInt(amount).toLocaleString("id-ID", {
            style: "currency",
            currency: "IDR",
            minimumFractionDigits: 0
		});
	}
    
    function formatRupiahShort(amount) {
        if (!amount) return 'Rp 0';
        amount = parseInt(amount);
        if (amount < 1000) return `Rp ${amount}`;
        else if (amount < 1000000) {
            const result = amount / 1000;
            return `Rp ${result % 1 === 0 ? result : result.toFixed(1)}rb`;
			} else if (amount < 1000000000) {
            const result = amount / 1000000;
            return `Rp ${result % 1 === 0 ? result : result.toFixed(1)}jt`;
			} else {
            const result = amount / 1000000000;
            return `Rp ${result % 1 === 0 ? result : result.toFixed(1)}M`;
		}
	}
	// --- 14. ADD TO CART ---
    window.addToCart = function(productId) {
        // Cari produk dari semua produk yang sudah diload
        const product = products.find(p => p.id.toString() === productId.toString());
        
        if (!product) {
            console.error("Produk tidak ditemukan dengan ID:", productId);
            alert("Produk tidak ditemukan!");
            return;
		}
        
        if(parseInt(product.is_ready) === 0) {
            alert("Maaf, stok produk ini sedang habis.");
            return;
		}
        
        const sizeCheck = document.getElementById(`size-${productId}`);
        const isLarge = sizeCheck && sizeCheck.checked;
        const sizeName = isLarge ? (product.size_label || "Large") : "Regular";
        
        const hasSizeOption = product.has_size_option !== undefined ? 
		parseInt(product.has_size_option) === 1 : 
		(parseInt(product.harga_tambahan) > 0);
        
        const disableSizeOption = product.disable_size_option !== undefined ? 
		parseInt(product.disable_size_option) === 1 : 
		(parseInt(product.harga_tambahan) === 0);
        
        const sizePrice = parseInt(product.size_price) || parseInt(product.harga_tambahan) || 3000;
        
        let finalPrice = parseInt(product.price);
        
        if (isLarge && hasSizeOption && !disableSizeOption) {
            finalPrice += sizePrice;
		}
        
        const existing = cart.find(item => 
            item.id.toString() === productId.toString() && 
            item.size === sizeName
		);
        
        if (existing) {
            existing.quantity++;
			} else {
            cart.push({ 
                ...product, 
                id: productId,
                quantity: 1, 
                price: finalPrice, 
                size: sizeName,
                size_price: sizePrice
			});
		}
        updateCart();
        
        if (sizeCheck) sizeCheck.checked = false;
	};
    
    // --- 15. UPDATE CART ---
    window.removeFromCart = function(index) {
        cart.splice(index, 1);
        updateCart();
	};
    
    function updateCart() {
        const cartDiv = document.getElementById("cart");
        const countBadge = document.getElementById("cart-count");
        const totalSpan = document.getElementById("total");
        
        if(!cartDiv) return;
        
        cartDiv.innerHTML = "";
        let total = 0;
        let count = 0;
        
        cart.forEach((item, index) => {
            const subtotal = item.price * item.quantity;
            total += subtotal;
            count += item.quantity;
            const sizeLabel = item.size === "Large" ? "(L)" : "";
            
            cartDiv.innerHTML += `
			<div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-1">
			<div style="line-height:1.2;">
			<span class="fw-bold small">${item.name} ${sizeLabel}</span><br>
			<small class="text-muted" style="font-size:0.75rem;">${item.quantity}x @ ${formatRupiah(item.price)}</small>
			</div>
			<div class="d-flex align-items-center gap-2">
			<span class="fw-bold small">${formatRupiah(subtotal)}</span>
			<i class="fas fa-trash-alt text-danger" style="cursor:pointer; font-size:0.8rem;" onclick="removeFromCart(${index})"></i>
			</div>
			</div>
            `;
		});
        
        if(cart.length === 0) {
            cartDiv.innerHTML = "<p class='text-muted small fst-italic text-center py-3'>Belum ada menu yang dipilih.</p>";
		}
        
        let fee = 0;
        let feeLabel = "";
        if(adminFeePercent > 0) {
            fee = Math.ceil(total * (adminFeePercent / 100));
            feeLabel = ` (+Fee ${adminFeePercent}%)`;
		}
        
        const labelEl = document.querySelector("#total").parentElement.querySelector("span:first-child");
        if(labelEl) labelEl.innerText = `Total${feeLabel}`;
        
        const grandTotal = total + fee;
        
        totalSpan.innerText = formatRupiah(grandTotal);
        countBadge.innerText = count + " Item";
        
        const nominalInput = document.getElementById("nominalInput");
        if(nominalInput) nominalInput.value = grandTotal;
        
        const qrcodeDiv = document.getElementById("qrcode");
        if (grandTotal > 0) {
            generateQRIS();
			} else {
            if(qrcodeDiv) qrcodeDiv.innerHTML = ""; 
		}
	}
    
    // --- 12. SEARCH FUNCTIONALITY ---
    const searchInput = document.getElementById("search");
    const clearBtn = document.getElementById("btn-clear-search");
    
    if (searchInput) {
        searchInput.addEventListener("input", function() {
            const keyword = this.value.toLowerCase();
            
            clearBtn.style.display = this.value.length > 0 ? "block" : "none";
            
            document.querySelectorAll(".col-12.col-md-6").forEach(item => {
                const title = item.querySelector(".product-title").innerText.toLowerCase();
                item.style.display = title.includes(keyword) ? "" : "none";
			});
		});
	}
    
    if (clearBtn) {
        clearBtn.addEventListener("click", () => {
            searchInput.value = "";
            clearBtn.style.display = "none";
            
            document.querySelectorAll(".col-12.col-md-6").forEach(item => {
                item.style.display = "";
			});
            
            searchInput.focus();
		});
	}
    
    // --- 13. ORDER SUBMISSION ---
    const orderForm = document.getElementById("order-form");
    if(orderForm) {
        orderForm.addEventListener("submit", function (e) {
            e.preventDefault();
            if (cart.length === 0) return alert("Keranjang belanja masih kosong!");
            
            const btn = this.querySelector('button[type="submit"]');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            btn.disabled = true;
            
            const name = document.getElementById("name").value;
            const loc = document.getElementById("location").value;
            
            let totalCart = 0;
            cart.forEach(item => totalCart += item.price * item.quantity);
            let fee = 0;
            if(adminFeePercent > 0) fee = Math.ceil(totalCart * (adminFeePercent / 100));
            const grandTotal = totalCart + fee;
            
            const orderData = {
                name: name,
                location: loc,
                total: grandTotal,
                fee: fee,
                items: cart.map(item => ({
                    name: item.name,
                    price: item.price,
                    quantity: item.quantity,
                    size: item.size
				}))
			};
            
            fetch(base_url + 'api/orders/save_order', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json','X-API-KEY': apiKey },
                body: JSON.stringify(orderData)
			})
            .then(res => res.json())
            .then(result => {
                if(result.status === 'success') {
                    let msg = `Halo, saya *${name}* mau pesan di *${loc}* (Order #${result.order_id}):\n\n`;
                    
                    cart.forEach(item => {
                        const size = item.size === "Large" ? "(L)" : "(R)";
                        msg += `- ${item.name} ${size} (${item.quantity}x) : ${formatRupiah(item.price * item.quantity)}\n`;
					});
                    
                    if(fee > 0) msg += `\nAdmin Fee (${adminFeePercent}%): ${formatRupiah(fee)}`;
                    msg += `\n*Total Bayar: ${formatRupiah(grandTotal)}*`;
                    msg += `\n\nMohon diproses, terima kasih!`;
                    
                    const encodedMsg = encodeURIComponent(msg);
                    window.open(`https://wa.me/${waNumber}?text=${encodedMsg}`, '_blank');
                    
                    cart = [];
                    updateCart();
                    orderForm.reset();
					} else {
                    alert("Gagal menyimpan pesanan: " + (result.message || "Unknown error"));
				}
			})
            .catch(err => {
                console.error(err);
                alert("Terjadi kesalahan koneksi saat menyimpan pesanan.");
			})
            .finally(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
			});
		});
	}
    
    // --- 14. QRIS FUNCTIONS ---
    function pad(number) { return number < 10 ? "0" + number : number.toString(); }
    
    function toCRC16(input) {
        let crc = 0xffff;
        for (let i = 0; i < input.length; i++) {
            crc ^= input.charCodeAt(i) << 8;
            for (let j = 0; j < 8; j++) {
                crc = crc & 0x8000 ? (crc << 1) ^ 0x1021 : crc << 1;
			}
		}
        let hex = (crc & 0xffff).toString(16).toUpperCase();
        return hex.length === 3 ? "0" + hex : hex;
	}
    
    function makeString(qris, { nominal } = {}) {
        if (!qris) return "";
        let qrisModified = qris.slice(0, -4).replace("010211", "010212");
        let qrisParts = qrisModified.split("5802ID");
        if(qrisParts.length < 2) return "";
        let amount = "54" + pad(nominal.toString().length) + nominal;
        amount += "5802ID";
        let output = qrisParts[0].trim() + amount + qrisParts[1].trim();
        output += toCRC16(output);
        return output;
	}
    
    function generateQRIS() {
        const qrisInput = document.getElementById("qrisInput");
        const nominalInput = document.getElementById("nominalInput");
        const qrcodeElement = document.getElementById("qrcode");
        
        if (!qrisInput || !nominalInput || !qrcodeElement) return;
        
        const qrisRaw = qrisInput.value.trim();
        const nominal = nominalInput.value.trim();
        
        if (!qrisRaw || !nominal || Number(nominal) <= 0) return;
        
        const qrisFinal = makeString(qrisRaw, { nominal: nominal });
        
        qrcodeElement.innerHTML = ""; 
        try {
            QRCode.toCanvas(qrisFinal, { margin: 1, width: 100 }, function (err, canvas) {
                if (!err) {
                    canvas.style.width = "100%";
                    canvas.style.height = "auto";
                    qrcodeElement.appendChild(canvas);
				}
			});
			} catch (e) {
            console.error("QR Error", e);
		}
	}
    
    // --- 15. DOWNLOAD QRIS ---
    window.downloadQRIS = function() {
        const qrDiv = document.getElementById("qrcode");
        const canvas = qrDiv.querySelector("canvas");
        
        if(canvas) {
            const w = canvas.width;
            const h = canvas.height;
            const compositeCanvas = document.createElement('canvas');
            compositeCanvas.width = w;
            compositeCanvas.height = h;
            
            const ctx = compositeCanvas.getContext('2d');
            ctx.fillStyle = "#FFFFFF";
            ctx.fillRect(0, 0, w, h);
            ctx.drawImage(canvas, 0, 0);
            
            const dataUrl = compositeCanvas.toDataURL("image/png");
            
            const link = document.createElement("a");
            link.download = "QRIS-Pembayaran.png";
            link.href = dataUrl;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
			} else {
            alert("QRIS belum muncul. Silakan pilih menu terlebih dahulu.");
		}
	};
    
    // --- 16. PULL TO REFRESH ---
    let touchStartY = 0;
    let isRefreshing = false;
    
    document.addEventListener('touchstart', (e) => {
        if (window.scrollY === 0) {
            touchStartY = e.touches[0].clientY;
		}
	});
    
    document.addEventListener('touchmove', (e) => {
        if (window.scrollY === 0 && !isRefreshing) {
            const touchY = e.touches[0].clientY;
            const diff = touchY - touchStartY;
            
            if (diff > 100) {
                isRefreshing = true;
                refreshProducts();
			}
		}
	});
    
    // --- 17. REFRESH PRODUCTS ---
    function refreshProducts() {
        // Tampilkan refresh indicator
        const refreshIndicator = document.createElement("div");
        refreshIndicator.id = "refresh-indicator";
        refreshIndicator.className = "text-center py-2 bg-light";
        refreshIndicator.innerHTML = '<i class="fas fa-sync-alt fa-spin me-2"></i>Menyegarkan...';
        
        const productList = document.getElementById("product-list");
        productList.parentElement.insertBefore(refreshIndicator, productList);
        
        // Refresh produk
        loadProducts(true);
        
        // Hapus indicator setelah beberapa detik
        setTimeout(() => {
            const indicator = document.getElementById("refresh-indicator");
            if (indicator) indicator.remove();
            isRefreshing = false;
		}, 2000);
	}
});