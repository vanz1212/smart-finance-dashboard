// Format currency to Indonesian Rupiah
function formatCurrency(value) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(value);
}

// Calculate tax based on progressive tax brackets
function hitungPajak(gaji) {
    let pajak = 0;
    
    if (gaji <= 60000000) {
        pajak = gaji * 0.05;
    } else if (gaji <= 250000000) {
        pajak = (60000000 * 0.05) + ((gaji - 60000000) * 0.15);
    } else if (gaji <= 500000000) {
        pajak = (60000000 * 0.05) + (190000000 * 0.15) + ((gaji - 250000000) * 0.25);
    } else if (gaji <= 5000000000) {
        pajak = (60000000 * 0.05) + (190000000 * 0.15) + (250000000 * 0.25) + ((gaji - 500000000) * 0.30);
    } else {
        pajak = (60000000 * 0.05) + (190000000 * 0.15) + (250000000 * 0.25) + (4500000000 * 0.30) + ((gaji - 5000000000) * 0.35);
    }
    
    return Math.round(pajak);
}

// Get financial status based on expense percentage
function getStatus(persentase) {
    if (persentase > 70) {
        return "PENGELUARAN TINGGI";
    } else if (persentase > 50) {
        return "CUKUP AMAN";
    } else {
        return "KEUANGAN SEHAT";
    }
}

// Switch between tabs
function switchTab(tabName) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(tab => {
        tab.classList.remove('active');
    });
    
    // Remove active class from all buttons
    const tabButtons = document.querySelectorAll('.tab-btn');
    tabButtons.forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Show selected tab
    document.getElementById(tabName).classList.add('active');
    
    // Add active class to clicked button
    event.target.classList.add('active');
}

// Calculate and display dashboard
function calculateDashboard(e) {
    e.preventDefault();
    
    const nama = document.getElementById('nama').value;
    const pemasukan = parseInt(document.getElementById('pemasukan').value);
    const pengeluaran = parseInt(document.getElementById('pengeluaran').value);
    
    // Validation
    if (pemasukan < 0 || pengeluaran < 0) {
        alert('❌ Pemasukan dan pengeluaran tidak boleh negatif!');
        return;
    }
    
    const pajak = hitungPajak(pemasukan);
    const setelahPajak = pemasukan - pajak;
    const saldo = setelahPajak - pengeluaran;
    const persentase = (pengeluaran / setelahPajak) * 100;
    const status = getStatus(persentase);
    
    // Display results
    document.getElementById('resultNama').textContent = nama || '-';
    document.getElementById('resultPemasukan').textContent = formatCurrency(pemasukan);
    document.getElementById('resultPajak').textContent = formatCurrency(pajak);
    document.getElementById('resultSetelahPajak').textContent = formatCurrency(setelahPajak);
    document.getElementById('resultPengeluaran').textContent = formatCurrency(pengeluaran);
    document.getElementById('resultSaldo').textContent = formatCurrency(saldo);
    document.getElementById('resultPersentase').textContent = persentase.toFixed(2) + '%';
    document.getElementById('resultStatus').textContent = status;
    
    // Update progress bar
    const progressBar = document.getElementById('progressBar');
    const progressPercent = Math.min(persentase, 100);
    progressBar.style.width = progressPercent + '%';
    progressBar.textContent = persentase.toFixed(1) + '%';
    
    // Show result
    document.getElementById('dashboardResult').classList.remove('hidden');
}

// Generate dynamic forms for comparison
function generateForm() {
    const jumlah = parseInt(document.getElementById('jumlahIndividu').value);
    
    if (jumlah < 1 || jumlah > 10) {
        alert('Masukkan jumlah individu antara 1-10');
        return;
    }
    
    const dynamicForms = document.getElementById('dynamicForms');
    dynamicForms.innerHTML = '';
    
    for (let i = 0; i < jumlah; i++) {
        const formHTML = `
            <div class="individual-form">
                <h3>Individu ${i + 1}</h3>
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" class="nama-input" placeholder="Masukkan nama">
                </div>
                <div class="form-group">
                    <label>Gaji Tahunan (Rp)</label>
                    <input type="number" class="gaji-input" placeholder="0" min="0">
                </div>
            </div>
        `;
        dynamicForms.innerHTML += formHTML;
    }
    
    // Add calculate button
    const btnHTML = `
        <div style="grid-column: 1 / -1;">
            <button type="button" class="btn-primary" onclick="calculateComparison()" style="margin-top: 20px;">
                Hitung Perbandingan
            </button>
        </div>
    `;
    dynamicForms.innerHTML += btnHTML;
}

