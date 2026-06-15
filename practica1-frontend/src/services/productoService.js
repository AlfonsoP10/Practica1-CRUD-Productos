import axios from 'axios'

const api = axios.create({
  baseURL: 'http://localhost:8000/api/v1',
  headers: {
    Accept: 'application/json',
  },
})

api.interceptors.request.use((config) => {
  const token = localStorage.getItem('token')

  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }

  return config
})

export const getProductos = () => api.get('/productos')

export const createProducto = (data) =>
  api.post('/productos', data, {
    headers: {
      'Content-Type': 'multipart/form-data',
    },
  })

export const updateProducto = (id, data) => {
  data.append('_method', 'PUT')

  return api.post(`/productos/${id}`, data, {
    headers: {
      'Content-Type': 'multipart/form-data',
    },
  })
}

export const deleteProducto = (id) => api.delete(`/productos/${id}`)