<script setup lang="ts">
import { ref, watch } from 'vue'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'

const props = defineProps<{
    modelValue: number | undefined | null
    id?:string
    placeholder?: string
}>()

const emit = defineEmits(['update:modelValue'])

// Estado interno para lo que el usuario ve (string con comas o puntos)
const displayValue = ref('')

// Función para formatear (Tu lógica de toLocaleString)
const formatNumber = (val: number | undefined | null) => {
  if (val === undefined || val === null) return ''
  return val.toLocaleString('en-US', {
    minimumFractionDigits: 0,
    maximumFractionDigits: 2,
  })
}

// Sincronizar cuando el modelValue cambia externamente (ej: al cargar datos)
watch(() => props.modelValue, (newVal) => {
  const formatted = formatNumber(newVal)
  if (formatted !== displayValue.value) {
    displayValue.value = formatted
  }
}, { immediate: true })

const onInput = (e: Event) => {
  const target = e.target as HTMLInputElement
  let val = target.value

  val = val.replace(/[^0-9.]/g, '')
  const parts = val.split('.')
  if (parts.length > 2) val = parts[0] + '.' + parts.slice(1).join('')

  const numero = parseFloat(val)
  emit('update:modelValue', isNaN(numero) ? undefined : numero)
}

const onBlur = () => {
  // Al salir del input, le ponemos las comas bonitas
  displayValue.value = formatNumber(props.modelValue)
}

</script>

<template>
    <div class="relative flex items-center">
      <span class="absolute left-3 text-muted-foreground select-none">$</span>
      <Input
            v-model="displayValue"
            :id="id"
            type="text"
            :placeholder="placeholder || '0.00'"
            class="pl-7 pr-4"
            @input="onInput"
            @blur="onBlur"
      />
    </div>
</template>