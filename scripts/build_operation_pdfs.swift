import Foundation
import AppKit

let root = URL(fileURLWithPath: FileManager.default.currentDirectoryPath)
let sourceDir = root.appendingPathComponent("docs/operations")
let outDir = root.appendingPathComponent("docs/operations/exports/pdf")
try FileManager.default.createDirectory(at: outDir, withIntermediateDirectories: true)

let files: [(String, String)] = [
    ("CLIENT_JOURNEY_AR.md", "PGI_Client_Journey_AR.pdf"),
    ("ADMIN_CONTROL_PANEL_GUIDE_AR.md", "PGI_Admin_Control_Panel_Guide_AR.pdf"),
    ("EMPLOYEE_JOURNEY_AR.md", "PGI_Employee_Journey_AR.pdf"),
    ("OUTLOOK_AND_REAL_DATA_PLAN_AR.md", "PGI_Outlook_And_Real_Data_Plan_AR.pdf"),
]

let pageRect = CGRect(x: 0, y: 0, width: 612, height: 792) // Letter points, matching Quartz default output
let margin: CGFloat = 54
let contentWidth = pageRect.width - (margin * 2)
let contentBottom: CGFloat = 58
let contentTop: CGFloat = pageRect.height - 82

let navy = NSColor(calibratedRed: 2/255, green: 6/255, blue: 23/255, alpha: 1)
let ink = NSColor(calibratedRed: 15/255, green: 23/255, blue: 42/255, alpha: 1)
let muted = NSColor(calibratedRed: 100/255, green: 116/255, blue: 139/255, alpha: 1)
let beige = NSColor(calibratedRed: 245/255, green: 242/255, blue: 235/255, alpha: 1)
let amber = NSColor(calibratedRed: 252/255, green: 211/255, blue: 77/255, alpha: 1)
let border = NSColor(calibratedRed: 226/255, green: 232/255, blue: 240/255, alpha: 1)

func paragraphStyle(alignment: NSTextAlignment = .right, lineHeight: CGFloat = 1.35) -> NSMutableParagraphStyle {
    let style = NSMutableParagraphStyle()
    style.alignment = alignment
    style.baseWritingDirection = .rightToLeft
    style.lineSpacing = 3
    style.paragraphSpacing = 5
    style.lineHeightMultiple = lineHeight
    return style
}

func attrs(size: CGFloat, weight: NSFont.Weight = .regular, color: NSColor = ink, align: NSTextAlignment = .right) -> [NSAttributedString.Key: Any] {
    let font = NSFont.systemFont(ofSize: size, weight: weight)
    return [
        .font: font,
        .foregroundColor: color,
        .paragraphStyle: paragraphStyle(alignment: align)
    ]
}

func clean(_ text: String) -> String {
    return text.replacingOccurrences(of: "**", with: "")
        .replacingOccurrences(of: "`", with: "")
        .trimmingCharacters(in: .whitespaces)
}

var activeContext: CGContext?

func drawText(_ text: String, rect: CGRect, attributes: [NSAttributedString.Key: Any]) {
    guard let cgContext = activeContext else { return }
    NSGraphicsContext.saveGraphicsState()
    NSGraphicsContext.current = NSGraphicsContext(cgContext: cgContext, flipped: false)
    let attributed = NSAttributedString(string: text, attributes: attributes)
    attributed.draw(with: rect, options: [.usesLineFragmentOrigin, .usesFontLeading])
    NSGraphicsContext.restoreGraphicsState()
}

func textHeight(_ text: String, width: CGFloat, attributes: [NSAttributedString.Key: Any]) -> CGFloat {
    let attributed = NSAttributedString(string: text, attributes: attributes)
    let box = attributed.boundingRect(with: CGSize(width: width, height: 5000), options: [.usesLineFragmentOrigin, .usesFontLeading])
    return ceil(box.height) + 4
}

func drawRounded(_ rect: CGRect, radius: CGFloat, fill: NSColor, stroke: NSColor? = nil, lineWidth: CGFloat = 1) {
    guard let cgContext = activeContext else { return }
    NSGraphicsContext.saveGraphicsState()
    NSGraphicsContext.current = NSGraphicsContext(cgContext: cgContext, flipped: false)
    let path = NSBezierPath(roundedRect: rect, xRadius: radius, yRadius: radius)
    fill.setFill()
    path.fill()
    if let stroke = stroke {
        stroke.setStroke()
        path.lineWidth = lineWidth
        path.stroke()
    }
    NSGraphicsContext.restoreGraphicsState()
}

func drawPageShell(_ page: Int) {
    drawRounded(pageRect, radius: 0, fill: beige)
    if page > 1 {
        drawRounded(CGRect(x: 34, y: 42, width: pageRect.width - 68, height: pageRect.height - 122), radius: 20, fill: .white, stroke: border)
        drawText("PG Integrated", rect: CGRect(x: margin, y: pageRect.height - 48, width: contentWidth, height: 20), attributes: attrs(size: 10, weight: .bold, color: muted, align: .left))
        drawText("Operations Documentation", rect: CGRect(x: margin, y: pageRect.height - 48, width: contentWidth, height: 20), attributes: attrs(size: 10, weight: .bold, color: muted))
    }
}

func beginPage(_ ctx: CGContext, page: inout Int) {
    ctx.beginPDFPage(nil)
    activeContext = ctx
    page += 1
    drawPageShell(page)
}

func endPage(_ ctx: CGContext, page: Int) {
    let footerAttrs = attrs(size: 8, color: muted, align: .center)
    drawText("PG Integrated - Operations Documentation - صفحة \(page)", rect: CGRect(x: margin, y: 24, width: contentWidth, height: 18), attributes: footerAttrs)
    ctx.endPDFPage()
    activeContext = nil
}

