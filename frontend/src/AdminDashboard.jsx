import React from 'react';
import { useNavigate } from 'react-router-dom';

/**
 * AdminDashboard stub for admin-only area.
 */
export default function AdminDashboard() {
  const navigate = useNavigate();
  const handleLogout = async () => {
    await fetch('/logout', { method: 'POST' });
    navigate('/tonalli/login');
  };
  return (
    <div className="max-w-md mx-auto p-6 bg-white rounded shadow">
      <h2 className="text-2xl font-bold mb-4">Admin Dashboard</h2>
      <div className="mb-4">Welcome, Admin!</div>
      <button className="w-full bg-gray-700 text-white p-2 rounded" onClick={handleLogout}>Logout</button>
    </div>
  );
}
