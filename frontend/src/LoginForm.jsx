import React, { useState } from 'react';
import { useLocation, useNavigate } from 'react-router-dom';

/**
 * LoginForm handles both customer and admin login based on route context.
 * - Shows register link for customers only.
 * - Calls appropriate API endpoint.
 */
export default function LoginForm() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [error, setError] = useState('');
  const [loading, setLoading] = useState(false);
  const location = useLocation();
  const navigate = useNavigate();

  const isAdmin = location.pathname.startsWith('/tonalli');
  const loginEndpoint = isAdmin ? '/tonalli/login' : '/login';

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');
    try {
      const response = await fetch(loginEndpoint, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
      });
      if (!response.ok) throw new Error('Invalid credentials');
      const data = await response.json();
      // Redirect based on role
      if (data.roles && data.roles.includes('ROLE_ADMIN')) {
        navigate('/tonalli/dashboard');
      } else {
        navigate('/account');
      }
    } catch (err) {
      setError(err.message || 'Login failed');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="max-w-md mx-auto p-6 bg-white rounded shadow">
      <h2 className="text-2xl font-bold mb-4">{isAdmin ? 'Admin Login' : 'Login'}</h2>
      <form onSubmit={handleSubmit} className="space-y-4">
        <input
          type="email"
          className="w-full border p-2 rounded"
          placeholder="Email"
          value={email}
          onChange={e => setEmail(e.target.value)}
          required
        />
        <input
          type="password"
          className="w-full border p-2 rounded"
          placeholder="Password"
          value={password}
          onChange={e => setPassword(e.target.value)}
          required
        />
        {error && <div className="text-red-600 text-sm">{error}</div>}
        <button type="submit" className="w-full bg-blue-600 text-white p-2 rounded" disabled={loading}>
          {loading ? 'Logging in...' : 'Login'}
        </button>
      </form>
      {!isAdmin && (
        <div className="mt-4 text-center">
          <span>Don't have an account?</span>
          <button className="text-blue-600 ml-1 underline" onClick={() => navigate('/register')}>Register</button>
        </div>
      )}
    </div>
  );
}
