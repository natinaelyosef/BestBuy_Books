from reportlab.lib.pagesizes import letter
from reportlab.pdfgen import canvas
import os

output_path = "overview.pdf"

c = canvas.Canvas(output_path, pagesize=letter)
width, height = letter

line_height = 12
margin = 40

def write_line(text, y):
    c.drawString(margin, y, text)


def process_file(path):
    global y
    write_line(f"=== {path} ===", y)
    y -= line_height
    try:
        with open(path, 'r', encoding='utf-8', errors='replace') as f:
            for line in f:
                if y < margin:
                    c.showPage()
                    y = height - margin
                write_line(line.rstrip(), y)
                y -= line_height
    except Exception as e:
        write_line(f"[Error reading file: {e}]", y)
        y -= line_height
    y -= line_height


if __name__ == '__main__':
    dirs_to_exclude = ['vendor', 'node_modules', '.git', 'storage', 'tests', 'bootstrap/cache', 'storage/framework/cache', 'storage/framework/sessions', 'storage/framework/testing', 'storage/framework/views', 'public/build', 'public/uploads']
    extensions_to_include = ['.php', '.js', '.jsx', '.json', '.md', '.blade.php', '.css', '.scss', '.py', '.xml', '.yml', '.yaml', '.env', '.htaccess', '.txt']
    y = height - margin
    for root, dirs, files in os.walk('.'):
        # Skip excluded directories
        dirs[:] = [d for d in dirs if d not in dirs_to_exclude and os.path.join(root, d) not in [os.path.join('.', ex) for ex in dirs_to_exclude]]
        for file in files:
            if any(file.endswith(ext) for ext in extensions_to_include):
                file_path = os.path.join(root, file)
                process_file(file_path)
                if y < margin:
                    c.showPage()
                    y = height - margin
    c.save()
    print(f"PDF generated at {output_path}")
