import React from 'react';

const Layout = ({ children }) => {
    return (
        <div className="min-h-screen flex flex-col">
            <header className="bg-red-900/80 text-white p-4">
                <h1>My Website</h1>
            </header>
            <main className="flex-grow p-4">
                {children}
            </main>
            <footer className="bg-red-900/80 text-white p-4 text-center">
                &copy; 2023 Example Next - Php App
            </footer>
        </div>
    );
};

export default Layout;