func ensureSpace(_ ctx: CGContext, y: inout CGFloat, needed: CGFloat, page: inout Int) {
    if y - needed < contentBottom {
        endPage(ctx, page: page)
        beginPage(ctx, page: &page)
        y = contentTop
    }
}

func drawBlock(_ ctx: CGContext, text: String, y: inout CGFloat, page: inout Int, attributes: [NSAttributedString.Key: Any], spacingBefore: CGFloat = 0, spacingAfter: CGFloat = 6, indent: CGFloat = 0) {
    y -= spacingBefore
    let h = textHeight(text, width: contentWidth - indent, attributes: attributes)
    ensureSpace(ctx, y: &y, needed: h + spacingAfter, page: &page)
    y -= h
    drawText(text, rect: CGRect(x: margin, y: y, width: contentWidth - indent, height: h + 8), attributes: attributes)
    y -= spacingAfter
}

func renderMarkdown(source: URL, output: URL) throws {
    let raw = try String(contentsOf: source, encoding: .utf8)
    let lines = raw.components(separatedBy: .newlines)
    let title = lines.first(where: { $0.hasPrefix("# ") })?.replacingOccurrences(of: "# ", with: "") ?? source.deletingPathExtension().lastPathComponent

    guard let consumer = CGDataConsumer(url: output as CFURL),
          let ctx = CGContext(consumer: consumer, mediaBox: nil, nil) else {
        throw NSError(domain: "PDF", code: 1)
    }

    var page = 0
    beginPage(ctx, page: &page)

    // Cover
    drawRounded(CGRect(x: 48, y: 118, width: pageRect.width - 96, height: 560), radius: 28, fill: navy)
    drawRounded(CGRect(x: pageRect.width - 210, y: 574, width: 118, height: 26), radius: 13, fill: amber)
    drawText("OPERATIONS", rect: CGRect(x: pageRect.width - 203, y: 578, width: 104, height: 18), attributes: attrs(size: 8, weight: .bold, color: navy, align: .center))
    drawText("PG Integrated", rect: CGRect(x: 82, y: 620, width: contentWidth - 56, height: 34), attributes: attrs(size: 19, weight: .bold, color: .white))
    drawText(title, rect: CGRect(x: 82, y: 348, width: contentWidth - 56, height: 190), attributes: attrs(size: 33, weight: .bold, color: .white))
    drawText("دليل تشغيلي قابل للتعديل بنفس هوية الموقع", rect: CGRect(x: 82, y: 305, width: contentWidth - 56, height: 40), attributes: attrs(size: 15, color: NSColor(calibratedRed: 203/255, green: 213/255, blue: 225/255, alpha: 1)))
    drawText("المصدر: \(source.lastPathComponent)", rect: CGRect(x: 82, y: 185, width: contentWidth - 56, height: 28), attributes: attrs(size: 10, color: NSColor(calibratedRed: 148/255, green: 163/255, blue: 184/255, alpha: 1)))
    endPage(ctx, page: page)

    beginPage(ctx, page: &page)
    var y = contentTop
    var inCode = false
    var codeLines: [String] = []

    for original in lines {
        let line = original.trimmingCharacters(in: .whitespaces)
        if line.hasPrefix("```") {
            if inCode {
                drawBlock(ctx, text: codeLines.joined(separator: "\n"), y: &y, page: &page, attributes: attrs(size: 9, color: NSColor(calibratedRed: 30/255, green: 41/255, blue: 59/255, alpha: 1), align: .left), spacingBefore: 4, spacingAfter: 8)
                codeLines = []
            }
            inCode.toggle()
            continue
        }
        if inCode {
            codeLines.append(original)
            continue
        }
        if line.isEmpty { continue }

        if line.hasPrefix("# ") {
            drawBlock(ctx, text: clean(String(line.dropFirst(2))), y: &y, page: &page, attributes: attrs(size: 23, weight: .bold), spacingAfter: 14)
        } else if line.hasPrefix("## ") {
            drawBlock(ctx, text: clean(String(line.dropFirst(3))), y: &y, page: &page, attributes: attrs(size: 17, weight: .bold, color: NSColor(calibratedRed: 30/255, green: 41/255, blue: 59/255, alpha: 1)), spacingBefore: 16, spacingAfter: 8)
        } else if line.hasPrefix("### ") {
            drawBlock(ctx, text: clean(String(line.dropFirst(4))), y: &y, page: &page, attributes: attrs(size: 13, weight: .bold, color: NSColor(calibratedRed: 51/255, green: 65/255, blue: 85/255, alpha: 1)), spacingBefore: 8, spacingAfter: 5)
        } else if line.hasPrefix("- ") {
            drawBlock(ctx, text: "• " + clean(String(line.dropFirst(2))), y: &y, page: &page, attributes: attrs(size: 11), spacingAfter: 3)
        } else if line.range(of: #"^\d+\.\s+"#, options: .regularExpression) != nil {
            drawBlock(ctx, text: clean(line), y: &y, page: &page, attributes: attrs(size: 11), spacingAfter: 3)
        } else {
            drawBlock(ctx, text: clean(line), y: &y, page: &page, attributes: attrs(size: 11), spacingAfter: 5)
        }
    }
    if !codeLines.isEmpty {
        drawBlock(ctx, text: codeLines.joined(separator: "\n"), y: &y, page: &page, attributes: attrs(size: 9, align: .left), spacingAfter: 8)
    }
    endPage(ctx, page: page)
    ctx.closePDF()
}

for (md, pdf) in files {
    let src = sourceDir.appendingPathComponent(md)
    let out = outDir.appendingPathComponent(pdf)
    try renderMarkdown(source: src, output: out)
    print(out.path)
}
