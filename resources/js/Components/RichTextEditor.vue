<template>
    <v-card variant="outlined" rounded="lg" :class="{ 'editor-error': props.errorMessages?.length }">

        <!-- Toolbar -->
        <v-toolbar density="compact" flat class="px-2">
            <!-- Bold -->
            <v-btn icon="mdi-format-bold" size="small" variant="text" :active="editor?.isActive('bold')"
                @click="editor?.chain().focus().toggleBold().run()" />

            <!-- Italic -->
            <v-btn icon="mdi-format-italic" size="small" variant="text" :active="editor?.isActive('italic')"
                @click="editor?.chain().focus().toggleItalic().run()" />

            <!-- Underline -->
            <v-btn icon="mdi-format-underline" size="small" variant="text" :active="editor?.isActive('underline')"
                @click="editor?.chain().focus().toggleUnderline().run()" />

            <!-- Strike -->
            <v-btn icon="mdi-format-strikethrough" size="small" variant="text" :active="editor?.isActive('strike')"
                @click="editor?.chain().focus().toggleStrike().run()" />

            <v-divider vertical class="mx-2" />

            <!-- Headings -->
            <v-menu>
                <template #activator="{ props }">
                    <v-btn v-bind="props" variant="text" size="small">
                        Heading
                        <v-icon end>
                            mdi-chevron-down
                        </v-icon>
                    </v-btn>
                </template>

                <v-list density="compact">
                    <v-list-item title="Paragraph" @click="
                        editor?.chain().focus().setParagraph().run()
                        " />

                    <v-list-item title="Heading 1" @click="
                        editor?.chain().focus().toggleHeading({ level: 1 }).run()
                        " />

                    <v-list-item title="Heading 2" @click="
                        editor?.chain().focus().toggleHeading({ level: 2 }).run()
                        " />

                    <v-list-item title="Heading 3" @click="
                        editor?.chain().focus().toggleHeading({ level: 3 }).run()
                        " />
                </v-list>
            </v-menu>

            <v-divider vertical class="mx-2" />

            <!-- Bullet List -->
            <v-btn icon="mdi-format-list-bulleted" size="small" variant="text" :active="editor?.isActive('bulletList')"
                @click="
                    editor?.chain().focus().toggleBulletList().run()
                    " />

            <!-- Ordered List -->
            <v-btn icon="mdi-format-list-numbered" size="small" variant="text" :active="editor?.isActive('orderedList')"
                @click="
                    editor?.chain().focus().toggleOrderedList().run()
                    " />

            <!-- Blockquote -->
            <v-btn icon="mdi-format-quote-close" size="small" variant="text" :active="editor?.isActive('blockquote')"
                @click="
                    editor?.chain().focus().toggleBlockquote().run()
                    " />

            <v-divider vertical class="mx-2" />

            <!-- Align Left -->
            <v-btn icon="mdi-format-align-left" size="small" variant="text"
                :active="editor?.isActive({ textAlign: 'left' })" @click="
                    editor?.chain().focus().setTextAlign('left').run()
                    " />

            <!-- Align Center -->
            <v-btn icon="mdi-format-align-center" size="small" variant="text"
                :active="editor?.isActive({ textAlign: 'center' })" @click="
                    editor?.chain().focus().setTextAlign('center').run()
                    " />

            <!-- Align Right -->
            <v-btn icon="mdi-format-align-right" size="small" variant="text"
                :active="editor?.isActive({ textAlign: 'right' })" @click="
                    editor?.chain().focus().setTextAlign('right').run()
                    " />

            <!-- Justify -->
            <v-btn icon="mdi-format-align-justify" size="small" variant="text"
                :active="editor?.isActive({ textAlign: 'justify' })" @click="
                    editor?.chain().focus().setTextAlign('justify').run()
                    " />

            <v-divider vertical class="mx-2" />

            <!-- Inline Code -->
            <v-btn icon="mdi-code-tags" size="small" variant="text" :active="editor?.isActive('code')" @click="
                editor?.chain().focus().toggleCode().run()
                " />

            <!-- Code Block -->
            <v-btn icon="mdi-code-braces" size="small" variant="text" :active="editor?.isActive('codeBlock')" @click="
                editor?.chain().focus().toggleCodeBlock().run()
                " />

            <!-- Horizontal Rule -->
            <v-btn icon="mdi-minus" size="small" variant="text" @click="
                editor?.chain().focus().setHorizontalRule().run()
                " />

            <v-spacer />

            <!-- Undo -->
            <v-btn icon="mdi-undo" size="small" variant="text" :disabled="!editor?.can().undo()" @click="
                editor?.chain().focus().undo().run()
                " />

            <!-- Redo -->
            <v-btn icon="mdi-redo" size="small" variant="text" :disabled="!editor?.can().redo()" @click="
                editor?.chain().focus().redo().run()
                " />
        </v-toolbar>

        <v-divider />

        <!-- Editor -->
        <div class="editor-content pa-4">
            <EditorContent :editor="editor" />
        </div>
    </v-card>
    <div v-if="props.errorMessages?.length" class="text-error text-label-medium mt-2 px-4 pb-3">
        {{
            Array.isArray(props.errorMessages)
                ? props.errorMessages[0]
                : props.errorMessages
        }}
    </div>
</template>

<script setup lang="ts">
import { onBeforeUnmount } from 'vue';

import { EditorContent, useEditor } from '@tiptap/vue-3';

import StarterKit from '@tiptap/starter-kit';

import Underline from '@tiptap/extension-underline';
import Link from '@tiptap/extension-link';
import TextAlign from '@tiptap/extension-text-align';
import { TextStyle } from '@tiptap/extension-text-style';
import Color from '@tiptap/extension-color';
import Highlight from '@tiptap/extension-highlight';
import Superscript from '@tiptap/extension-superscript';
import Subscript from '@tiptap/extension-subscript';

const props = defineProps<{
    modelValue: string;
    errorMessages?: string | string[];
}>();

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

const editor = useEditor({
    content: props.modelValue,

    extensions: [
        StarterKit,

        Underline,

        Link.configure({
            openOnClick: false,
            autolink: true,
            defaultProtocol: 'https',
        }),

        TextAlign.configure({
            types: ['heading', 'paragraph'],
        }),

        TextStyle,

        Color,

        Highlight.configure({
            multicolor: true,
        }),

        Superscript,

        Subscript,
    ],

    onUpdate: ({ editor }) => {
        emit('update:modelValue', editor.getHTML());
    },
});

onBeforeUnmount(() => {
    editor.value?.destroy();
});
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