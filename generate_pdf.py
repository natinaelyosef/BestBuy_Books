from reportlab.lib.pagesizes import letter, A4
from reportlab.platypus import SimpleDocTemplate, Paragraph, Spacer, Preformatted, PageBreak
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.lib.units import inch
from reportlab.platypus.tables import Table
from reportlab.platypus.tableofcontents import TableOfContents
from reportlab.lib.enums import TA_LEFT, TA_CENTER
from reportlab.lib import colors
import os
import time
from datetime import datetime

def generate_project_pdf():
    # Output PDF file
    output_path = "project_documentation.pdf"
    doc = SimpleDocTemplate(
        output_path,
        pagesize=A4,
        rightMargin=72,
        leftMargin=72,
        topMargin=72,
        bottomMargin=72
    )
    
    # Get styles
    styles = getSampleStyleSheet()
    title_style = ParagraphStyle(
        'CustomTitle',
        parent=styles['Heading1'],
        fontSize=24,
        spaceAfter=30,
        alignment=TA_CENTER,
        textColor=(0.2, 0.2, 0.6)
    )
    
    subtitle_style = ParagraphStyle(
        'Subtitle',
        parent=styles['Normal'],
        fontSize=14,
        spaceAfter=20,
        alignment=TA_CENTER,
        textColor=(0.3, 0.3, 0.3)
    )
    
    header_style = ParagraphStyle(
        'Header',
        parent=styles['Heading2'],
        fontSize=16,
        spaceAfter=12,
        borderWidth=1,
        borderColor='black',
        borderPadding=10
    )
    
    file_header_style = ParagraphStyle(
        'FileHeader',
        parent=styles['Heading3'],
        fontSize=12,
        spaceAfter=6,
        borderWidth=1,
        borderColor='gray',
        borderPadding=6,
        backColor=colors.lightgrey
    )
    
    # Create story
    story = []
    
    # Title
    story.append(Paragraph('BestBuy Books Project Documentation', title_style))
    story.append(Paragraph(f'Generated on: {datetime.now().strftime("%Y-%m-%d %H:%M:%S")}', subtitle_style))
    story.append(Spacer(1, 20))
    
    # Project overview
    story.append(Paragraph('Project Overview', header_style))
    story.append(Paragraph(
        'BestBuy_Books is an e-commerce platform built with Laravel framework supporting multi-role users '
        '(Administrators, Merchants, Customers) for managing books, orders, chats, and issue reports. '
        'The project implements real-time communication between users and administrators, merchant '
        'registration approval process, and role-based access control.',
        styles['Normal']
    ))
    story.append(Spacer(1, 20))
    
    # Directory structure
    story.append(Paragraph('Directory Structure', header_style))
    story.append(Spacer(1, 12))
    
    # Generate directory tree
    dir_tree = generate_directory_tree(r"c:\xampp\htdocs\BestBuy_Books - Copy")
    story.append(Preformatted(dir_tree, styles['Code']))
    story.append(PageBreak())
    
    # Add table of contents placeholder
    toc = TableOfContents()
    toc.levelStyles = [
        ParagraphStyle(
            fontName='Times-Bold',
            fontSize=14,
            name='TOCHeading1',
            leftIndent=20,
            firstLineIndent=-20,
            spaceBefore=10,
            leading=16
        ),
        ParagraphStyle(
            fontName='Times-Roman',
            fontSize=12,
            name='TOCHeading2',
            leftIndent=40,
            firstLineIndent=-20,
            spaceBefore=5,
            leading=12
        )
    ]
    story.append(toc)
    story.append(PageBreak())
    
    # Process files with size limits
    dirs_to_exclude = [
        'vendor', 'node_modules', '.git', 'storage', 'tests', 
        'bootstrap/cache', 'storage/framework', 'public/build', 
        'resources/js', 'resources/css', 'database/factories', 
        'database/seeders'
    ]
    
    extensions_to_include = [
        '.php', '.blade.php', '.js', '.jsx', '.json', '.md', 
        '.css', '.py', '.txt', '.env', '.sql', '.xml', '.yml', 
        '.yaml', '.html', '.twig'
    ]
    
    # Process core application files
    root_path = r"c:\xampp\htdocs\BestBuy_Books - Copy"
    for root, dirs, files in os.walk(root_path):
        # Remove excluded directories
        dirs[:] = [d for d in dirs if d not in dirs_to_exclude]
        
        for file in files:
            if any(file.lower().endswith(ext) for ext in extensions_to_include):
                file_path = os.path.join(root, file)
                
                # Calculate relative path
                rel_path = os.path.relpath(file_path, root_path)
                
                # Skip very large files
                try:
                    file_size = os.path.getsize(file_path)
                    if file_size > 100000:  # Skip files larger than 100KB
                        continue
                except OSError:
                    continue
                
                # Add file header
                story.append(Paragraph(f'File: {rel_path}', file_header_style))
                
                # Read and add file content with limit
                try:
                    with open(file_path, 'r', encoding='utf-8', errors='replace') as f:
                        content = f.read(50000)  # Limit to first 50k chars per file
                        
                        # Add content
                        story.append(Preformatted(content, styles['Code']))
                        
                        # Add size warning if needed
                        if len(content) >= 50000:
                            story.append(Paragraph('(Content truncated due to size)', 
                                                 ParagraphStyle(
                                                     'TruncateNote',
                                                     parent=styles['Normal'],
                                                     fontSize=8,
                                                     textColor='red'
                                                 )))
                except Exception as e:
                    story.append(Paragraph(f'[Could not read file: {str(e)}]', 
                                         ParagraphStyle(
                                             'ErrorNote',
                                             parent=styles['Normal'],
                                             textColor='red'
                                         )))
                
                story.append(Spacer(1, 20))
    
    # Build document
    doc.multiBuild(story)
    print(f"PDF generated successfully at {output_path}")
    print(f"File size: {os.path.getsize(output_path) / (1024*1024):.2f} MB")


def generate_directory_tree(startpath):
    """Generate a tree-like representation of the directory structure"""
    output = []
    # Normalize path separators
    startpath = os.path.normpath(startpath)
    for root, dirs, files in os.walk(startpath):
        # Get the level by counting path separators
        level = root.replace(startpath, '').count(os.sep)
        indent = ' │   ' * level
        
        # Get basename of the folder
        folder_name = os.path.basename(root)
        output.append(f'{indent} ├─ {folder_name}/')
        
        subindent = ' │   ' * (level + 1)
        for i, file in enumerate(files):
            if i == len(files) - 1:
                output.append(f'{subindent} └─ {file}')
            else:
                output.append(f'{subindent} ├─ {file}')
    
    return '\n'.join(output[:1000])  # Limit to prevent huge outputs


def split_large_content(content, max_chars_per_part=50000):
    """Split large content into smaller parts"""
    parts = []
    for i in range(0, len(content), max_chars_per_part):
        parts.append(content[i:i+max_chars_per_part])
    return parts


if __name__ == "__main__":
    generate_project_pdf()