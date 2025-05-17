import React, { useEffect, useState } from 'react';
import { Navigate, useLocation } from 'react-router-dom';

/**
 * ProtectedRoute guards routes that require authentication and specific roles.
 * 
 * @param {Object} props - Component props
 * @param {React.ReactNode} props.children - Child components to render if authenticated
 * @param {string[]} [props.roles] - Array of allowed roles (e.g., ['ROLE_ADMIN', 'ROLE_USER'])
 * @param {string} [props.redirectPath] - Path to redirect if not authenticated
 * @param {React.ReactNode} [props.loadingComponent] - Component to show while checking auth status
 * @returns {React.ReactNode} Rendered component or redirect
 */
export default function ProtectedRoute({ 
  children, 
  roles = [], 
  redirectPath = '/login',
  loadingComponent = <div>Loading...</div>
}) {
  const [auth, setAuth] = useState({
    isAuthenticated: false,
    user: null,
    isLoading: true,
    error: null
  });
  const location = useLocation();

  useEffect(() => {
    const checkAuth = async () => {
      try {
        const response = await fetch('/account');
        if (!response.ok) throw new Error('Not authenticated');
        
        const user = await response.json();
        setAuth({
          isAuthenticated: true,
          user,
          isLoading: false,
          error: null
        });
      } catch (error) {
        setAuth(prev => ({
          ...prev,
          isAuthenticated: false,
          isLoading: false,
          error: error.message
        }));
      }
    };

    checkAuth();
  }, []);

  // Show loading state
  if (auth.isLoading) {
    return loadingComponent;
  }

  // Redirect if not authenticated
  if (!auth.isAuthenticated) {
    return (
      <Navigate 
        to={redirectPath} 
        state={{ from: location }} 
        replace 
      />
    );
  }

  // Check if user has required role
  if (roles.length > 0) {
    const hasRole = auth.user?.roles?.some(role => roles.includes(role));
    if (!hasRole) {
      return <Navigate to="/unauthorized" state={{ from: location }} replace />;
    }
  }

  // If authenticated and has required role, render children
  return children;
}

/**
 * Higher Order Component for protecting admin routes
 * @param {React.ComponentType} Component - Component to protect
 * @returns {React.ComponentType} Protected component
 */
export const withAdminProtection = (Component) => {
  return function WithAdminProtection(props) {
    return (
      <ProtectedRoute roles={['ROLE_ADMIN']}>
        <Component {...props} />
      </ProtectedRoute>
    );
  };
};

/**
 * Higher Order Component for protecting customer routes
 * @param {React.ComponentType} Component - Component to protect
 * @returns {React.ComponentType} Protected component
 */
export const withCustomerProtection = (Component) => {
  return function WithCustomerProtection(props) {
    return (
      <ProtectedRoute roles={['ROLE_USER', 'ROLE_ADMIN']}>
        <Component {...props} />
      </ProtectedRoute>
    );
  };
};
