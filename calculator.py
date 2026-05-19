def kalkulator():
    print("=== Kalkulator Sederhana ===")
    print("Operasi: + - * /")
    
    try:
        a = float(input("Masukkan angka pertama : "))
        op = input("Masukkan operasi (+,-,*,/): ")
        b = float(input("Masukkan angka kedua   : "))
    except ValueError:
        print("Input tidak valid!")
        return

    if op == "+":
        hasil = a + b
        nama = "Penjumlahan"
    elif op == "-":
        hasil = a - b
        nama = "Pengurangan"
    elif op == "*":
        hasil = a * b
        nama = "Perkalian"
    elif op == "/":
        if b == 0:
            print("Error: Tidak bisa dibagi nol!")
            return
        hasil = a / b
        nama = "Pembagian"
    else:
        print("Operasi tidak dikenal!")
        return

    print(f"\n{nama}: {a} {op} {b} = {hasil}")

kalkulator()