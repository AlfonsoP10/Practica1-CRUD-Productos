import { mount } from '@vue/test-utils'
import { describe, expect, it, vi, beforeEach } from 'vitest'
import CartIcon from '@/components/CartIcon.vue'

let totalItemsMock = 0

vi.mock('@/stores/carrito', () => ({
  useCarritoStore: () => ({
    get totalItems() {
      return totalItemsMock
    },
  }),
}))

describe('CartIcon', () => {
  beforeEach(() => {
    totalItemsMock = 0
  })

  it('muestra el icono del carrito', () => {
    const wrapper = mount(CartIcon, {
      global: {
        stubs: {
          RouterLink: {
            template: '<a><slot /></a>',
          },
        },
      },
    })

    expect(wrapper.text()).toContain('🛒')
  })

  it('no muestra badge si el carrito está vacío', () => {
    totalItemsMock = 0

    const wrapper = mount(CartIcon, {
      global: {
        stubs: {
          RouterLink: {
            template: '<a><slot /></a>',
          },
        },
      },
    })

    expect(wrapper.find('.badge').exists()).toBe(false)
  })

  it('muestra el badge cuando hay productos', () => {
    totalItemsMock = 3

    const wrapper = mount(CartIcon, {
      global: {
        stubs: {
          RouterLink: {
            template: '<a><slot /></a>',
          },
        },
      },
    })

    expect(wrapper.find('.badge').exists()).toBe(true)
    expect(wrapper.text()).toContain('3')
  })
})