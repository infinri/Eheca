import React from 'react';
import { Link } from 'react-router-dom';

export default function HomePage() {
  return (
    <div className="max-w-4xl mx-auto p-6">
      <header className="text-center mb-12">
        <h1 className="text-4xl font-bold text-gray-900 mb-4">Welcome to Eheca</h1>
        <p className="text-xl text-gray-600">Your all-in-one platform for managing your business</p>
      </header>
      
      <div className="grid md:grid-cols-2 gap-8 mb-12">
        <div className="bg-white p-6 rounded-lg shadow-md">
          <h2 className="text-2xl font-semibold mb-4">For Customers</h2>
          <p className="mb-4 text-gray-700">Access your account, manage your profile, and more.</p>
          <div className="space-y-3">
            <Link 
              to="/login" 
              className="block w-full text-center bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition-colors"
            >
              Customer Login
            </Link>
            <Link 
              to="/register" 
              className="block w-full text-center border border-blue-600 text-blue-600 py-2 px-4 rounded hover:bg-blue-50 transition-colors"
            >
              Create Account
            </Link>
          </div>
        </div>
        
        <div className="bg-white p-6 rounded-lg shadow-md">
          <h2 className="text-2xl font-semibold mb-4">For Administrators</h2>
          <p className="mb-4 text-gray-700">Access the admin dashboard to manage the platform.</p>
          <Link 
            to="/tonalli/login" 
            className="block w-full text-center bg-gray-800 text-white py-2 px-4 rounded hover:bg-gray-900 transition-colors"
          >
            Admin Login
          </Link>
        </div>
      </div>
      
      <div className="text-center text-gray-600">
        <p>Need help? <Link to="/contact" className="text-blue-600 hover:underline">Contact support</Link></p>
      </div>
    </div>
  );
}
