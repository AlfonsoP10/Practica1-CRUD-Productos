import axios from 'axios'

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
})

export const getProductos = () => api.get('/productos')

export const createProducto = (data) =>
  api.post('/productos', data, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })

export const updateProducto = (id, data) =>
  api.post(`/productos/${id}?_method=PUT`, data, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })

export const deleteProducto = (id) => api.delete(`/productos/${id}`)