// Calculate and display comparison
function calculateComparison() {
    const namaInputs = document.querySelectorAll('.nama-input');
    const gajiInputs = document.querySelectorAll('.gaji-input');
    
    const dataIndividu = [];
    let isValid = true;
    
    namaInputs.forEach((input, idx) => {
        const nama = input.value.trim();
        const gaji = parseInt(gajiInputs[idx].value);
        
        if (!nama || !gaji || gaji < 0) {
            isValid = false;
            return;
        }
        
        dataIndividu.push({ nama, gaji });
    });
    
    if (!isValid) {
        alert('❌ Lengkapi semua data dengan benar!');
        return;
    }
    
    // Calculate totals
    let totalGaji = 0;
    let totalPajak = 0;
    let totalSetelahPajak = 0;
    
    const tableHTML = dataIndividu.map((item, idx) => {
        const pajak = hitungPajak(item.gaji);
        const setelahPajak = item.gaji - pajak;
        const tarifEfektif = ((pajak / item.gaji) * 100).toFixed(2);
        
        totalGaji += item.gaji;
        totalPajak += pajak;
        totalSetelahPajak += setelahPajak;
        
        return `
            <tr>
                <td>${idx + 1}</td>
                <td>${item.nama}</td>
                <td>${formatCurrency(item.gaji)}</td>
                <td>${formatCurrency(pajak)}</td>
                <td>${formatCurrency(setelahPajak)}</td>
                <td>${tarifEfektif}%</td>
            </tr>
        `;
    }).join('');
    
    const avgTarif = ((totalPajak / totalGaji) * 100).toFixed(2);
    
    document.getElementById('comparisonTableBody').innerHTML = tableHTML;
    document.getElementById('totalGaji').innerHTML = `<strong>${formatCurrency(totalGaji)}</strong>`;
    document.getElementById('totalPajak').innerHTML = `<strong>${formatCurrency(totalPajak)}</strong>`;
    document.getElementById('totalSetelahPajak').innerHTML = `<strong>${formatCurrency(totalSetelahPajak)}</strong>`;
    document.getElementById('avgTarif').innerHTML = `<strong>${avgTarif}%</strong>`;
    
    // Show result
    document.getElementById('comparisonResult').classList.remove('hidden');
    
    // Draw chart
    drawComparisonChart(dataIndividu);
}

// Draw comparison chart
function drawComparisonChart(dataIndividu) {
    const canvas = document.getElementById('comparisonChart');
    const ctx = canvas.getContext('2d');
    
    // Clear canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    // Set canvas size
    canvas.width = canvas.offsetWidth;
    canvas.height = 300;
    
    const padding = 50;
    const chartWidth = canvas.width - padding * 2;
    const chartHeight = canvas.height - padding * 2;
    
    // Colors
    const colorGaji = '#10b981';
    const colorPajak = '#ef4444';
    const colorSetelahPajak = '#2563eb';
    
    // Find max value for scaling
    const maxGaji = Math.max(...dataIndividu.map(d => d.gaji));
    const scale = chartHeight / maxGaji;
    
    // Bar spacing
    const barWidth = chartWidth / (dataIndividu.length * 3);
    const spacing = chartWidth / dataIndividu.length;
    
    // Draw bars
    dataIndividu.forEach((item, idx) => {
        const pajak = hitungPajak(item.gaji);
        const setelahPajak = item.gaji - pajak;
        
        const xStart = padding + (idx * spacing) + spacing / 4;
        
        // Gaji bar
        const gajiHeight = item.gaji * scale;
        ctx.fillStyle = colorGaji;
        ctx.fillRect(xStart, padding + chartHeight - gajiHeight, barWidth, gajiHeight);
        
        // Pajak bar
        const pajakHeight = pajak * scale;
        ctx.fillStyle = colorPajak;
        ctx.fillRect(xStart + barWidth + 5, padding + chartHeight - pajakHeight, barWidth, pajakHeight);
        
        // Setelah pajak bar
        const setelahHeight = setelahPajak * scale;
        ctx.fillStyle = colorSetelahPajak;
        ctx.fillRect(xStart + (barWidth * 2) + 10, padding + chartHeight - setelahHeight, barWidth, setelahHeight);
        
        // Draw name label
        ctx.fillStyle = '#1e293b';
        ctx.font = '12px Arial';
        ctx.textAlign = 'center';
        ctx.fillText(item.nama.substring(0, 10), xStart + barWidth + 2.5, padding + chartHeight + 20);
    });
    
    // Draw axes
    ctx.strokeStyle = '#cbd5e1';
    ctx.lineWidth = 2;
    ctx.beginPath();
    ctx.moveTo(padding, padding);
    ctx.lineTo(padding, padding + chartHeight);
    ctx.lineTo(padding + chartWidth, padding + chartHeight);
    ctx.stroke();
    
    // Draw legend
    const legendY = padding - 20;
    const legendItems = [
        { color: colorGaji, label: 'Gaji' },
        { color: colorPajak, label: 'Pajak' },
        { color: colorSetelahPajak, label: 'Setelah Pajak' }
    ];
    
    let legendX = padding;
    legendItems.forEach(item => {
        ctx.fillStyle = item.color;
        ctx.fillRect(legendX, legendY - 12, 12, 12);
        
        ctx.fillStyle = '#1e293b';
        ctx.font = '12px Arial';
        ctx.textAlign = 'left';
        ctx.fillText(item.label, legendX + 18, legendY);
        
        legendX += 100;
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Add enter key support for dashboard form
    document.getElementById('dashboardForm').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            calculateDashboard(e);
        }
    });
    
    // Initialize first form
    generateForm();
});
