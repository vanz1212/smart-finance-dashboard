from PIL import Image, ImageDraw
import math

def draw_banana_pil():
    """
    Program untuk membuat gambar pisang dengan titik-titik menggunakan PIL
    """
    
    # Ukuran canvas
    width = 600
    height = 500
    
    # Buat image dengan background putih
    img = Image.new('RGB', (width, height), color='white')
    draw = ImageDraw.Draw(img)
    
    # Warna
    dot_color = (255, 220, 0)  # Kuning emas
    outline_color = (255, 180, 0)  # Orange
    stem_color = (34, 139, 34)  # Hijau gelap
    
    # Membuat titik-titik pisang menggunakan kurva bezier yang disederhanakan
    dots = []
    center_x = width / 2
    center_y = height / 2
    
    # Generate titik-titik di sepanjang kurva pisang
    for i in range(200):
        angle = (i / 200) * math.pi  # 0 ke pi
        
        # Kurva pisang
        x = center_x + 120 * math.cos(angle)
        y = center_y - 80 * math.sin(angle) + (i / 200) * 80
        
        # Tambahkan jitter kecil
        import random
        random.seed(i)
        x += random.uniform(-5, 5)
        y += random.uniform(-5, 5)
        
        dots.append((x, y))
    
    # Gambar titik-titik
    dot_size = 8
    for x, y in dots:
        draw.ellipse(
            [x - dot_size/2, y - dot_size/2, x + dot_size/2, y + dot_size/2],
            fill=dot_color,
            outline=outline_color,
            width=1
        )
    
    # Gambar batang pisang (stem)
    stem_start_x = center_x + 120
    stem_start_y = center_y + 80
    stem_end_x = center_x + 140
    stem_end_y = center_y + 100
    
    draw.line([(stem_start_x, stem_start_y), (stem_end_x, stem_end_y)], 
              fill=stem_color, width=8)
    
    # Tambahkan daun (leaves)
    leaf1_x = [stem_end_x, stem_end_x + 30, stem_end_x + 20]
    leaf1_y = [stem_end_y, stem_end_y - 20, stem_end_y + 10]
    draw.polygon(list(zip(leaf1_x, leaf1_y)), fill=(34, 139, 34), outline=(0, 100, 0))
    
    leaf2_x = [stem_end_x, stem_end_x + 25, stem_end_x + 10]
    leaf2_y = [stem_end_y, stem_end_y + 20, stem_end_y + 15]
    draw.polygon(list(zip(leaf2_x, leaf2_y)), fill=(50, 160, 50), outline=(0, 100, 0))
    
    # Tambahkan teks
    draw.text((width/2 - 80, 30), "GAMBAR PISANG", fill=(0, 0, 0))
    draw.text((width/2 - 90, height - 40), "Dibuat dengan titik-titik", fill=(100, 100, 100))
    
    # Simpan dan tampilkan
    img.save('pisang_dots_pil.png')
    print("✓ Gambar pisang PIL telah disimpan sebagai 'pisang_dots_pil.png'")
    img.show()

if __name__ == "__main__":
    draw_banana_pil()
