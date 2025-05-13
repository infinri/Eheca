import React from 'react';
import { Navigate } from 'react-router-dom';

/**
 * ProtectedRoute guards routes that require authentication.
 * Usage: <ProtectedRoute><Component /></ProtectedRoute>
 */
export default function ProtectedRoute({ children }) {
  const isAuthenticated = !!localStorage.getItem('eheca_auth'); // Placeholder, replace with real check
  // For demo: always allow. Replace with proper logic after backend JWT/cookie integration
  return isAuthenticated ? children : <Navigate to="/login" />;
}
