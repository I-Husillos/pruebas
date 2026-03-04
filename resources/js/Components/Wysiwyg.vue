<template>
  <div class="border border-gray-300 rounded-md overflow-hidden bg-white focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-indigo-500 transition-all">
    <!-- Toolbar -->
    <div v-if="editor" class="flex flex-wrap items-center gap-1 border-b border-gray-200 bg-gray-50 p-2">
      <button 
        type="button"
        @click="editor.chain().focus().toggleBold().run()" 
        :class="{ 'bg-gray-200 text-indigo-600': editor.isActive('bold') }"
        class="p-1.5 rounded hover:bg-gray-200 text-gray-600 transition-colors"
        title="Negrita"
      >
        <component :is="BoldIcon" class="w-4 h-4" />
      </button>
      <button 
        type="button"
        @click="editor.chain().focus().toggleItalic().run()" 
        :class="{ 'bg-gray-200 text-indigo-600': editor.isActive('italic') }"
        class="p-1.5 rounded hover:bg-gray-200 text-gray-600 transition-colors"
        title="Cursiva"
      >
        <component :is="ItalicIcon" class="w-4 h-4" />
      </button>
      <button 
        type="button"
        @click="editor.chain().focus().toggleUnderline().run()" 
        :class="{ 'bg-gray-200 text-indigo-600': editor.isActive('underline') }"
        class="p-1.5 rounded hover:bg-gray-200 text-gray-600 transition-colors"
        title="Subrayado"
      >
        <component :is="UnderlineIcon" class="w-4 h-4" />
      </button>
      
      <div class="w-px h-6 bg-gray-300 mx-1"></div>
      
      <button 
        type="button"
        @click="editor.chain().focus().toggleBulletList().run()" 
        :class="{ 'bg-gray-200 text-indigo-600': editor.isActive('bulletList') }"
        class="p-1.5 rounded hover:bg-gray-200 text-gray-600 transition-colors"
        title="Lista de puntos"
      >
        <component :is="ListIcon" class="w-4 h-4" />
      </button>
      <button 
        type="button"
        @click="editor.chain().focus().toggleOrderedList().run()" 
        :class="{ 'bg-gray-200 text-indigo-600': editor.isActive('orderedList') }"
        class="p-1.5 rounded hover:bg-gray-200 text-gray-600 transition-colors"
        title="Lista numerada"
      >
        <component :is="ListOrderedIcon" class="w-4 h-4" />
      </button>
      
      <div class="w-px h-6 bg-gray-300 mx-1"></div>
      
      <button 
        type="button"
        @click="setLink" 
        :class="{ 'bg-gray-200 text-indigo-600': editor.isActive('link') }"
        class="p-1.5 rounded hover:bg-gray-200 text-gray-600 transition-colors"
        title="Enlace"
      >
        <component :is="LinkIcon" class="w-4 h-4" />
      </button>
      <button 
        type="button"
        @click="editor.chain().focus().unsetLink().run()" 
        v-if="editor.isActive('link')"
        class="p-1.5 rounded hover:bg-gray-200 text-gray-600 transition-colors"
        title="Eliminar enlace"
      >
        <component :is="UnlinkIcon" class="w-4 h-4" />
      </button>
      
      <div class="flex-grow"></div>
      
      <button 
        type="button"
        @click="editor.chain().focus().undo().run()" 
        class="p-1.5 rounded hover:bg-gray-200 text-gray-600 transition-colors"
        title="Deshacer"
      >
        <component :is="UndoIcon" class="w-4 h-4" />
      </button>
      <button 
        type="button"
        @click="editor.chain().focus().redo().run()" 
        class="p-1.5 rounded hover:bg-gray-200 text-gray-600 transition-colors"
        title="Rehacer"
      >
        <component :is="RedoIcon" class="w-4 h-4" />
      </button>
      <button 
        type="button"
        @click="toggleHtmlMode" 
        :class="{ 'bg-gray-200 text-indigo-600': isHtmlMode }"
        class="p-1.5 rounded hover:bg-gray-200 text-gray-600 transition-colors"
        title="Ver HTML"
      >
        <component :is="CodeIcon" class="w-4 h-4" />
      </button>
      <button 
        type="button"
        @click="triggerImageUpload" 
        class="p-1.5 rounded hover:bg-gray-200 text-gray-600 transition-colors"
        title="Insertar Imagen"
      >
        <component :is="ImageIcon" class="w-4 h-4" />
      </button>
      <input type="file" ref="fileInput" class="hidden" accept="image/*" @change="handleImageUpload">
    </div>
    
    <!-- Editor Content -->
    <EditorContent v-show="!isHtmlMode" :editor="editor" class="prose prose-sm max-w-none p-4 min-h-[150px] outline-none" />
    
    <!-- Raw HTML Content -->
    <textarea 
      v-if="isHtmlMode"
      v-model="rawHtml"
      @input="updateFromHtml"
      class="w-full h-[150px] p-4 font-mono text-sm bg-gray-50 border-none resize-y focus:ring-0"
    ></textarea>
  </div>
