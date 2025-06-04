let cart = [];

function addToCart(id, nama, harga) {
    const index = cart.findIndex(item => item.id === id);
    if (index !== -1) {
        cart[index].jumlah += 1;
    } else {
        cart.push({ id, nama, harga, jumlah: 1 });
    }
    renderCart();
}

function removeFromCart(index) {
    cart.splice(index, 1);
    renderCart();
}

function renderCart() {
    const tbody = document.querySelector('#cart-table tbody');
    tbody.innerHTML = '';
    let totalHarga = 0;

    cart.forEach((item, index) => {
        const total = item.harga * item.jumlah;
        totalHarga += total;
        tbody.innerHTML += `
            <tr>
                <td>${item.nama}</td>
                <td>${item.harga}</td>
                <td>${item.jumlah}</td>
                <td>${total}</td>
                <td><button onclick="removeFromCart(${index})">Hapus</button></td>
            </tr>
        `;
    });

    document.getElementById('total-harga').textContent = totalHarga;
}

function submitTransaksi() {
    if (cart.length === 0) {
        alert("Keranjang kosong!");
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "transaksi.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onload = function () {
        if (xhr.status === 200) {
            alert("Transaksi berhasil disimpan!");
            cart = [];
            renderCart();
        } else {
            alert("Gagal menyimpan transaksi.");
        }
    };

    xhr.send(JSON.stringify({ cart }));
}
