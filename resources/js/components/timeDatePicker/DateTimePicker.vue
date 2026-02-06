<script setup>
import { computed } from 'vue';
import { format, parseISO, isValid } from 'date-fns';
import { es } from 'date-fns/locale';
import { Calendar as CalendarIcon } from 'lucide-vue-next';

// Shadcn UI (Asegúrate de tener estos instalados)
import { Calendar } from '@/components/ui/calendar';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import TimeSpanInput from './TimeSpanInput.vue';

const props = defineProps({
  modelValue: {
    type: Object,
    required: true,
  }
});

const emit = defineEmits(['update:modelValue']);

// Helper para convertir el string de fecha que viene de Inertia a un objeto Date de JS
const dateValue = computed(() => {
  if (!props.modelValue.date) return null;
  const parsed = parseISO(props.modelValue.date);
  return isValid(parsed) ? parsed : null;
});

// Helper para asegurar que la duración sea un número para el componente interno
const durationValue = computed(() => {
  return parseInt(props.modelValue.duration) || 0;
});

const updateDate = (newDate) => {
  if (!newDate) return;
  // Formateamos a YYYY-MM-DD para que Laravel lo reciba sin problemas
  const dateString = format(newDate, 'yyyy-MM-dd');
  emit('update:modelValue', { 
    ...props.modelValue, 
    date: dateString 
  });
};

const updateDuration = (newSeconds) => {
  emit('update:modelValue', { 
    ...props.modelValue, 
    duration: String(newSeconds) // Lo devolvemos como string para consistencia
  });
};
</script>

<template>
  <div class="space-y-4 p-4 border rounded-md bg-white">
    <div class="flex flex-col gap-1.5">
      <label class="text-sm font-medium leading-none">Fecha</label>
      <Popover>
        <PopoverTrigger as-child>
          <Button
            variant="outline"
            :class="cn(
              'w-full justify-start text-left font-normal',
              !dateValue && 'text-muted-foreground'
            )"
          >
            <CalendarIcon class="mr-2 h-4 w-4" />
            {{ dateValue ? format(dateValue, "PPP", { locale: es }) : "Seleccionar fecha" }}
          </Button>
        </PopoverTrigger>
        <PopoverContent class="w-auto p-0" align="start">
          <Calendar
            :model-value="dateValue"
            @update:model-value="updateDate"
            initial-focus
          />
        </PopoverContent>
      </Popover>
    </div>

    <div class="flex flex-col gap-1.5">
      <label class="text-sm font-medium leading-none">Duración</label>
      <TimeSpanInput 
        :model-value="durationValue" 
        @update:model-value="updateDuration"
      />
    </div>
  </div>
</template>