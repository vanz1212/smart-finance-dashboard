#!/usr/bin/env python3
# -*- coding: utf-8 -*-

def draw_banana_ascii():
    """
    Program untuk membuat gambar pisang ASCII dengan titik-titik
    """
    
    banana_ascii = """
    
            ·  ·  ·  ·  ·  ·  ·
          ·    ·    ·    ·    ·
        ·      ·      ·      ·
      ·        ·        ·        ·
    ·          ·          ·          ·
    ·          ·          ·          ·
      ·        ·        ·        ·
        ·      ·      ·      ·
          ·    ·    ·    ·    ·
            ·  ·  ·  ·  ·  ·
              ·  ·  ·  ·
                ·  ·
                  |
                  |
                 /|\\
    
    """
    
    print("=" * 50)
    print("       🍌 GAMBAR PISANG DENGAN TITIK 🍌")
    print("=" * 50)
    print(banana_ascii)
    print("=" * 50)
    print("Pisang dibuat dengan titik-titik (·)")
    print("=" * 50)

if __name__ == "__main__":
    draw_banana_ascii()
