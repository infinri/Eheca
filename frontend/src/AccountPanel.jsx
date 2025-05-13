import React, { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';

/**
 * AccountPanel displays current user info and provides logout.
 */
export default function AccountPanel() {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const navigate = useNavigate();

  useEffect(() => {
    fetch('/account')
      .then(res => {
        if (!res.ok) throw new Error('Not authenticated');
        return res.json();
      })
      .then(data => setUser(data))
      .catch(() => navigate('/login'))
      .finally(() => setLoading(false));
  }, [navigate]);

  const handleLogout = async () => {
    await fetch('/logout', { method: 'POST' });
    navigate('/login');
  };

  if (loading) return <div>Loading account...</div>;
  if (error) return <div className="text-red-600">{error}</div>;

  return (
    <div className="max-w-md mx-auto p-6 bg-white rounded shadow">
      <h2 className="text-2xl font-bold mb-4">Account Info</h2>
      {user && (
        <div className="mb-4">
          <div><strong>Email:</strong> {user.email}</div>
          <div><strong>Roles:</strong> {user.roles.join(', ')}</div>
        </div>
      )}
      <button className="w-full bg-gray-700 text-white p-2 rounded" onClick={handleLogout}>Logout</button>
    </div>
  );
}
