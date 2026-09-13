#!/usr/bin/env python3
"""Rebuild public/certificates/sample-clearance.pdf from source + settings JSON."""

from __future__ import annotations

import argparse
import json
import shutil
from pathlib import Path

import arabic_reshaper
import fitz
import qrcode
from bidi.algorithm import get_display
from PIL import Image

ROOT = Path(__file__).resolve().parents[1]
SRC = ROOT / "storage/app/sample-ref/source.pdf"
OUT = ROOT / "public/certificates/sample-clearance.pdf"
QR_PATH = ROOT / "public/certificates/qr.png"
LOGO_PATH = ROOT / "public/img/logo.png"
FONT_CANDIDATES = [
    "/System/Library/Fonts/Supplemental/Times New Roman.ttf",  # macOS
    "/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf",  # Ubuntu
    "/usr/share/fonts/truetype/liberation/LiberationSerif-Regular.ttf",
    "/usr/share/fonts/truetype/freefont/FreeSerif.ttf",
    "/usr/share/fonts/truetype/noto/NotoNaskhArabic-Regular.ttf",
    "/usr/share/fonts/truetype/noto/NotoSansArabic-Regular.ttf",
]


def resolve_font() -> str:
    for path in FONT_CANDIDATES:
        if Path(path).is_file():
            return path
    raise FileNotFoundError(
        "No suitable TTF font found. Install one of: fonts-dejavu-core, fonts-liberation, fonts-noto-core"
    )


FS = 9.12
EXTRACT_SHIFT = 12.0
FONT = resolve_font()


def ar(text: str) -> str:
    return get_display(arabic_reshaper.reshape(text))


def load_settings(path: Path) -> dict:
    data = json.loads(path.read_text(encoding="utf-8"))
    return {k: str(v) for k, v in data.items()}


def build_qr(url: str) -> None:
    qr = qrcode.QRCode(
        version=8,
        error_correction=qrcode.constants.ERROR_CORRECT_H,
        box_size=20,
        border=4,
    )
    qr.add_data(url)
    qr.make(fit=False)
    img = qr.make_image(fill_color="black", back_color="white").convert("RGB")
    if img.size[0] != 1140:
        canvas = Image.new("RGB", (1140, 1140), "white")
        off = (1140 - img.size[0]) // 2
        canvas.paste(img, (off, off))
        img = canvas
    img.save(QR_PATH)


