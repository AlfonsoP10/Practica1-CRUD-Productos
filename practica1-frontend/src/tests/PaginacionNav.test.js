import { mount } from '@vue/test-utils'
import { describe, expect, it } from 'vitest'
import PaginacionNav from '@/components/PaginacionNav.vue'

describe('PaginacionNav', () => {
  const meta = {
    current_page: 2,
    last_page: 5,
    per_page: 15,
    total: 70,
  }

  it('muestra la página actual y total', () => {
    const wrapper = mount(PaginacionNav, {
      props: { meta },
    })

    expect(wrapper.text()).toContain('Página 2 de 5')
  })

  it('emite cambio-pagina al hacer click en siguiente', async () => {
    const wrapper = mount(PaginacionNav, {
      props: { meta },
    })

    const botones = wrapper.findAll('button')
    await botones[2].trigger('click')

    expect(wrapper.emitted('cambio-pagina')).toBeTruthy()
    expect(wrapper.emitted('cambio-pagina')[0]).toEqual([3])
  })
  it('no muestra la paginación si solo hay una página', () => {
  const wrapper = mount(PaginacionNav, {
    props: {
      meta: {
        current_page: 1,
        last_page: 1,
        per_page: 15,
        total: 10,
      },
    },
  })

  expect(wrapper.text()).toBe('')
})
})