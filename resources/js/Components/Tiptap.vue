<template>
    <div class="toolbar flex">
        <el-button-group>
            <el-button size="small" @click="tools.addYt()">
              <Icon icon="mdi:youtube" />
            </el-button>
        </el-button-group>
    </div>
    <bubble-menu v-if="editor" :editor="editor" class="p-2 bg-slate-50 rounded-tl-lg rounded-br-lg rounded-tr-lg shadow-lg">
      <el-button-group class="bubble-menu">
        <el-button size="small" @click="editor.chain().focus().toggleBold().run()" :class="{ 'is-active': editor.isActive('bold') }">
          <Icon icon="mdi:format-bold" />
        </el-button>
        <el-button size="small" @click="editor.chain().focus().toggleItalic().run()" :class="{ 'is-active': editor.isActive('italic') }">
          <Icon icon="mdi:format-italic" />
        </el-button>
        <el-button size="small" @click="editor.chain().focus().toggleStrike().run()" :class="{ 'is-active': editor.isActive('strike') }">
          <Icon icon="mdi:format-strikethrough" />
        </el-button>
      <!-- </el-button-group> -->
      <!-- <el-button-group> -->
        <el-button size="small" @click="editor.chain().focus().setTextAlign('left').run()" :class="{ 'is-active': editor.isActive('bold') }">
          <Icon icon="mdi:format-align-left" />
        </el-button>
        <el-button size="small" @click="editor.chain().focus().setTextAlign('center').run()" :class="{ 'is-active': editor.isActive('bold') }">
          <Icon icon="mdi:format-align-center" />
        </el-button>
        <el-button size="small" @click="editor.chain().focus().setTextAlign('right').run()" :class="{ 'is-active': editor.isActive('bold') }">
          <Icon icon="mdi:format-align-right" />
        </el-button>
        <el-button size="small" @click="editor.chain().focus().setTextAlign('justify').run()" :class="{ 'is-active': editor.isActive('bold') }">
          <Icon icon="mdi:format-align-justify" />
        </el-button>
      </el-button-group>
    </bubble-menu>
    <editor-content :editor="editor" :extensions="extensions" />
  </template>
  
  <script setup>
  import { ref, watch, onMounted, onBeforeUnmount, reactive } from 'vue'
  import StarterKit from '@tiptap/starter-kit'
  import { Editor, EditorContent, BubbleMenu } from '@tiptap/vue-3'
  import Youtube from '@tiptap/extension-youtube';
  import TextAlign from '@tiptap/extension-text-align'
  import { Icon } from '@iconify/vue';
  
  const props = defineProps({
    modelValue: {
      type: String,
      default: ''
    }
  })
  
  const emit = defineEmits(['update:modelValue'])
  
  const editor = ref(null)

  const extensions = [
    BubbleMenu,
    TextAlign,
    StarterKit.configure({
        heading: {
            levels: [1,2,3],
            
        },
    }),
    Youtube,
  ]
  
  watch(() => props.modelValue, (value) => {
    // HTML
    const isSame = editor.value.getHTML() === value
  
    // JSON
    // const isSame = JSON.stringify(editor.value.getJSON()) === JSON.stringify(value)
  
    if (isSame) {
      return
    }
  
    editor.value.commands.setContent(value, false)
  })

  const tools = reactive({
    addYt: () => {
        const url = prompt('Masukkan Link Youtube')

        if (url) editor.value.commands.setYoutubeVideo({src:url})
    }
  })
  
  onMounted(() => {
    editor.value = new Editor({
      extensions,
      content: props.modelValue,
      onUpdate: () => {
        // HTML
        emit('update:modelValue', editor.value.getHTML())
  
        // JSON
        // emit('update:modelValue', editor.value.getJSON())
      }
    })
  })
  
  onBeforeUnmount(() => {
    editor.value.destroy()
  })
  </script>
  
  <style lang="scss">
    /* Basic editor styles */
    .tiptap {
      :first-child {
        margin-top: 0;
      }
  
      /* List styles */
      ul,
      ol {
        padding: 0 1rem;
        margin: 1.25rem 1rem 1.25rem 0.4rem;
  
        li p {
          margin-top: 0.25em;
          margin-bottom: 0.25em;
        }
      }
  
      /* Heading styles */
      h1,
      h2,
      h3,
      h4,
      h5,
      h6 {
        line-height: 1.1;
        margin-top: 2.5rem;
        text-wrap: pretty;
      }
  
      h1,
      h2 {
        margin-top: 3.5rem;
        margin-bottom: 1.5rem;
      }
  
      h1 {
        font-size: 1.4rem;
      }
  
      h2 {
        font-size: 1.2rem;
      }
  
      h3 {
        font-size: 1.1rem;
      }
  
      h4,
      h5,
      h6 {
        font-size: 1rem;
      }
  
      /* Code and preformatted text styles */
      code {
        background-color: var(--purple-light);
        border-radius: 0.4rem;
        color: var(--black);
        font-size: 0.85rem;
        padding: 0.25em 0.3em;
      }
  
      pre {
        background: var(--black);
        border-radius: 0.5rem;
        color: var(--white);
        font-family: 'JetBrainsMono', monospace;
        margin: 1.5rem 0;
        padding: 0.75rem 1rem;
  
        code {
          background: none;
          color: inherit;
          font-size: 0.8rem;
          padding: 0;
        }
      }
  
      blockquote {
        border-left: 3px solid var(--gray-3);
        margin: 1.5rem 0;
        padding-left: 1rem;
      }
  
      hr {
        border: none;
        border-top: 1px solid var(--gray-2);
        margin: 2rem 0;
      }
    }
  </style>