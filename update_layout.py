path = 'resources/views/layouts/app.blade.php'
with open(path, 'r', encoding='utf-8') as f:
    content = f.read()

content = content.replace("@yield('content')", "@yield('content')\n            {{ $slot ?? '' }}")

with open(path, 'w', encoding='utf-8') as f:
    f.write(content)