</template>

<script setup>
import { useEditor, EditorContent } from '@tiptap/vue-3';
import StarterKit from '@tiptap/starter-kit';
import Link from '@tiptap/extension-link';
import Underline from '@tiptap/extension-underline';
import Image from '@tiptap/extension-image';
import { 
  Bold as BoldIcon, 
  Italic as ItalicIcon, 
  Underline as UnderlineIcon, 
  List as ListIcon, 
  ListOrdered as ListOrderedIcon,
  Link as LinkIcon,
  Link2Off as UnlinkIcon,
  Undo as UndoIcon,
  Redo as RedoIcon,
  Code as CodeIcon,
  Image as ImageIcon
} from 'lucide-vue-next';
import { watch, ref } from 'vue';

const props = defineProps({
  modelValue: {
    type: String,
    default: '',
  },
});

const emit = defineEmits(['update:modelValue']);

const isHtmlMode = ref(false);
const rawHtml = ref('');

const toggleHtmlMode = () => {
  if (isHtmlMode.value) {
    // Switch back to WYSIWYG
    editor.value.commands.setContent(rawHtml.value);
  } else {
    // Switch to HTML View
    rawHtml.value = editor.value.getHTML();
  }
  isHtmlMode.value = !isHtmlMode.value;
};

const updateFromHtml = () => {
  emit('update:modelValue', rawHtml.value);
};

const editor = useEditor({
  content: props.modelValue,
  extensions: [
    StarterKit,
    Underline,
    Link.configure({
      openOnClick: false,
    }),
    Image,
  ],
  onUpdate: ({ editor }) => {
    // Only update if NOT in HTML mode (though HTML mode hides editor, this is safe)
    if (!isHtmlMode.value) {
      emit('update:modelValue', editor.getHTML());
    }
  },
  editorProps: {
    attributes: {
      class: 'focus:outline-none min-h-[150px]',
    },
  },
});

watch(() => props.modelValue, (value) => {
  const isSame = editor.value.getHTML() === value;
  if (!isSame) {
    editor.value.commands.setContent(value, false);
  }
});

const setLink = () => {
  const previousUrl = editor.value.getAttributes('link').href;
  const url = window.prompt('URL:', previousUrl);

  if (url === null) {
    return;
  }

  if (url === '') {
    editor.value.chain().focus().extendMarkRange('link').unsetLink().run();
    return;
  }

  editor.value.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
  editor.value.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
};

const fileInput = ref(null);

const triggerImageUpload = () => {
    fileInput.value.click();
};

const handleImageUpload = async (event) => {
    const file = event.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('file', file);
    formData.append('path', 'editor-uploads'); // Organization subfolder

    try {
        // We assume axios is available globally or we fetch
        // In Inertia apps, axios is usually on window.axios
        const response = await window.axios.post(route('admin.media.store'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        if (response.data.url) {
            editor.value.chain().focus().setImage({ src: response.data.url }).run();
        }
    } catch (error) {
        console.error('Upload failed', error);
        alert('Error al subir la imagen');
    } finally {
        // Reset input
        event.target.value = '';
    }
};
</script>

<style>
/* Basic Tiptap styles */
.ProseMirror {
    outline: none;
}

.ProseMirror p.is-editor-empty:first-child::before {
    content: attr(data-placeholder);
    float: left;
    color: #adb5bd;
    pointer-events: none;
    height: 0;
}

/* Fix for lists if prose is not behaving */
.ProseMirror ul {
    list-style-type: disc;
    padding-left: 1.5em;
    margin: 1em 0;
}

.ProseMirror ol {
    list-style-type: decimal;
    padding-left: 1.5em;
    margin: 1em 0;
}

.ProseMirror p {
    margin-bottom: 0.5em;
}
</style>
