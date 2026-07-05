from __future__ import annotations

import html
import re
import subprocess
from pathlib import Path


ROOT = Path(__file__).resolve().parents[1]
SOURCE_DIR = ROOT / "docs" / "operations"
OUT_DIR = ROOT / "docs" / "operations" / "exports"
HTML_DIR = OUT_DIR / "html"
PDF_DIR = OUT_DIR / "pdf"

FILES = {
    "CLIENT_JOURNEY_AR.md": "PGI_Client_Journey_AR.pdf",
    "ADMIN_CONTROL_PANEL_GUIDE_AR.md": "PGI_Admin_Control_Panel_Guide_AR.pdf",
    "EMPLOYEE_JOURNEY_AR.md": "PGI_Employee_Journey_AR.pdf",
    "OUTLOOK_AND_REAL_DATA_PLAN_AR.md": "PGI_Outlook_And_Real_Data_Plan_AR.pdf",
}

CHROME = Path("/Applications/Google Chrome.app/Contents/MacOS/Google Chrome")


def inline(text: str) -> str:
    text = html.escape(text.strip())
    text = re.sub(r"`([^`]+)`", r"<code>\1</code>", text)
    text = text.replace("**", "")
    return text


def md_to_html(md_path: Path) -> str:
    lines = md_path.read_text(encoding="utf-8").splitlines()
    title = next((line.lstrip("# ").strip() for line in lines if line.startswith("# ")), md_path.stem)
    body: list[str] = []
    in_code = False
    code_lines: list[str] = []
    list_open = False
    ordered_open = False

    def close_lists() -> None:
        nonlocal list_open, ordered_open
        if list_open:
            body.append("</ul>")
            list_open = False
        if ordered_open:
            body.append("</ol>")
            ordered_open = False

    for raw in lines:
        line = raw.rstrip()
        if line.startswith("```"):
            if in_code:
                body.append(f"<pre>{html.escape(chr(10).join(code_lines))}</pre>")
                code_lines = []
            in_code = not in_code
            continue
        if in_code:
            code_lines.append(line)
            continue
        if not line.strip():
            close_lists()
            continue

        if line.startswith("# "):
            close_lists()
            body.append(f"<h1>{inline(line[2:])}</h1>")
        elif line.startswith("## "):
            close_lists()
            body.append(f"<h2>{inline(line[3:])}</h2>")
        elif line.startswith("### "):
            close_lists()
            body.append(f"<h3>{inline(line[4:])}</h3>")
        elif re.match(r"^\\d+\\.\\s+", line):
            if not ordered_open:
                close_lists()
                body.append("<ol>")
                ordered_open = True
            body.append(f"<li>{inline(re.sub(r'^\\d+\\.\\s+', '', line))}</li>")
        elif line.startswith("- "):
            if not list_open:
                close_lists()
                body.append("<ul>")
                list_open = True
            body.append(f"<li>{inline(line[2:])}</li>")
        else:
            close_lists()
            body.append(f"<p>{inline(line)}</p>")

    close_lists()
    if code_lines:
        body.append(f"<pre>{html.escape(chr(10).join(code_lines))}</pre>")

    return f"""<!doctype html>
<html lang="ar" dir="rtl">
<head>
<meta charset="utf-8">
<title>{html.escape(title)}</title>
<style>
@page {{ size: A4; margin: 18mm 16mm; }}
* {{ box-sizing: border-box; }}
body {{
  font-family: Arial, "Geeza Pro", sans-serif;
  color: #0f172a;
  direction: rtl;
  line-height: 1.65;
  font-size: 14px;
}}
.cover {{
  min-height: 92vh;
  display: flex;
  flex-direction: column;
  justify-content: center;
  page-break-after: always;
}}
.brand {{ font-weight: 800; font-size: 22px; margin-bottom: 42px; }}
.cover h1 {{ font-size: 34px; line-height: 1.25; margin: 0 0 12px; color: #0f172a; }}
.subtitle {{ color: #64748b; font-size: 18px; }}
.meta {{ color: #64748b; margin-top: 24px; font-size: 13px; }}
h1 {{ font-size: 26px; margin: 0 0 18px; page-break-after: avoid; color: #0f172a; }}
h2 {{ font-size: 20px; margin: 26px 0 8px; page-break-after: avoid; color: #1e293b; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px; }}
h3 {{ font-size: 16px; margin: 18px 0 6px; page-break-after: avoid; color: #334155; }}
p {{ margin: 0 0 9px; }}
ul, ol {{ margin: 4px 0 12px; padding-right: 25px; }}
li {{ margin: 3px 0; }}
code {{ direction: ltr; unicode-bidi: embed; background: #f1f5f9; padding: 1px 4px; border-radius: 4px; font-family: "Courier New", monospace; }}
pre {{ direction: ltr; text-align: left; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px; white-space: pre-wrap; font-family: "Courier New", monospace; font-size: 12px; line-height: 1.45; }}
.footer {{ position: fixed; bottom: 8mm; left: 16mm; right: 16mm; text-align: center; color: #94a3b8; font-size: 10px; }}
</style>
</head>
<body>
<section class="cover">
  <div class="brand">PG Integrated</div>
  <h1>{html.escape(title)}</h1>
  <div class="subtitle">دليل تشغيلي قابل للتعديل - نسخة PDF</div>
  <div class="meta">المصدر: {html.escape(md_path.name)}</div>
</section>
<main>
{chr(10).join(body)}
</main>
<div class="footer">PG Integrated - Operations Documentation</div>
</body>
</html>"""


def main() -> None:
    HTML_DIR.mkdir(parents=True, exist_ok=True)
    PDF_DIR.mkdir(parents=True, exist_ok=True)
    for md_name, pdf_name in FILES.items():
        html_path = HTML_DIR / pdf_name.replace(".pdf", ".html")
        pdf_path = PDF_DIR / pdf_name
        html_path.write_text(md_to_html(SOURCE_DIR / md_name), encoding="utf-8")
        subprocess.run([
            str(CHROME),
            "--headless",
            "--disable-gpu",
            "--no-sandbox",
            f"--print-to-pdf={pdf_path}",
            f"file://{html_path}",
        ], check=True)
        print(pdf_path)


if __name__ == "__main__":
    main()
