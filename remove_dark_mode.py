import os
import re

directory = r"d:\Data\IT\Project\jurnal-accounting\resources\views"

def remove_dark_classes(filepath):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()

    # Regex to find 'dark:' followed by allowed tailwind characters
    # Examples: dark:bg-gray-900, dark:text-white, dark:hover:bg-red-900/20
    # It stops at space, quote, or other boundary
    new_content = re.sub(r'dark:[A-Za-z0-9\-\/\[\]\#]+', '', content)
    # clean up multiple, trailing, or leading spaces within class attributes
    # just basic cleanup
    new_content = new_content.replace('  ', ' ').replace('  ', ' ')
    
    # Write back if changed
    if new_content != content:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(new_content)
        print(f"Updated {filepath}")

for root, _, files in os.walk(directory):
    for file in files:
        if file.endswith('.blade.php'):
            remove_dark_classes(os.path.join(root, file))