def main() -> None:
    parser = argparse.ArgumentParser()
    parser.add_argument("--settings", required=True)
    args = parser.parse_args()
    s = load_settings(Path(args.settings))

    build_qr(s.get("qr_url", "https://inform.menpowerr-eg.co/"))

    shutil.copy2(SRC, OUT)
    doc = fitz.open(OUT)
    page = doc[0]
    font = fitz.Font(fontfile=FONT)

    BOX_EXTRACT = fitz.Rect(127.96, 255.65, 165.01, 265.9)
    BOX_AMOUNT_ORIG = fitz.Rect(459.7, 367.36, 483.07, 377.62)
    BOX_TAFQEET_ORIG = fitz.Rect(166.72, 367.36, 377.05, 377.62)
    DESC_RIGHT = 387.84
    WORKS_RIGHT = 430.2
    SRC_TAF_RIGHT = 377.05

    def all_lines():
        rows = []
        for b in page.get_text("rawdict")["blocks"]:
            if b.get("type") != 0:
                continue
            for line in b.get("lines", []):
                chars = []
                for sp in line.get("spans", []):
                    chars.extend(sp.get("chars", []))
                if chars:
                    rows.append(("".join(c["c"] for c in chars), chars))
        return rows

    def find_line(pred):
        for text, chars in all_lines():
            if pred(text):
                return text, chars
        raise ValueError("line not found")

    def sub_bbox(text, chars, needle):
        i = text.find(needle)
        if i < 0:
            raise ValueError(needle)
        r = fitz.Rect(chars[i]["bbox"])
        for j in range(i + 1, i + len(needle)):
            r |= fitz.Rect(chars[j]["bbox"])
        return r, chars[i]["origin"][1], i

    redacts, draws, boxes = [], [], []

    def cover(r, px=0.5, py=0.35):
        redacts.append(fitz.Rect(r.x0 - px, r.y0 - py, r.x1 + px, r.y1 + py))

    def put_digits(value, old_r, y):
        draws.append((value, old_r.x0, y, FS))

    def put_digits_box(value, box, y):
        w = font.text_length(value, fontsize=FS)
        pad = 1.2
        b = fitz.Rect(box.x1 - (w + 2 * pad), box.y0, box.x1, box.y1)
        draws.append((value, b.x0 + pad, y, FS))
        boxes.append(b)

    def put_ar_right(value, right_x, y, left_limit=None, fs=FS):
        visual = ar(value)
        w = font.text_length(visual, fontsize=fs)
        while left_limit is not None and fs > 6.0 and right_x - w < left_limit:
            fs -= 0.1
            w = font.text_length(visual, fontsize=fs)
        draws.append((visual, right_x - w, y, fs))

    # contractor
    text, chars = find_line(lambda t: "زهر" in t and "مواد" in t)
    r = fitz.Rect(chars[0]["bbox"])
    for c in chars[1:]:
        r |= fitz.Rect(c["bbox"])
    cover(fitz.Rect(r.x0 - 2, r.y0, min(448, r.x1 + 55), r.y1), px=1, py=0.6)
    put_ar_right(s["contractor_name"], r.x1, chars[0]["origin"][1], left_limit=248)

    # operation
    cover(fitz.Rect(40, 216.5, DESC_RIGHT + 2, 230.0), px=0.5, py=0.5)
    put_ar_right(s["operation_description"], DESC_RIGHT, 226.29, left_limit=42)

    # contract / works
    text, chars = find_line(lambda t: "1658/2021/2022" in t)
    r_mid, _, idx = sub_bbox(text, chars, "1658/2021/2022")
    for c in chars[idx:]:
        r_mid |= fitz.Rect(c["bbox"])
    cover(r_mid, px=1.0, py=0.5)

    works_ar = ar(s.get("works_label", "اعمال/"))
    dig = s.get("works_code", "15740")
    num = s.get("contract_number", "261008004796")
    w_ar = font.text_length(works_ar, fontsize=FS)
    w_d = font.text_length(dig, fontsize=FS)
    works_x_ar = WORKS_RIGHT - w_ar
    works_x_dig = WORKS_RIGHT - w_ar - 3.0 - w_d
    draws.append((works_ar, works_x_ar, 245.1, FS))
    draws.append((dig, works_x_dig, 245.1, FS))

    nw = font.text_length(num, fontsize=FS)
    num_x = WORKS_RIGHT - nw
    cover(fitz.Rect(num_x - 1, 247.5, WORKS_RIGHT + 1, 261.5), px=0.2, py=0.2)

    # extract
    text, chars = find_line(lambda t: "1489218" in t and "10391089" in t)
    r1, y1, _ = sub_bbox(text, chars, "1489218")
    r2, y2, _ = sub_bbox(text, chars, "10391089")
    extract_num_x = r1.x0
    extract_num_y = y1
    cover(r1)
    cover(fitz.Rect(BOX_EXTRACT.x0 + 0.4, BOX_EXTRACT.y0 + 0.25, BOX_EXTRACT.x1 - 0.4, BOX_EXTRACT.y1 - 0.25))
    put_digits(s["extract_code"], r1, y1)
    put_digits_box(s["extract_value"], BOX_EXTRACT, y2)

    for text, chars in all_lines():
        if text.strip() == "ختامي":
            r = fitz.Rect(chars[0]["bbox"])
            for c in chars[1:]:
                r |= fitz.Rect(c["bbox"])
            cover(r, px=2, py=0.5)
            put_ar_right(s["extract_type"], r.x1 + 10, chars[0]["origin"][1], left_limit=430)
            break

    text, chars = find_line(lambda t: "2022-03-20" in t)
    r1, y1, _ = sub_bbox(text, chars, "2022-03-20")
    r2, y2, _ = sub_bbox(text, chars, "2023-03-17")
    cover(r1)
    cover(r2)
    put_digits(s["extract_start_date"], r1, y1)
    put_digits(s["extract_end_date"], r2, y2)

    text, chars = find_line(lambda t: "20251231074798" in t)
    r, y, _ = sub_bbox(text, chars, "20251231074798")
    cover(r)
    put_digits(s["receipt_no"], r, y)

    text, chars = find_line(lambda t: "35685" in t and "مبلغ" in t)
    r_num, y_num, _ = sub_bbox(text, chars, "35685")
    idx = text.find("فقط")
    y_taf = chars[idx]["origin"][1]
    cover(fitz.Rect(166.0, 366.5, 483.5, 378.0), px=0.5, py=0.4)

    amount = s["amount"]
    aw = font.text_length(amount, fontsize=FS)
    apad = 1.2
    amount_box = fitz.Rect(BOX_AMOUNT_ORIG.x1 - (aw + 2 * apad), BOX_AMOUNT_ORIG.y0, BOX_AMOUNT_ORIG.x1, BOX_AMOUNT_ORIG.y1)
    draws.append((amount, amount_box.x0 + apad, y_num, FS))
    boxes.append(amount_box)

    taf = ar(s["tafqeet"])
    twlen = font.text_length(taf, fontsize=FS)
    tpad = 1.5
    taf_box = fitz.Rect(SRC_TAF_RIGHT - (twlen + 2 * tpad), BOX_TAFQEET_ORIG.y0, SRC_TAF_RIGHT, BOX_TAFQEET_ORIG.y1)
    draws.append((taf, taf_box.x1 - tpad - twlen, y_taf, FS))
    boxes.append(taf_box)

    text, chars = find_line(lambda t: "كود العملية" in t and "335844" in t)
    for old, new in [
        ("335844", s["ministry_code"]),
        ("1489218", s["extract_code"]),
        ("a18a7f495c", s["password"]),
    ]:
        r, y, _ = sub_bbox(text, chars, old)
        cover(r)
        put_digits(new, r, y)

    text, chars = find_line(lambda t: "2026-01-04" in t and "اعتماد" in t)
    r, y, _ = sub_bbox(text, chars, "2026-01-04")
    cover(r)
    put_digits(s["approval_date"], r, y)

    for rr in redacts:
        page.add_redact_annot(rr, fill=(1, 1, 1))
    page.apply_redactions(images=0, graphics=0, text=1)
    for rr in redacts:
        page.draw_rect(rr, color=(1, 1, 1), fill=(1, 1, 1), overlay=True)

    shape = page.new_shape()
    for br in boxes:
        shape.draw_rect(br)
    shape.finish(color=(0, 0, 0), width=0.57, fill=None)
    shape.commit()

    tw = fitz.TextWriter(page.rect, color=(0, 0, 0))
    for text, x, y, fs in draws:
        tw.append(fitz.Point(x, y), text, font=font, fontsize=fs)
    tw.write_text(page)

    mid = ROOT / "public/certificates/_mid.pdf"
    doc.save(mid)
    doc.close()

    doc = fitz.open(mid)
    page = doc[0]
    band = fitz.Rect(35, 250, 580, 311.0)
    pix = page.get_pixmap(matrix=fitz.Matrix(3, 3), clip=band, alpha=False)
    page.add_redact_annot(band, fill=(1, 1, 1))
    page.apply_redactions(images=0, graphics=0, text=1)
    page.draw_rect(band, color=(1, 1, 1), fill=(1, 1, 1), overlay=True)
    page.insert_image(
        fitz.Rect(band.x0, band.y0 + EXTRACT_SHIFT, band.x1, band.y1 + EXTRACT_SHIFT),
        pixmap=pix,
    )

    page.draw_rect(fitz.Rect(500, 247, 563.5, 265.5), color=(1, 1, 1), fill=(1, 1, 1), overlay=True)
    page.draw_rect(fitz.Rect(num_x - 1.5, 247, WORKS_RIGHT + 1.5, 265.5), color=(1, 1, 1), fill=(1, 1, 1), overlay=True)

    tw2 = fitz.TextWriter(page.rect, color=(0, 0, 0))
    tw2.append(fitz.Point(num_x, 258.0), num, font=font, fontsize=FS)

    ex_y = extract_num_y + EXTRACT_SHIFT
    ex_w = font.text_length(s["extract_code"], fontsize=FS)
    page.draw_rect(
        fitz.Rect(extract_num_x - 0.5, ex_y - 9.0, extract_num_x + ex_w + 0.5, ex_y + 2.5),
        color=(1, 1, 1),
        fill=(1, 1, 1),
        overlay=True,
    )
    tw2.append(fitz.Point(extract_num_x, ex_y), s["extract_code"], font=font, fontsize=FS)
    tw2.write_text(page)

    # logo right, QR left
    logo_rect = fitz.Rect(262.2, 42.75, 333.45, 114.0)
    qr_rect = fitz.Rect(76.95, 42.75, 148.2, 114.0)
    page.draw_rect(logo_rect, color=(1, 1, 1), fill=(1, 1, 1), overlay=True)
    page.draw_rect(qr_rect, color=(1, 1, 1), fill=(1, 1, 1), overlay=True)
    if LOGO_PATH.exists():
        page.insert_image(logo_rect, filename=str(LOGO_PATH), keep_proportion=True)
    page.insert_image(qr_rect, filename=str(QR_PATH), keep_proportion=True)

    # footer url
    wipe = fitz.Rect(420.0, 476.0, 575.0, 495.0)
    for _ in range(2):
        page.draw_rect(wipe, color=(1, 1, 1), fill=(1, 1, 1), overlay=True)
    footer = s.get("footer_url", "https://inform.manpower.gov.eg/")
    fw = font.text_length(footer, fontsize=FS)
    page.insert_text(
        fitz.Point(562.59 - fw, 488.2),
        footer,
        fontfile=FONT,
        fontsize=FS,
        color=(0, 0, 0),
        overlay=True,
    )

    tmp = OUT.with_suffix(".new.pdf")
    doc.save(tmp, garbage=4, deflate=True)
    doc.close()
    shutil.move(tmp, OUT)
    mid.unlink(missing_ok=True)
    print("OK", OUT)


if __name__ == "__main__":
    main()
