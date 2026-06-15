import { mount } from '@vue/test-utils'
import { describe, expect, it } from 'vitest'
import InputField from '@/components/InputField.vue'

describe('InputField', () => {
  it('muestra el label recibido por props', () => {
    const wrapper = mount(InputField, {
      props: {
        label: 'Nombre',
        modelValue: '',
      },
    })

    expect(wrapper.text()).toContain('Nombre')
  })

  it('emite update:modelValue al escribir', async () => {
    const wrapper = mount(InputField, {
      props: {
        label: 'Email',
        modelValue: '',
      },
    })

    const input = wrapper.find('input')
    await input.setValue('test@test.com')

    expect(wrapper.emitted('update:modelValue')).toBeTruthy()
    expect(wrapper.emitted('update:modelValue')[0]).toEqual(['test@test.com'])
  })
})