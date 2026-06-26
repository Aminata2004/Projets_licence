import os
import re

directory = '/opt/lampp/htdocs/Gestion_compagnie_mcv/app/views/admin'

new_style = """      <style>
        /* BusLink Premium Configuration Theme */
        .config-card {
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border-radius: 20px;
            background: #ffffff;
            overflow: hidden;
        }
        .config-card .card-header {
            background: linear-gradient(135deg, #0f172a, #1e293b) !important;
            color: white !important;
            border-radius: 20px 20px 0 0 !important;
            border-bottom: none;
            padding: 1.2rem 1.5rem;
        }
        .config-card .card-title, .config-card .card-header h5 {
            color: white !important;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
        }
        .vertical-tabs-custom .nav-link {
            border-radius: 12px;
            margin-bottom: 8px;
            color: #475569;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 12px 15px;
            border: 1px solid transparent;
            display: flex;
            align-items: center;
        }
        .vertical-tabs-custom .nav-link:hover {
            background: rgba(245, 158, 11, 0.08);
            color: #ea580c;
            transform: translateX(4px);
        }
        .vertical-tabs-custom .nav-link.active {
            background: linear-gradient(135deg, #f59e0b, #ea580c) !important;
            color: white !important;
            border: none !important;
            box-shadow: 0 8px 20px -5px rgba(234, 88, 12, 0.4);
        }
        .vertical-tabs-custom .nav-link i {
            font-size: 1.2rem;
            margin-right: 10px;
        }
        .btn-buslink, .btn-primary {
            background: linear-gradient(135deg, #f59e0b, #ea580c) !important;
            color: white !important;
            border: none !important;
            border-radius: 12px;
            padding: 0.6rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 8px 20px -5px rgba(234, 88, 12, 0.4);
        }
        .btn-buslink:hover, .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px -5px rgba(234, 88, 12, 0.5);
        }
        /* Table Styling */
        .table-custom-header thead th {
            background: rgba(15, 23, 42, 0.04) !important;
            color: #0f172a !important;
            font-weight: 600;
            border-bottom: 2px solid #e2e8f0;
            padding: 1rem;
        }
        .table-hover-effect tbody tr {
            transition: all 0.2s;
        }
        .table-hover-effect tbody tr:hover {
            background: rgba(245, 158, 11, 0.04) !important;
            transform: scale(1.002);
            box-shadow: 0 4px 10px rgba(0,0,0,0.03);
            border-radius: 8px;
        }
      </style>"""

pattern = re.compile(r'<style>\s*/\* BusLink Configuration Theme \*/.*?</style>', re.DOTALL)

for root, dirs, files in os.walk(directory):
    for file in files:
        if file.endswith('.php'):
            filepath = os.path.join(root, file)
            with open(filepath, 'r') as f:
                content = f.read()
            
            if '/* BusLink Configuration Theme */' in content:
                new_content = pattern.sub(new_style, content)
                with open(filepath, 'w') as f:
                    f.write(new_content)
                print(f"Updated {file}")

