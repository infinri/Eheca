import React, { useState } from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate, useLocation } from 'react-router-dom';
import LoginForm from './LoginForm';
import RegisterForm from './RegisterForm';
import ForgotPassword from './ForgotPassword';
import ResetPassword from './ResetPassword';
import CustomerDashboard from './CustomerDashboard';
import AdminDashboard from './AdminDashboard';
import HomePage from './HomePage';
import { ProtectedRoute, withAdminProtection, withCustomerProtection } from './ProtectedRoute';

// Toast notification component
const ToastNotification = ({ message, type = 'success', onClose }) => {
  if (!message) return null;
  
  const bgColor = type === 'error' ? 'bg-red-100 border-red-500' : 'bg-green-100 border-green-500';
  const textColor = type === 'error' ? 'text-red-700' : 'text-green-700';
  const icon = type === 'error' ? (
    <svg className="h-6 w-6 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
    </svg>
  ) : (
    <svg className="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
    </svg>
  );

  return (
    <div className={`fixed top-4 right-4 border-l-4 p-4 ${bgColor} rounded shadow-lg z-50`}>
      <div className="flex items-center">
        <div className="flex-shrink-0">
          {icon}
        </div>
        <div className="ml-3">
          <p className={`text-sm font-medium ${textColor}`}>
            {message}
          </p>
        </div>
        <div className="ml-4 flex-shrink-0 flex">
          <button
            onClick={onClose}
            className="inline-flex text-gray-400 focus:outline-none focus:text-gray-500 transition ease-in-out duration-150"
          >
            <svg className="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path fillRule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clipRule="evenodd" />
            </svg>
          </button>
        </div>
      </div>
    </div>
  );
};

// Main App component
const AppContent = () => {
  const location = useLocation();
  const [notification, setNotification] = useState({
    message: location.state?.message || '',
    type: location.state?.type || 'success'
  });

  // Clear location state to prevent showing the message again on refresh
  if (location.state?.message) {
    window.history.replaceState({}, document.title);
  }

  const closeNotification = () => {
    setNotification({ message: '', type: 'success' });
  };

  return (
    <div className="min-h-screen bg-gray-100">
      {/* Global notification */}
      <ToastNotification 
        message={notification.message} 
        type={notification.type} 
        onClose={closeNotification} 
      />
      
      <Routes>
        {/* Public routes */}
        <Route path="/" element={<HomePage />} />
        <Route path="/login" element={<LoginForm />} />
        <Route path="/register" element={<RegisterForm />} />
        <Route path="/forgot-password" element={<ForgotPassword />} />
        <Route path="/reset-password" element={<ResetPassword />} />
        
        {/* Protected customer routes */}
        <Route 
          path="/dashboard/*" 
          element={
            <ProtectedRoute>
              <CustomerDashboard />
            </ProtectedRoute>
          } 
        />
        
        {/* Protected admin routes */}
        <Route 
          path="/tonalli/*" 
          element={
            <ProtectedRoute roles={['ROLE_ADMIN']} redirectPath="/">
              <AdminDashboard />
            </ProtectedRoute>
          } 
        />
        
        {/* 404 - Not Found */}
        <Route path="*" element={
          <div className="min-h-screen flex items-center justify-center">
            <div className="text-center">
              <h1 className="text-4xl font-bold text-gray-900 mb-4">404</h1>
              <p className="text-xl text-gray-600 mb-6">Page not found</p>
              <a 
                href="/" 
                className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
              >
                Go to Homepage
              </a>
            </div>
          </div>
        } />
      </Routes>
    </div>
  );
};

// App wrapper with Router
const App = () => (
  <Router>
    <AppContent />
  </Router>
);

export default App;
