nama = input("Masukkan nama: ")

pemasukan = int(input("Masukkan pemasukan: "))
pengeluaran = int(input("Masukkan pengeluaran: "))

saldo = pemasukan - pengeluaran

persentase = (pengeluaran / pemasukan) * 100


if persentase > 70:
    status = "PENGELUARAN TINGGI"
elif persentase > 50:
    status = "CUKUP AMAN"
else:
    status = "KEUANGAN SEHAT"


print("\n==========================")
print(" SMART FINANCE DASHBOARD ")
print("==========================")

print("Nama             :", nama)
print("Pemasukan        : Rp", pemasukan)
print("Pengeluaran      : Rp", pengeluaran)
print("Saldo            : Rp", saldo)
print("Status Keuangan  :", status)

print("==========================")