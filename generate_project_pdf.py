from reportlab.lib.pagesizes import A4
from reportlab.platypus import SimpleDocTemplate, Paragraph, Spacer, Preformatted, PageBreak
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.lib.enums import TA_LEFT, TA_CENTER
from reportlab.lib import colors
import os
from datetime import datetime

def generate_project_pdf():
    """
    Generates a PDF containing the project code and directory structure,
    keeping the file size under 10MB as per project specifications.
    """
    # Output PDF file
    output_path = "project_documentation.pdf"
    doc = SimpleDocTemplate(
        output_path,
        pagesize=A4,
        rightMargin=50,
        leftMargin=50,
        topMargin=50,
        bottomMargin=50
    )
    
    # Get styles
    styles = getSampleStyleSheet()
    
    # Custom styles for better compactness
    title_style = ParagraphStyle(
        'CustomTitle',
        parent=styles['Heading1'],
        fontSize=16,
        spaceAfter=15,
        alignment=TA_CENTER,
        textColor=(0.2, 0.2, 0.6)
    )
    
    subtitle_style = ParagraphStyle(
        'Subtitle',
        parent=styles['Normal'],
        fontSize=10,
        spaceAfter=12,
        alignment=TA_CENTER,
        textColor=(0.3, 0.3, 0.3)
    )
    
    header_style = ParagraphStyle(
        'Header',
        parent=styles['Heading2'],
        fontSize=12,
        spaceAfter=8
    )
    
    file_header_style = ParagraphStyle(
        'FileHeader',
        parent=styles['Heading3'],
        fontSize=9,
        spaceAfter=4,
        borderWidth=0.5,
        borderColor='gray',
        borderPadding=2,
        backColor=colors.lightgrey
    )
    
    code_style = ParagraphStyle(
        'Code',
        parent=styles['Code'],
        fontSize=7,
        leading=9
    )
    
    truncate_style = ParagraphStyle(
        'TruncateNote',
        parent=styles['Normal'],
        fontSize=6,
        textColor=colors.red
    )
    
    # Create story
    story = []
    
    # Title
    story.append(Paragraph('BestBuy Books Project Documentation', title_style))
    story.append(Paragraph(f'Generated on: {datetime.now().strftime("%Y-%m-%d %H:%M:%S")}', subtitle_style))
    story.append(Spacer(1, 15))
    
    # Project overview
    story.append(Paragraph('Project Overview', header_style))
    story.append(Paragraph(
        'BestBuy_Books is an e-commerce platform built with Laravel framework supporting multi-role users '
        '(Administrators, Merchants, Customers) for managing books, orders, chats, and issue reports. '
        'The project implements real-time communication between users and administrators, merchant '
        'registration approval process, and role-based access control.',
        styles['Normal']
    ))
    story.append(Spacer(1, 15))
    
    # Directory structure
    story.append(Paragraph('Directory Structure', header_style))
    story.append(Spacer(1, 10))
    
    # Generate directory tree
    dir_tree = generate_directory_tree(r"c:\xampp\htdocs\BestBuy_Books - Copy")
    story.append(Preformatted(dir_tree, code_style))
    story.append(PageBreak())
    
    # Process files with strict size limits as per project specifications
    dirs_to_exclude = [
        'vendor', 'node_modules', '.git', 'storage', 'tests', 
        'bootstrap/cache', 'storage/framework', 'public/build', 
        'resources/js', 'resources/css', 'database/factories', 
        'database/seeders', '__pycache__', '.vscode', '.idea'
    ]
    
    extensions_to_include = [
        '.php', '.blade.php', '.py', '.txt', '.env', '.sql', 
        '.xml', '.yml', '.yaml', '.md', '.json'
    ]
    
    # Process core application files with size limits per specification
    root_path = r"c:\xampp\htdocs\BestBuy_Books - Copy"
    total_chars_added = 0
    files_processed = 0
    
    for root, dirs, files in os.walk(root_path):
        # Remove excluded directories
        dirs[:] = [d for d in dirs if d not in dirs_to_exclude]
        
        for file in files:
            if any(file.lower().endswith(ext) for ext in extensions_to_include):
                file_path = os.path.join(root, file)
                
                # Calculate relative path
                rel_path = os.path.relpath(file_path, root_path)
                
                # Skip very large files per specification (>50KB)
                try:
                    file_size = os.path.getsize(file_path)
                    if file_size > 50000:  # Skip files larger than 50KB
                        continue
                except OSError:
                    continue
                
                # Check if we're approaching size limit to stay under 10MB
                if total_chars_added > 800000:  # Stop before reaching 10MB
                    story.append(Paragraph(
                        'Document size limit reached. Remaining files not included.', 
                        ParagraphStyle('LimitNote', parent=styles['Normal'], textColor=colors.red)
                    ))
                    break
                
                # Add file header
                story.append(Paragraph(f'File: {rel_path}', file_header_style))
                
                # Read and add file content with limit per specification (max 20KB)
                try:
                    with open(file_path, 'r', encoding='utf-8', errors='replace') as f:
                        content = f.read(20000)  # Limit to first 20k chars per file per spec
                        
                        # Add content
                        story.append(Preformatted(content, code_style))
                        
                        # Add size warning if needed
                        if len(content) >= 20000:
                            story.append(Paragraph(
                                '(Content truncated due to size - showing first 20KB)', 
                                truncate_style
                            ))
                        
                        total_chars_added += len(content)
                        files_processed += 1
                        
                except Exception as e:
                    story.append(Paragraph(
                        f'[Could not read file: {str(e)}]', 
                        ParagraphStyle('ErrorNote', parent=styles['Normal'], textColor=colors.red)
                    ))
                
                story.append(Spacer(1, 8))
        
        if total_chars_added > 800000:  # Stop before reaching 10MB
            break
    
    # Add summary
    story.append(PageBreak())
    story.append(Paragraph('Summary', header_style))
    story.append(Paragraph(f'Total files processed: {files_processed}', styles['Normal']))
    story.append(Paragraph(f'Total characters added: {total_chars_added:,}', styles['Normal']))
    
    # Build document
    doc.build(story)
    print(f"Project PDF generated successfully at {output_path}")
    print(f"File size: {os.path.getsize(output_path) / (1024*1024):.2f} MB")
    print(f"Files processed: {files_processed}")


def generate_directory_tree(startpath):
    """
    Generate a tree-like representation of the directory structure
    with depth and file count limits to keep the PDF compact
    """
    output = []
    for root, dirs, files in os.walk(startpath):
        # Limit depth to prevent extremely deep trees
        depth = root.replace(startpath, '').count(os.sep)
        if depth > 5:  # Limit depth to 5 levels
            continue
            
        # Get the level by counting path separators
        level = depth
        indent = ' │   ' * level
        
        # Get basename of the folder
        folder_name = os.path.basename(root)
        if folder_name:  # Skip empty names
            output.append(f'{indent} ├─ {folder_name}/')
        
        subindent = ' │   ' * (level + 1)
        count = 0
        for file in files:
            if count >= 15:  # Limit files listed per directory to keep compact
                output.append(f'{subindent} ... and more files')
                break
            output.append(f'{subindent} ├─ {file}')
            count += 1
    
    return '\n'.join(output[:500])  # Limit to prevent huge outputs


if __name__ == "__main__":
    generate_project_pdf()