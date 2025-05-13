import React from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import LoginForm from './LoginForm';
import RegisterForm from './RegisterForm';
import AccountPanel from './AccountPanel';
import AdminDashboard from './AdminDashboard';
// import ProtectedRoute from './ProtectedRoute'; // Enable when backend supports JWT/cookie auth

/**
 * Eheca Homepage UI with registration, login, and account info panels.
 * Clean, modern, and ready for backend API integration.
 */
export default function App() {
  return (
    <Router>
      <div className="min-h-screen bg-gray-100 flex flex-col justify-center items-center">
        <Routes>
          <Route path="/login" element={<LoginForm />} />
          <Route path="/register" element={<RegisterForm />} />
          <Route path="/account" element={<AccountPanel />} />
          <Route path="/tonalli/login" element={<LoginForm />} />
          <Route path="/tonalli/dashboard" element={<AdminDashboard />} />
          <Route path="/" element={<Navigate to="/login" />} />
        </Routes>
      </div>
    </Router>
  );
}
