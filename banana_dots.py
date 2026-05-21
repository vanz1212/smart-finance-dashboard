import matplotlib.pyplot as plt
import numpy as np

def draw_banana_dots():
    """
    Program untuk membuat gambar pisang dengan titik-titik
    """
    # Membuat figure dan axis
    fig, ax = plt.subplots(1, 1, figsize=(10, 8))
    
    # Membuat kurva pisang menggunakan persamaan parametrik
    t = np.linspace(0, np.pi, 200)
    
    # Persamaan lengkung pisang
    x = 4 * np.cos(t)
    y = 2 * np.sin(t) + 0.5 * t
    
    # Membuat titik-titik di sepanjang kurva pisang
    num_dots = 150
    t_dots = np.linspace(0, np.pi, num_dots)
    x_dots = 4 * np.cos(t_dots)
    y_dots = 2 * np.sin(t_dots) + 0.5 * t_dots
    
    # Menambahkan variasi kecil untuk efek yang lebih alami
    np.random.seed(42)
    x_dots += np.random.normal(0, 0.1, num_dots)
    y_dots += np.random.normal(0, 0.1, num_dots)
    
    # Plot titik-titik dengan warna kuning
    ax.scatter(x_dots, y_dots, s=100, c='gold', edgecolors='orange', linewidth=1.5, alpha=0.8, zorder=2)
    
    # Menambahkan outline pisang dengan warna hijau untuk batang
    stem_x = [5, 5.2]
    stem_y = [y_dots[-1], y_dots[-1] + 0.5]
    ax.plot(stem_x, stem_y, 'g-', linewidth=3, label='Batang')
    
    # Dekorasi
    ax.set_xlim(-5, 6)
    ax.set_ylim(-1, 4)
    ax.set_aspect('equal')
    ax.grid(True, alpha=0.3, linestyle='--')
    ax.set_xlabel('X', fontsize=12)
    ax.set_ylabel('Y', fontsize=12)
    ax.set_title('🍌 Gambar Pisang dengan Titik-Titik 🍌', fontsize=16, fontweight='bold')
    ax.legend(fontsize=10)
    
    # Simpan dan tampilkan
    plt.tight_layout()
    plt.savefig('pisang_dots.png', dpi=150, bbox_inches='tight')
    print("✓ Gambar pisang telah disimpan sebagai 'pisang_dots.png'")
    plt.show()

if __name__ == "__main__":
    draw_banana_dots()
