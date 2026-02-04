<script setup>
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'

const props = defineProps(['modelValue'])
const emit = defineEmits(['update:modelValue'])

const editor = useEditor({
  // Contenido inicial
  content: props.modelValue,
  // StarterKit incluye: negrita, itálica, listas, párrafos, etc.
  extensions: [StarterKit],
  // Clases de Tailwind para que el editor se vea bien
  editorProps: {
    attributes: {
      class: 'prose prose-sm focus:outline-none p-4 min-h-[120px] max-w-none',
    },
  },
  // Cuando el usuario escribe, enviamos el HTML a la base de datos
  onUpdate: ({ editor }) => {
    emit('update:modelValue', editor.getHTML())
  },
})
</script>


<template>
  <div class="border rounded-lg overflow-hidden shadow-sm mt-2">
    <div v-if="editor" class="flex gap-2 p-2 bg-gray-100 dark:bg-zinc-900 border-b">
      <button 
        @click="editor.chain().focus().toggleBold().run()"
        :class="{ 'bg-blue-500 text-white': editor.isActive('bold') }"
        class="px-2 py-1 text-sm border border-black dark:border-zinc-500 rounded hover:bg-gray-200 hover:dark:bg-zinc-700 transition-colors"
      >
        B
      </button>
      
      <button 
        @click="editor.chain().focus().toggleItalic().run()"
        :class="{ 'bg-blue-500 text-white': editor.isActive('italic') }"
        class="px-2 py-1 text-sm border border-black dark:border-zinc-500 rounded hover:bg-gray-200 hover:dark:bg-zinc-700 transition-colors"
      >
        I
      </button>

      <button 
        @click="editor.chain().focus().setParagraph().run()"
        class="px-2 py-1 text-sm border border-black dark:border-zinc-500 rounded hover:bg-gray-200 hover:dark:bg-zinc-700 transition-colors"
      >
        Párrafo
      </button>
    </div>

    <editor-content :editor="editor" />
  </div>
</template>

<style scoped>
/* Estilo básico para que el cursor se vea bien */
:deep(.tiptap) {
  min-height: 120px;
  outline: none;
}
</style>