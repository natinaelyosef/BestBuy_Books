from reportlab.lib.pagesizes import A4
from reportlab.platypus import SimpleDocTemplate, Paragraph, Spacer, Preformatted, PageBreak
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.lib.enums import TA_LEFT, TA_CENTER
from reportlab.lib import colors
import os
from datetime import datetime

def generate_compact_project_pdf():
    """
    Generates a compact PDF containing the project code and directory structure,
    keeping the file size under 10MB as per project specifications.
    """
    # Output PDF file
    output_path = "bestbuy_books_project_documentation.pdf"
    doc = SimpleDocTemplate(
        output_path,
        pagesize=A4,
        rightMargin=30,
        leftMargin=30,
        topMargin=30,
        bottomMargin=30
    )
    
    # Get styles
    styles = getSampleStyleSheet()
    
    # Compact styles
    title_style = ParagraphStyle(
        'CompactTitle',
        parent=styles['Heading1'],
        fontSize=14,
        spaceAfter=8,
        alignment=TA_CENTER,
        textColor=(0.2, 0.2, 0.6)
    )
    
    subtitle_style = ParagraphStyle(
        'CompactSubtitle',
        parent=styles['Normal'],
        fontSize=8,
        spaceAfter=8,
        alignment=TA_CENTER,
        textColor=(0.3, 0.3, 0.3)
    )
    
    header_style = ParagraphStyle(
        'CompactHeader',
        parent=styles['Heading2'],
        fontSize=10,
        spaceAfter=6
    )
    
    file_header_style = ParagraphStyle(
        'CompactFileHeader',
        parent=styles['Heading3'],
        fontSize=7,
        spaceAfter=2,
        borderWidth=0.5,
        borderColor='gray',
        borderPadding=1,
        backColor=colors.lightgrey
    )
    
    code_style = ParagraphStyle(
        'CompactCode',
        parent=styles['Code'],
        fontSize=5,
        leading=6  # Very tight spacing for code
    )
    
    truncate_style = ParagraphStyle(
        'CompactTruncateNote',
        parent=styles['Normal'],
        fontSize=5,
        textColor=colors.red
    )
    
    # Create story
    story = []
    
    # Title
    story.append(Paragraph('BestBuy Books Project Documentation', title_style))
    story.append(Paragraph(f'Generated on: {datetime.now().strftime("%Y-%m-%d %H:%M:%S")}', subtitle_style))
    story.append(Spacer(1, 10))
    
    # Project overview
    story.append(Paragraph('Project Overview', header_style))
    story.append(Paragraph(
        'BestBuy_Books is an e-commerce platform built with Laravel framework supporting multi-role users '
        '(Administrators, Merchants, Customers) for managing books, orders, chats, and issue reports. '
        'The project implements real-time communication between users and administrators, merchant '
        'registration approval process, and role-based access control.',
        ParagraphStyle(
            'CompactNormal',
            parent=styles['Normal'],
            fontSize=6,
            leading=7
        )
    ))
    story.append(Spacer(1, 10))
    
    # Directory structure
    story.append(Paragraph('Directory Structure', header_style))
    story.append(Spacer(1, 6))
    
    # Generate directory tree
    dir_tree = generate_directory_tree(r"c:\xampp\htdocs\BestBuy_Books")
    story.append(Preformatted(dir_tree, code_style))
    story.append(PageBreak())
    
    # Process files with strict size limits as per project specifications
    dirs_to_exclude = [
        'vendor', 'node_modules', '.git', 'storage', 'tests', 
        'bootstrap/cache', 'storage/framework', 'public/build', 
        'resources/js', 'resources/css', 'database/factories', 
        'database/seeders', '__pycache__', '.vscode', '.idea', 
        '.venv', 'env'
    ]
    
    extensions_to_include = [
        '.php', '.blade.php', '.py', '.txt', '.env', '.sql', 
        '.xml', '.yml', '.yaml', '.md', '.json', '.js', '.jsx'
    ]
    
    # Process core application files with size limits per specification
    root_path = r"c:\xampp\htdocs\BestBuy_Books"
    total_chars_added = 0
    files_processed = 0
    skipped_files = 0
    
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
                        skipped_files += 1
                        continue
                except OSError:
                    continue
                
                # Check if we're approaching size limit to stay under 10MB
                if total_chars_added > 850000:  # Stop before reaching 10MB
                    story.append(Paragraph(
                        'Document size limit reached. Remaining files not included.', 
                        ParagraphStyle('LimitNote', parent=styles['Normal'], fontSize=6, textColor=colors.red)
                    ))
                    break
                
                # Add file header
                story.append(Paragraph(f'File: {rel_path}', file_header_style))
                
                # Read and add file content with limit per specification (max 15KB per file)
                try:
                    with open(file_path, 'r', encoding='utf-8', errors='replace') as f:
                        content = f.read(15000)  # Limit to first 15k chars per file per spec
                        
                        # Add content
                        story.append(Preformatted(content, code_style))
                        
                        # Add size warning if needed
                        if len(content) >= 15000:
                            story.append(Paragraph(
                                '(Content truncated due to size - showing first 15KB)', 
                                truncate_style
                            ))
                        
                        total_chars_added += len(content)
                        files_processed += 1
                        
                except Exception as e:
                    story.append(Paragraph(
                        f'[Could not read file: {str(e)}]', 
                        ParagraphStyle('ErrorNote', parent=styles['Normal'], fontSize=6, textColor=colors.red)
                    ))
                
                story.append(Spacer(1, 4))  # Compact spacing
                # Add page break every 10 files to prevent extremely long sections
                if files_processed % 10 == 0:
                    story.append(PageBreak())
        
        if total_chars_added > 850000:  # Stop before reaching 10MB
            break
    
    # Add summary
    story.append(PageBreak())
    story.append(Paragraph('Summary', header_style))
    story.append(Paragraph(f'Total files processed: {files_processed}', 
                          ParagraphStyle('CompactNormal', parent=styles['Normal'], fontSize=8)))
    story.append(Paragraph(f'Skipped large files: {skipped_files}', 
                          ParagraphStyle('CompactNormal', parent=styles['Normal'], fontSize=8)))
    story.append(Paragraph(f'Total characters added: {total_chars_added:,}', 
                          ParagraphStyle('CompactNormal', parent=styles['Normal'], fontSize=8)))
    story.append(Paragraph(f'Estimated PDF size: {total_chars_added/100000:.2f} MB', 
                          ParagraphStyle('CompactNormal', parent=styles['Normal'], fontSize=8)))
    
    # Build document
    doc.build(story)
    print(f"Compact Project PDF generated successfully at {output_path}")
    actual_size = os.path.getsize(output_path) / (1024*1024)
    print(f"Actual file size: {actual_size:.2f} MB")
    print(f"Files processed: {files_processed}")
    print(f"Large files skipped: {skipped_files}")
    
    if actual_size < 10.0:
        print("✓ File size is under 10MB as required")
    else:
        print("⚠ File size exceeded 10MB target")


def generate_directory_tree(startpath):
    """
    Generate a compact tree-like representation of the directory structure
    with depth and file count limits to keep the PDF compact
    """
    output = []
    # Normalize path separators
    startpath = os.path.normpath(startpath)
    for root, dirs, files in os.walk(startpath):
        # Limit depth to prevent extremely deep trees
        depth = root.replace(startpath, '').count(os.sep)
        if depth > 4:  # Limit depth to 4 levels
            continue
            
        # Get the level by counting path separators
        level = depth
        indent = ' |  ' * level  # Compact indentation
        
        # Get basename of the folder
        folder_name = os.path.basename(root)
        if folder_name and root != startpath:  # Skip empty names and root
            output.append(f'{indent} |- {folder_name}/')
        
        subindent = ' |  ' * (level + 1)
        count = 0
        for file in files:
            if count >= 10:  # Limit files listed per directory to keep compact
                output.append(f'{subindent} ... (+{len(files)-count} more files)')
                break
            output.append(f'{subindent} |- {file}')
            count += 1
    
    return '\n'.join(output[:300])  # Limit to prevent huge outputs


if __name__ == "__main__":
    generate_compact_project_pdf()