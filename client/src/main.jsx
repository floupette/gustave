import React from 'react';
import ReactDOM from 'react-dom/client';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import './index.css';
import Example from './pages/Example/Example';

ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <BrowserRouter>
      <Routes>
        { /* Routes should be declared here. */ }
        <Route path='/' element={ <Example /> } />
      </Routes>
    </BrowserRouter>
  </React.StrictMode>,
);
