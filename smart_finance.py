def hitung_pajak(gaji):
    """
    Menghitung pajak penghasilan (PPh) berdasarkan sistem pajak progresif Indonesia
    Bracket pajak:
    - Rp 0 - Rp 60.000.000: 5%
    - Rp 60.000.001 - Rp 250.000.000: 15%
    - Rp 250.000.001 - Rp 500.000.000: 25%
    - Rp 500.000.001 - Rp 5.000.000.000: 30%
    - Rp 5.000.000.001+: 35%
    """
    if gaji <= 60000000:
        pajak = gaji * 0.05
    elif gaji <= 250000000:
        pajak = (60000000 * 0.05) + ((gaji - 60000000) * 0.15)
    elif gaji <= 500000000:
        pajak = (60000000 * 0.05) + (190000000 * 0.15) + ((gaji - 250000000) * 0.25)
    elif gaji <= 5000000000:
        pajak = (60000000 * 0.05) + (190000000 * 0.15) + (250000000 * 0.25) + ((gaji - 500000000) * 0.30)
    else:
        pajak = (60000000 * 0.05) + (190000000 * 0.15) + (250000000 * 0.25) + (4500000000 * 0.30) + ((gaji - 5000000000) * 0.35)
    
    return pajak

def tampilkan_dashboard_individu(nama, pemasukan, pengeluaran):
    """Menampilkan dashboard keuangan untuk satu individu"""
    saldo = pemasukan - pengeluaran
    persentase = (pengeluaran / pemasukan) * 100
    pajak = hitung_pajak(pemasukan)
    pendapatan_setelah_pajak = pemasukan - pajak
    
    if persentase > 70:
        status = "PENGELUARAN TINGGI"
    elif persentase > 50:
        status = "CUKUP AMAN"
    else:
        status = "KEUANGAN SEHAT"
    
    print("\n" + "="*50)
    print(" SMART FINANCE DASHBOARD - TAX CALCULATOR")
    print("="*50)
    print(f"Nama                    : {nama}")
    print(f"Pemasukan               : Rp {pemasukan:,.0f}")
    print(f"Pajak Penghasilan (PPh)  : Rp {pajak:,.0f}")
    print(f"Pendapatan Setelah Pajak : Rp {pendapatan_setelah_pajak:,.0f}")
    print(f"Pengeluaran             : Rp {pengeluaran:,.0f}")
    print(f"Saldo                   : Rp {saldo:,.0f}")
    print(f"Persentase Pengeluaran  : {persentase:.2f}%")
    print(f"Status Keuangan         : {status}")
    print("="*50)

def tampilkan_perbandingan_pajak(data_individu):
    """Menampilkan perbandingan pajak untuk multiple individu"""
    print("\n" + "="*70)
    print(" PERBANDINGAN PAJAK PENGHASILAN")
    print("="*70)
    print(f"{'No':<4} {'Nama':<15} {'Gaji':<18} {'Pajak':<18} {'Setelah Pajak':<15}")
    print("-"*70)
    
    total_gaji = 0
    total_pajak = 0
    
    for idx, (nama, gaji) in enumerate(data_individu, 1):
        pajak = hitung_pajak(gaji)
        setelah_pajak = gaji - pajak
        total_gaji += gaji
        total_pajak += pajak
        
        print(f"{idx:<4} {nama:<15} Rp {gaji:>15,.0f} Rp {pajak:>15,.0f} Rp {setelah_pajak:>12,.0f}")
    
    print("-"*70)
    print(f"{'TOTAL':<19} Rp {total_gaji:>15,.0f} Rp {total_pajak:>15,.0f}")
    print("="*70)

# Menu utama
while True:
    print("\n" + "="*50)
    print(" MENU SMART FINANCE DASHBOARD")
    print("="*50)
    print("1. Lihat dashboard keuangan individu")
    print("2. Bandingkan pajak multiple individu")
    print("3. Keluar")
    print("="*50)
    
    pilihan = input("Pilih menu (1-3): ").strip()
    
    if pilihan == "1":
        nama = input("Masukkan nama: ").strip()
        try:
            pemasukan = int(input("Masukkan pemasukan (Rp): "))
            pengeluaran = int(input("Masukkan pengeluaran (Rp): "))
            
            if pemasukan < 0 or pengeluaran < 0:
                print("❌ Pemasukan dan pengeluaran tidak boleh negatif!")
                continue
                
            tampilkan_dashboard_individu(nama, pemasukan, pengeluaran)
        except ValueError:
            print("❌ Input tidak valid! Masukkan angka yang benar.")
    
    elif pilihan == "2":
        jumlah = int(input("Berapa banyak individu yang ingin dibandingkan? "))
        data_individu = []
        
        for i in range(jumlah):
            print(f"\n--- Individu {i+1} ---")
            nama = input("Masukkan nama: ").strip()
            try:
                gaji = int(input("Masukkan gaji tahunan (Rp): "))
                if gaji < 0:
                    print("❌ Gaji tidak boleh negatif!")
                    continue
                data_individu.append((nama, gaji))
            except ValueError:
                print("❌ Input tidak valid!")
                continue
        
        if data_individu:
            tampilkan_perbandingan_pajak(data_individu)
    
    elif pilihan == "3":
        print("Terima kasih telah menggunakan Smart Finance Dashboard!")
        break
    
    else:
        print("❌ Pilihan tidak valid! Silakan pilih 1-3.")