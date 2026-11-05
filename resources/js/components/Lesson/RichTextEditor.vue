<template>
    <v-card variant="outlined" rounded="lg" :class="{ 'editor-error': error }">
        <v-toolbar density="compact" flat class="px-2">
            <template v-for="(group, gi) in groups" :key="gi">
                <v-divider v-if="gi > 0" vertical class="mx-2" />

                <v-btn
                    v-for="b in group"
                    :key="b.icon"
                    :icon="b.icon"
                    :aria-label="b.label"
                    size="small"
                    variant="text"
                    :active="b.active?.() ?? false"
                    @click="b.run()"
                />

                <v-menu v-if="gi === 0">
                    <template #activator="{ props: menu }">
                        <v-btn v-bind="menu" variant="text" size="small">
                            Heading <v-icon end>mdi-chevron-down</v-icon>
                        </v-btn>
                    </template>
                    <v-list density="compact">
                        <v-list-item
                            v-for="h in headings"
                            :key="h.title"
                            :title="h.title"
                            @click="h.run()"
                        />
                    </v-list>
                </v-menu>
            </template>

            <v-spacer />

            <v-btn
                icon="mdi-undo"
                aria-label="Undo"
                size="small"
                variant="text"
                :disabled="!editor?.can().undo()"
                @click="editor?.chain().focus().undo().run()"
            />
            <v-btn
                icon="mdi-redo"
                aria-label="Redo"
                size="small"
                variant="text"
                :disabled="!editor?.can().redo()"
                @click="editor?.chain().focus().redo().run()"
            />
        </v-toolbar>

        <v-divider />

        <div class="editor-content pa-4">
            <EditorContent :editor="editor" />
        </div>
    </v-card>

    <div v-if="error" class="text-error text-caption mt-2 px-4 pb-3">
        {{ error }}
    </div>
</template>

<script setup lang="ts">
import Color from "@tiptap/extension-color";
import Highlight from "@tiptap/extension-highlight";
import Link from "@tiptap/extension-link";
import Subscript from "@tiptap/extension-subscript";
import Superscript from "@tiptap/extension-superscript";
import TextAlign from "@tiptap/extension-text-align";
import { TextStyle } from "@tiptap/extension-text-style";
import Underline from "@tiptap/extension-underline";
import StarterKit from "@tiptap/starter-kit";
import { EditorContent, useEditor } from "@tiptap/vue-3";
import { computed, onBeforeUnmount } from "vue";

interface ToolbarButton {
    icon: string;
    label: string;
    active?: () => boolean;
    run: () => void;
}

const props = defineProps<{ errorMessages?: string | string[] }>();
const model = defineModel<string>({ required: true });

const error = computed(() =>
    Array.isArray(props.errorMessages)
        ? props.errorMessages[0]
        : props.errorMessages,
);

const editor = useEditor({
    content: model.value,
    extensions: [
        StarterKit,
        Underline,
        Link.configure({
            openOnClick: false,
            autolink: true,
            defaultProtocol: "https",
        }),
        TextAlign.configure({ types: ["heading", "paragraph"] }),
        TextStyle,
        Color,
        Highlight.configure({ multicolor: true }),
        Superscript,
        Subscript,
    ],
    onUpdate: ({ editor }) => (model.value = editor.getHTML()),
});

onBeforeUnmount(() => editor.value?.destroy());

const chain = () => editor.value?.chain().focus();
const is = (name: string | Record<string, unknown>) =>
    editor.value?.isActive(name as never) ?? false;

const marks: ToolbarButton[] = [
    {
        icon: "mdi-format-bold",
        label: "Bold",
        active: () => is("bold"),
        run: () => chain()?.toggleBold().run(),
    },
    {
        icon: "mdi-format-italic",
        label: "Italic",
        active: () => is("italic"),
        run: () => chain()?.toggleItalic().run(),
    },
    {
        icon: "mdi-format-underline",
        label: "Underline",
        active: () => is("underline"),
        run: () => chain()?.toggleUnderline().run(),
    },
    {
        icon: "mdi-format-strikethrough",
        label: "Strike",
        active: () => is("strike"),
        run: () => chain()?.toggleStrike().run(),
    },
];

const blocks: ToolbarButton[] = [
    {
        icon: "mdi-format-list-bulleted",
        label: "Bullet list",
        active: () => is("bulletList"),
        run: () => chain()?.toggleBulletList().run(),
    },
    {
        icon: "mdi-format-list-numbered",
        label: "Ordered list",
        active: () => is("orderedList"),
        run: () => chain()?.toggleOrderedList().run(),
    },
    {
        icon: "mdi-format-quote-close",
        label: "Quote",
        active: () => is("blockquote"),
        run: () => chain()?.toggleBlockquote().run(),
    },
];

const aligns: ToolbarButton[] = (
    ["left", "center", "right", "justify"] as const
).map((side) => ({
    icon: `mdi-format-align-${side}`,
    label: `Align ${side}`,
    active: () => is({ textAlign: side }),
    run: () => chain()?.setTextAlign(side).run(),
}));

const code: ToolbarButton[] = [
    {
        icon: "mdi-code-tags",
        label: "Inline code",
        active: () => is("code"),
        run: () => chain()?.toggleCode().run(),
    },
    {
        icon: "mdi-code-braces",
        label: "Code block",
        active: () => is("codeBlock"),
        run: () => chain()?.toggleCodeBlock().run(),
    },
    {
        icon: "mdi-minus",
        label: "Horizontal rule",
        run: () => chain()?.setHorizontalRule().run(),
    },
];

const groups = [marks, blocks, aligns, code];

const headings = [
    { title: "Paragraph", run: () => chain()?.setParagraph().run() },
    ...([1, 2, 3] as const).map((level) => ({
        title: `Heading ${level}`,
        run: () => chain()?.toggleHeading({ level }).run(),
    })),
];
</script>

<style scoped>
.editor-error {
    border-color: rgb(var(--v-theme-error)) !important;
}

.editor-content {
    min-height: 350px;
}

:deep(.ProseMirror) {
    min-height: 300px;
    outline: none;
}

:deep(.ProseMirror p) {
    margin-bottom: 12px;
}
:deep(.ProseMirror h1) {
    font-size: 2rem;
    line-height: 1.2;
    margin-bottom: 16px;
}
:deep(.ProseMirror h2) {
    font-size: 1.5rem;
    line-height: 1.3;
    margin-bottom: 14px;
}
:deep(.ProseMirror h3) {
    font-size: 1.25rem;
    line-height: 1.4;
    margin-bottom: 12px;
}
:deep(.ProseMirror ul),
:deep(.ProseMirror ol) {
    padding-left: 24px;
    margin-bottom: 12px;
}
:deep(.ProseMirror blockquote) {
    border-left: 3px solid currentColor;
    padding-left: 16px;
    margin: 16px 0;
}
:deep(.ProseMirror code) {
    padding: 2px 4px;
    border-radius: 4px;
}
:deep(.ProseMirror pre) {
    padding: 16px;
    border-radius: 8px;
    overflow-x: auto;
    margin: 16px 0;
}
:deep(.ProseMirror hr) {
    margin: 24px 0;
}
</style>
