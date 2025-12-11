import { ref, computed } from 'vue';
import axios from 'axios';

const user = ref(null);
const token = ref(localStorage.getItem('auth_token') || null);
const isLoading = ref(false);
const error = ref(null);

// Setup axios with auth header
if (token.value) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`;
}

export function useAuth() {
  const isAuthenticated = computed(() => !!token.value);

  const login = async (email, password) => {
    isLoading.value = true;
    error.value = null;

    try {
      const response = await axios.post('/api/login', {
        email,
        password,
      });

      token.value = response.data.token;
      user.value = response.data.user;

      // Store token in localStorage
      localStorage.setItem('auth_token', token.value);

      // Set auth header for future requests
      axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`;

      return response.data;
    } catch (err) {
      error.value = err.response?.data?.message || 'Login failed';
      throw err;
    } finally {
      isLoading.value = false;
    }
  };

  const register = async (name, email, password, passwordConfirmation) => {
    isLoading.value = true;
    error.value = null;

    try {
      const response = await axios.post('/api/register', {
        name,
        email,
        password,
        password_confirmation: passwordConfirmation,
      });

      token.value = response.data.token;
      user.value = response.data.user;

      localStorage.setItem('auth_token', token.value);
      axios.defaults.headers.common['Authorization'] = `Bearer ${token.value}`;

      return response.data;
    } catch (err) {
      error.value = err.response?.data?.message || 'Registration failed';
      throw err;
    } finally {
      isLoading.value = false;
    }
  };

  const logout = async () => {
    isLoading.value = true;
    error.value = null;

    try {
      await axios.post('/api/logout');
    } catch (err) {
      console.error('Logout error:', err);
    } finally {
      token.value = null;
      user.value = null;
      localStorage.removeItem('auth_token');
      delete axios.defaults.headers.common['Authorization'];
      isLoading.value = false;
    }
  };

  const fetchUser = async () => {
    if (!token.value) return;

    try {
      const response = await axios.get('/api/user');
      user.value = response.data;
    } catch (err) {
      console.error('Failed to fetch user:', err);
      // If unauthorized, clear token
      if (err.response?.status === 401) {
        token.value = null;
        localStorage.removeItem('auth_token');
      }
    }
  };

  return {
    user,
    token,
    isAuthenticated,
    isLoading,
    error,
    login,
    register,
    logout,
    fetchUser,
  };
}
