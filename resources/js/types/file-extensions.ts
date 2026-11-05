export const FileExtensions = {
    PDF: "pdf",
    DOC: "doc",
    DOCX: "docx",
    XLS: "xls",
    XLSX: "xlsx",
    PPTX: "pptx",
    TXT: "txt",
    JPG: "jpg",
    PNG: "png",
    GIF: "gif",
} as const;

export type FileExtension =
    (typeof FileExtensions)[keyof typeof FileExtensions];
